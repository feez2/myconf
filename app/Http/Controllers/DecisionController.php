<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\Conference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaperDecisionMail;

class DecisionController extends Controller
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
        return view('decisions.select-conference', compact('conferences'));
    }

    public function index(Conference $conference, Request $request)
    {
        $this->authorize('makeDecisions', $conference);

        $query = $conference->papers()
            ->with(['author', 'reviews.reviewer'])
            ->whereHas('reviews', function ($query) {
                $query->where('status', 'completed');
            });

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by title
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'ilike', "%{$search}%");
        }

        $papers = $query->orderBy('title')->paginate(10)->appends($request->all());

        return view('decisions.index', compact('conference', 'papers'));
    }

    public function show(Paper $paper)
    {
        $this->authorize('makeDecisions', $paper->conference);

        $paper->load(['reviews.reviewer', 'conference']);

        return view('decisions.show', compact('paper'));
    }

    public function create(Paper $paper)
    {
        $this->authorize('makeDecisions', $paper->conference);

        if (in_array($paper->status, [Paper::STATUS_ACCEPTED, Paper::STATUS_REJECTED])) {
            return redirect()->route('decisions.show', $paper)
                ->with('error', 'This paper already has a final decision.');
        }

        $paper->load(['reviews.reviewer', 'conference']);

        return view('decisions.create', compact('paper'));
    }

    public function update(Request $request, Paper $paper)
    {
        $this->authorize('makeDecisions', $paper->conference);

        $validated = $request->validate([
            'decision' => 'required|in:accept,revision,reject',
            'decision_notes' => 'required|string|min:20|max:1000',
            'camera_ready_deadline' => 'nullable|date|after:today'
        ]);

        // Update paper status
        $newStatus = $this->getStatusFromDecision($validated['decision']);
        $paper->update([
            'status' => $newStatus,
            'decision_notes' => $validated['decision_notes'],
            'decision_made_at' => now(),
            'decision_made_by' => auth()->id(),
            'camera_ready_deadline' => $validated['decision'] === 'accept'
                ? $validated['camera_ready_deadline']
                : null
        ]);

        // Send notification
        Mail::to($paper->author->email)
            ->queue(new PaperDecisionMail($paper));

        return redirect()->route('decisions.index', $paper->conference)
            ->with('success', 'Decision recorded and author notified!');
    }

    protected function getStatusFromDecision($decision)
    {
        return match($decision) {
            'accept' => Paper::STATUS_ACCEPTED,
            'revision' => Paper::STATUS_REVISION_REQUIRED,
            'reject' => Paper::STATUS_REJECTED,
            default => Paper::STATUS_UNDER_REVIEW
        };
    }
}
