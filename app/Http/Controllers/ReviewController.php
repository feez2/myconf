<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // Get conferences where user is an accepted PC member
        $conferences = auth()->user()->programCommittees()
            ->where('status', 'accepted')
            ->pluck('conference_id');

        // Get all papers from these conferences
        $query = Paper::whereIn('conference_id', $conferences)
            ->with(['conference', 'author', 'reviews' => function($query) {
                $query->where('reviewer_id', auth()->id());
            }]);

        // Filter by review status
        if ($request->filled('review_status')) {
            switch ($request->review_status) {
                case 'not_started':
                    $query->whereDoesntHave('reviews', function($q) {
                        $q->where('reviewer_id', auth()->id());
                    });
                    break;
                case 'pending':
                    $query->whereHas('reviews', function($q) {
                        $q->where('reviewer_id', auth()->id())
                          ->where('status', 'pending');
                    });
                    break;
                case 'completed':
                    $query->whereHas('reviews', function($q) {
                        $q->where('reviewer_id', auth()->id())
                          ->where('status', 'completed');
                    });
                    break;
            }
        }

        // Search by title
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'ilike', "%{$search}%");
        }

        $papers = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->all());

        return view('reviews.index', compact('papers'));
    }

    public function show(Review $review)
    {
        $this->authorize('view', $review);

        return view('reviews.show', compact('review'));
    }

    public function edit(Review $review)
    {
        $this->authorize('update', $review);

        if ($review->isCompleted()) {
            return redirect()->route('reviews.show', $review)
                ->with('error', 'This review has already been completed.');
        }

        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $this->authorize('update', $review);

        $validated = $request->validate([
            'score' => 'required|integer|min:1|max:10',
            'recommendation' => 'required|in:' . implode(',', array_keys(Review::recommendationOptions())),
            'comments' => 'required|string|min:50|max:2000',
            'confidential_comments' => 'nullable|string|max:1000'
        ]);

        $review->update([
            'score' => $validated['score'],
            'recommendation' => $validated['recommendation'],
            'comments' => $validated['comments'],
            'confidential_comments' => $validated['confidential_comments'],
            'status' => Review::STATUS_COMPLETED,
            'completed_at' => now()
        ]);

        // Update paper status based on reviews
        $this->updatePaperStatus($review->paper);

        return redirect()->route('reviews.show', $review)
            ->with('success', 'Review submitted successfully!');
    }

    public function accept(Review $review)
    {
        $this->authorize('update', $review);

        if ($review->status !== Review::STATUS_REQUESTED) {
            return redirect()->back()
                ->with('error', 'This review request has already been processed.');
        }

        $review->update([
            'status' => Review::STATUS_ACCEPTED,
            'accepted_at' => now()
        ]);

        return redirect()->route('reviews.show', $review)
            ->with('success', 'Review request accepted. You can now start reviewing the paper.');
    }

    public function reject(Request $request, Review $review)
    {
        $this->authorize('update', $review);

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        if ($review->status !== Review::STATUS_REQUESTED) {
            return redirect()->back()
                ->with('error', 'This review request has already been processed.');
        }

        $review->update([
            'status' => Review::STATUS_REJECTED,
            'rejection_reason' => $validated['rejection_reason'],
            'rejected_at' => now()
        ]);

        return redirect()->route('reviews.index')
            ->with('success', 'Review request rejected.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paper_id' => 'required|exists:papers,id'
        ]);

        $paper = Paper::findOrFail($validated['paper_id']);

        // Check if user is a PC member for this conference
        if (!auth()->user()->programCommittees()
            ->where('conference_id', $paper->conference_id)
            ->where('status', 'accepted')
            ->exists()) {
            abort(403, 'You are not authorized to review papers for this conference.');
        }

        // Check if review already exists
        if ($paper->reviews()->where('reviewer_id', auth()->id())->exists()) {
            return redirect()->back()
                ->with('error', 'You have already started a review for this paper.');
        }

        // Create review
        $review = Review::create([
            'paper_id' => $paper->id,
            'reviewer_id' => auth()->id(),
            'status' => Review::STATUS_PENDING
        ]);

        return redirect()->route('reviews.edit', $review)
            ->with('success', 'Review started successfully. You can now provide your review.');
    }

    protected function updatePaperStatus(Paper $paper)
    {
        $completedReviews = $paper->reviews()->completed()->get();

        if ($completedReviews->count() >= config('conference.min_reviews_for_decision', 3)) {
            $avgScore = $completedReviews->avg('score');
            $recommendations = $completedReviews->pluck('recommendation');

            // Decision logic
            if ($avgScore >= config('conference.acceptance_threshold', 8) &&
                $recommendations->contains(Review::RECOMMEND_ACCEPT)) {
                $paper->update(['status' => Paper::STATUS_ACCEPTED]);
            }
            elseif ($avgScore >= config('conference.revision_threshold', 5)) {
                // Check if majority recommends revision
                $revisionCount = $recommendations->filter(function($rec) {
                    return in_array($rec, [
                        Review::RECOMMEND_MINOR_REVISION,
                        Review::RECOMMEND_MAJOR_REVISION
                    ]);
                })->count();

                if ($revisionCount > ($completedReviews->count() / 2)) {
                    $paper->update(['status' => Paper::STATUS_REVISION_REQUIRED]);
                } else {
                    $paper->update(['status' => Paper::STATUS_REJECTED]);
                }
            }
            else {
                $paper->update(['status' => Paper::STATUS_REJECTED]);
            }

            // Notify author about decision
            $this->notifyAuthorAboutDecision($paper);
        }
    }

    protected function notifyAuthorAboutDecision(Paper $paper)
    {
        // Implement notification logic here
        // This could be an email notification or system notification
    }
}
