<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Models\ProgramBook;
use App\Models\Session;
use App\Models\Presentation;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ProgramBookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function selectConference()
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('conferences.index')
                ->with('error', 'Unauthorized access.');
        }

        $conferences = Conference::orderBy('title')->get();
        return view('program-book.select-conference', compact('conferences'));
    }

    public function index(Conference $conference)
    {
        $this->authorize('manageProgramBook', $conference);

        $programBook = $conference->programBook()->first();

        if (!$programBook) {
            $programBook = $conference->programBook()->create([
                'title' => $conference->title . ' Program Book',
                'start_date' => $conference->start_date,
                'end_date' => $conference->end_date,
                'welcome_message' => 'Welcome to ' . $conference->title,
                'general_information' => 'Conference Dates: ' . $conference->start_date->format('F j, Y') . ' - ' . $conference->end_date->format('F j, Y') . "\n" .
                                      'Location: ' . $conference->location
            ]);
        }

        $acceptedPapers = $conference->papers()
            ->where('status', Paper::STATUS_ACCEPTED)
            ->orderBy('title')
            ->get();

        return view('program-book.index', compact('conference', 'programBook', 'acceptedPapers'));
    }

    public function create(Conference $conference)
    {
        $this->authorize('manageProgramBook', $conference);

        return view('program-book.create', compact('conference'));
    }

    public function store(Request $request, Conference $conference)
    {
        $this->authorize('manageProgramBook', $conference);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:' . $conference->start_date->format('Y-m-d'),
            'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:' . $conference->end_date->format('Y-m-d'),
            'welcome_message' => 'nullable|string',
            'general_information' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        $programBook = new ProgramBook($validated);
        $programBook->conference_id = $conference->id;

        if ($request->hasFile('cover_image')) {
            $programBook->cover_image_path = $request->file('cover_image')->store('program-books/covers', 'public');
        }

        $programBook->save();

        return redirect()->route('program-book.manage-sessions', $programBook)
            ->with('success', 'Program book created successfully! Now you can add sessions.');
    }

    public function edit(ProgramBook $programBook)
    {
        $this->authorize('manageProgramBook', $programBook->conference);

        return view('program-book.edit', compact('programBook'));
    }

    public function update(Request $request, ProgramBook $programBook)
    {
        $this->authorize('manageProgramBook', $programBook->conference);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:' . $programBook->conference->start_date->format('Y-m-d'),
            'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:' . $programBook->conference->end_date->format('Y-m-d'),
            'welcome_message' => 'nullable|string',
            'general_information' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        $programBook->fill($validated);

        if ($request->hasFile('cover_image')) {
            if ($programBook->cover_image_path) {
                Storage::disk('public')->delete($programBook->cover_image_path);
            }
            $programBook->cover_image_path = $request->file('cover_image')->store('program-books/covers', 'public');
        }

        $programBook->save();

        return redirect()->route('program-book.index', $programBook->conference)
            ->with('success', 'Program book updated successfully!');
    }

    public function manageSessions(ProgramBook $programBook)
    {
        $this->authorize('manageProgramBook', $programBook->conference);

        $sessions = $programBook->sessions()->orderBy('start_time')->get();
        $acceptedPapers = $programBook->conference->papers()
            ->where('status', Paper::STATUS_ACCEPTED)
            ->whereDoesntHave('presentation')
            ->orderBy('title')
            ->get();

        return view('program-book.manage-sessions', compact('programBook', 'sessions', 'acceptedPapers'));
    }

    public function storeSession(Request $request, ProgramBook $programBook)
    {
        $this->authorize('manageProgramBook', $programBook->conference);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date|after_or_equal:' . $programBook->start_date->format('Y-m-d') . '|before_or_equal:' . $programBook->end_date->format('Y-m-d'),
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'session_chair' => 'nullable|string|max:255',
            'type' => 'required|in:regular,keynote,workshop,panel',
        ]);

        // Check for time conflicts with existing sessions on the same date and location
        $conflictingSession = $programBook->sessions()
            ->where('date', $validated['date'])
            ->where('location', $validated['location'])
            ->where(function($query) use ($validated) {
                $query->where(function($q) use ($validated) {
                    $q->where('start_time', '<=', $validated['start_time'])
                      ->where('end_time', '>', $validated['start_time']);
                })->orWhere(function($q) use ($validated) {
                    $q->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>=', $validated['end_time']);
                })->orWhere(function($q) use ($validated) {
                    $q->where('start_time', '>=', $validated['start_time'])
                      ->where('end_time', '<=', $validated['end_time']);
                });
            })
            ->first();

        if ($conflictingSession) {
            return back()->withErrors(['time_conflict' => 'This session time conflicts with an existing session at the same location: ' . $conflictingSession->title])->withInput();
        }

        $session = new Session($validated);
        $session->program_book_id = $programBook->id;
        $session->save();

        return redirect()->route('program-book.manage-sessions', $programBook)
            ->with('success', 'Session added successfully!');
    }

    public function updateSession(Request $request, Session $session)
    {
        $this->authorize('manageProgramBook', $session->programBook->conference);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date|after_or_equal:' . $session->programBook->start_date->format('Y-m-d') . '|before_or_equal:' . $session->programBook->end_date->format('Y-m-d'),
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'session_chair' => 'nullable|string|max:255',
            'type' => 'required|in:regular,keynote,workshop,panel',
        ]);

        // Check for time conflicts with existing sessions on the same date and location (excluding current session)
        $conflictingSession = $session->programBook->sessions()
            ->where('id', '!=', $session->id)
            ->where('date', $validated['date'])
            ->where('location', $validated['location'])
            ->where(function($query) use ($validated) {
                $query->where(function($q) use ($validated) {
                    $q->where('start_time', '<=', $validated['start_time'])
                      ->where('end_time', '>', $validated['start_time']);
                })->orWhere(function($q) use ($validated) {
                    $q->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>=', $validated['end_time']);
                })->orWhere(function($q) use ($validated) {
                    $q->where('start_time', '>=', $validated['start_time'])
                      ->where('end_time', '<=', $validated['end_time']);
                });
            })
            ->first();

        if ($conflictingSession) {
            return back()->withErrors(['time_conflict' => 'This session time conflicts with an existing session at the same location: ' . $conflictingSession->title])
            ->withInput();
        }

        $session->update($validated);

        return redirect()->route('program-book.manage-sessions', $session->programBook)
            ->with('success', 'Session updated successfully!');
    }

    public function deleteSession(Session $session)
    {
        $this->authorize('manageProgramBook', $session->programBook->conference);

        $session->delete();

        return redirect()->route('program-book.manage-sessions', $session->programBook)
            ->with('success', 'Session deleted successfully!');
    }

    public function storePresentation(Request $request, Session $session)
    {
        $this->authorize('manageProgramBook', $session->programBook->conference);

        $validated = $request->validate([
            'paper_id' => 'nullable|exists:papers,id',
            'title' => 'required|string|max:255',
            'abstract' => 'nullable|string',
            'start_time' => 'required|date_format:H:i|after_or_equal:' . $session->start_time->format('H:i') . '|before:' . $session->end_time->format('H:i'),
            'end_time' => 'required|date_format:H:i|after:start_time|before_or_equal:' . $session->end_time->format('H:i'),
            'speaker_name' => 'required|string|max:255',
            'speaker_affiliation' => 'nullable|string|max:255',
            'speaker_bio' => 'nullable|string',
            'speaker_photo' => 'nullable|image|max:2048',
        ]);

        // Check for time conflicts with existing presentations in the same session
        $conflictingPresentation = $session->presentations()
            ->where(function($query) use ($validated) {
                $query->where(function($q) use ($validated) {
                    $q->where('start_time', '<=', $validated['start_time'])
                      ->where('end_time', '>', $validated['start_time']);
                })->orWhere(function($q) use ($validated) {
                    $q->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>=', $validated['end_time']);
                })->orWhere(function($q) use ($validated) {
                    $q->where('start_time', '>=', $validated['start_time'])
                      ->where('end_time', '<=', $validated['end_time']);
                });
            })
            ->first();

        if ($conflictingPresentation) {
            return back()->withErrors(['time_conflict' => 'This presentation time conflicts with an existing presentation: ' . $conflictingPresentation->title])->withInput();
        }

        $presentation = new Presentation($validated);
        $presentation->session_id = $session->id;

        if ($request->hasFile('speaker_photo')) {
            $presentation->speaker_photo_path = $request->file('speaker_photo')->store('program-books/speakers', 'public');
        }

        $presentation->save();

        return redirect()->route('program-book.manage-sessions', $session->programBook)
            ->with('success', 'Presentation added successfully!');
    }

    public function updatePresentation(Request $request, Presentation $presentation)
    {
        $this->authorize('manageProgramBook', $presentation->session->programBook->conference);

        $validated = $request->validate([
            'paper_id' => 'nullable|exists:papers,id',
            'title' => 'required|string|max:255',
            'abstract' => 'nullable|string',
            'start_time' => 'required|date_format:H:i|after_or_equal:' . $presentation->session->start_time->format('H:i') . '|before:' . $presentation->session->end_time->format('H:i'),
            'end_time' => 'required|date_format:H:i|after:start_time|before_or_equal:' . $presentation->session->end_time->format('H:i'),
            'speaker_name' => 'required|string|max:255',
            'speaker_affiliation' => 'nullable|string|max:255',
            'speaker_bio' => 'nullable|string',
            'speaker_photo' => 'nullable|image|max:2048',
        ]);

        // Check for time conflicts with existing presentations in the same session (excluding current presentation)
        $conflictingPresentation = $presentation->session->presentations()
            ->where('id', '!=', $presentation->id)
            ->where(function($query) use ($validated) {
                $query->where(function($q) use ($validated) {
                    $q->where('start_time', '<=', $validated['start_time'])
                      ->where('end_time', '>', $validated['start_time']);
                })->orWhere(function($q) use ($validated) {
                    $q->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>=', $validated['end_time']);
                })->orWhere(function($q) use ($validated) {
                    $q->where('start_time', '>=', $validated['start_time'])
                      ->where('end_time', '<=', $validated['end_time']);
                });
            })
            ->first();

        if ($conflictingPresentation) {
            return back()->withErrors(['time_conflict' => 'This presentation time conflicts with an existing presentation: ' . $conflictingPresentation->title])->withInput();
        }

        $presentation->fill($validated);

        if ($request->hasFile('speaker_photo')) {
            if ($presentation->speaker_photo_path) {
                Storage::disk('public')->delete($presentation->speaker_photo_path);
            }
            $presentation->speaker_photo_path = $request->file('speaker_photo')->store('program-books/speakers', 'public');
        }

        $presentation->save();

        return redirect()->route('program-book.manage-sessions', $presentation->session->programBook)
            ->with('success', 'Presentation updated successfully!');
    }

    public function deletePresentation(Presentation $presentation)
    {
        $this->authorize('manageProgramBook', $presentation->session->programBook->conference);

        if ($presentation->speaker_photo_path) {
            Storage::disk('public')->delete($presentation->speaker_photo_path);
        }

        $presentation->delete();

        return redirect()->route('program-book.manage-sessions', $presentation->session->programBook)
            ->with('success', 'Presentation deleted successfully!');
    }

    public function export(ProgramBook $programBook)
    {
        $this->authorize('manageProgramBook', $programBook->conference);

        $sessions = $programBook->sessions()
            ->with('presentations')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->groupBy('date');

        $pdf = Pdf::loadView('program-book.export', [
            'programBook' => $programBook,
            'scheduleByDay' => $sessions,
        ]);

        // Set PDF options for better rendering
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isCssFloatEnabled' => true,
            'defaultFont' => 'serif',
            'dpi' => 150,
            'fontHeightRatio' => 0.9,
        ]);

        return $pdf->download("program-book-{$programBook->conference->acronym}.pdf");
    }
}
