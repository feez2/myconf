<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Models\User;
use App\Models\ProgramCommittee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\PCInvitationMail;
use App\Notifications\ReviewerInvitationNotification;
use App\Mail\ReviewerInvitationMail;

class ConferenceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('can:admin')->except(['index', 'show']);
        // $this->authorizeResource(Conference::class, 'conference');
    }

    /**
     * Display a listing of conferences.
     */
    public function index(Request $request)
    {
        $query = Conference::query();

        // Search by title or acronym
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('acronym', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $now = now();
            switch ($request->status) {
                case 'upcoming':
                    $query->where('start_date', '>', $now);
                    break;
                case 'ongoing':
                    $query->where('start_date', '<=', $now)
                          ->where('end_date', '>=', $now);
                    break;
                case 'completed':
                    $query->where('end_date', '<', $now);
                    break;
            }
        }

        // Order by start date
        $query->orderBy('start_date', 'desc');

        $conferences = $query->paginate(10);

        return view('conferences.index', compact('conferences'));
    }

    /**
     * Show the form for creating a new conference.
     */
    public function create()
    {
        return view('conferences.create');
    }

    /**
     * Store a newly created conference.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'acronym' => 'required|string|max:50',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'website' => 'nullable|url',
            'submission_deadline' => 'required|date|before:start_date',
            'review_deadline' => 'nullable|date|after:submission_deadline|before:start_date',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('conference-logos', 'public');
        }

        Conference::create($validated);

        return redirect()->route('conferences.index')
                         ->with('success', 'Conference created successfully!');
    }

    /**
     * Display the specified conference.
     */
    public function show(Conference $conference)
    {
        $reviewers = User::where('role', 'reviewer')
            ->whereDoesntHave('programCommittees', function($query) use ($conference) {
                $query->where('conference_id', $conference->id);
            })
            ->orderBy('name')
            ->get();

        return view('conferences.show', compact('conference', 'reviewers'));
    }

    /**
     * Show the form for editing the conference.
     */
    public function edit(Conference $conference)
    {
        return view('conferences.edit', compact('conference'));
    }

    /**
     * Update the specified conference.
     */
    public function update(Request $request, Conference $conference)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'acronym' => 'required|string|max:50',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'website' => 'nullable|url',
            'submission_deadline' => 'required|date|before:start_date',
            'review_deadline' => 'nullable|date|after:submission_deadline|before:start_date',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($conference->logo) {
                Storage::disk('public')->delete($conference->logo);
            }
            $validated['logo'] = $request->file('logo')->store('conference-logos', 'public');
        }

        $conference->update($validated);

        return redirect()->route('conferences.index')
                         ->with('success', 'Conference updated successfully!');
    }

    /**
     * Remove the specified conference.
     */
    public function destroy(Conference $conference)
    {
        if ($conference->logo) {
            Storage::disk('public')->delete($conference->logo);
        }

        $conference->delete();

        return redirect()->route('conferences.index')
                         ->with('success', 'Conference deleted successfully!');
    }

    /**
     * Assign chairs to conference
     */
    public function assignChairs(Request $request, Conference $conference)
    {
        $this->authorize('manageSettings', $conference);

        $request->validate([
            'chair_ids' => 'required|array',
            'chair_ids.*' => 'exists:users,id',
        ]);

        $conference->chairs()->sync($request->chair_ids);

        return back()->with('success', 'Conference chairs updated successfully');
    }

    /**
     * Assign program chairs to conference
     */
    public function assignProgramChairs(Request $request, Conference $conference)
    {
        $this->authorize('manageSettings', $conference);

        $request->validate([
            'program_chair_ids' => 'required|array',
            'program_chair_ids.*' => 'exists:users,id',
        ]);

        $conference->programChairs()->sync($request->program_chair_ids);

        return back()->with('success', 'Program chairs updated successfully');
    }

    /**
    * Show chair management page
    */
    public function showChairManagement(Conference $conference)
    {
        $this->authorize('manageSettings', $conference);

        $users = User::whereIn('role', ['admin', 'reviewer'])
                ->orderBy('name')
                ->get();

        return view('conferences.chairs', compact('conference', 'users'));
    }

    public function submissions(Conference $conference)
    {
        // Check if user is authorized to view submissions
        if (auth()->user()->role !== 'admin' &&
            !$conference->chairs()->where('user_id', auth()->id())->exists() &&
            !$conference->programChairs()->where('user_id', auth()->id())->exists()) {
            abort(403, 'Unauthorized action.');
        }

        $query = $conference->papers()->with(['user', 'authors']);

        // Filter by status
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Search by title or author
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('authors', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $papers = $query->latest()->paginate(10);

        return view('conferences.submissions', compact('conference', 'papers'));
    }

    /**
     * Show the track invitations page for a conference.
     *
     * @param  \App\Models\Conference  $conference
     * @return \Illuminate\View\View
     */
    public function trackInvitations(Conference $conference)
    {
        // Allow admin and users with managePC permission
        if (!auth()->user()->isAdmin() && !auth()->user()->can('managePC', $conference)) {
            abort(403, 'This action is unauthorized.');
        }

        $query = $conference->programCommittees()
            ->with('user')
            ->when(request('status'), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when(request('search'), function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            });

        $invitations = $query->paginate(10);

        // Get only reviewers who are not already in the program committee
        $reviewers = User::where('role', 'reviewer')
            ->whereDoesntHave('programCommittees', function ($query) use ($conference) {
                $query->where('conference_id', $conference->id);
            })
            ->orderBy('name')
            ->get();

        return view('conferences.track-invitations', compact('conference', 'invitations', 'reviewers'));
    }

    /**
     * Send invitations to reviewers.
     *
     * @param  \App\Models\Conference  $conference
     * @return \Illuminate\Http\RedirectResponse
     */
    public function inviteReviewers(Request $request, Conference $conference)
    {
        // Allow admin and users with managePC permission
        if (!auth()->user()->isAdmin() && !auth()->user()->can('managePC', $conference)) {
            abort(403, 'This action is unauthorized.');
        }

        $request->validate([
            'reviewer_ids' => ['required', 'array'],
            'reviewer_ids.*' => ['required', 'exists:users,id'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        foreach ($request->reviewer_ids as $reviewerId) {
            $pc = $conference->programCommittees()->create([
                'user_id' => $reviewerId,
                'role' => 'reviewer',
                'status' => 'pending',
                'invitation_message' => $request->message,
                'invited_at' => now()
            ]);

            // Send invitation email
            Mail::to($pc->user->email)
                ->queue(new ReviewerInvitationMail($pc));
        }

        return redirect()->back()->with('success', 'Invitations sent successfully.');
    }

    /**
     * Resend an invitation to a reviewer.
     *
     * @param  \App\Models\Conference  $conference
     * @param  \App\Models\ProgramCommittee  $invitation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resendInvitation(Conference $conference, ProgramCommittee $invitation)
    {
        // Allow admin and users with managePC permission
        if (!auth()->user()->isAdmin() && !auth()->user()->can('managePC', $conference)) {
            abort(403, 'This action is unauthorized.');
        }

        if ($invitation->status !== 'pending') {
            return redirect()->back()->with('error', 'Can only resend pending invitations.');
        }

        // Resend invitation email
        Mail::to($invitation->user->email)
            ->queue(new ReviewerInvitationMail($invitation));

        return redirect()->back()->with('success', 'Invitation resent successfully.');
    }
}
