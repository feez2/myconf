<?php

namespace App\Http\Controllers;

use App\Events\PaperSubmittedEvent;
use App\Events\ReviewAssignedEvent;
use App\Events\ReviewRequestedEvent;
use App\Models\Paper;
use App\Models\Conference;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\ReviewAssigned;

class PaperController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Paper::with('conference')
            ->where('user_id', auth()->id());

        // Search by title or conference acronym
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%")
                  ->orWhereHas('conference', function($qc) use ($search) {
                      $qc->where('acronym', 'ilike', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $papers = $query->latest()->paginate(10)->appends($request->all());

        $statusOptions = \App\Models\Paper::statusOptions();

        return view('papers.index', compact('papers', 'statusOptions'))
            ->with('search', $request->search)
            ->with('status', $request->status);
    }

    public function create(Conference $conference)
    {
        // Check if submissions are open
        if (!$conference->isAcceptingSubmissions()) {
            return redirect()->back()
                ->with('error', 'This conference is not currently accepting submissions.');
        }

        return view('papers.create', compact('conference'));
    }

    public function store(Request $request, Conference $conference)
    {
        // Validate submission deadline
        if (!$conference->isAcceptingSubmissions()) {
            return redirect()->back()
                ->with('error', 'The submission deadline has passed.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string|min:100|max:5000',
            'keywords' => 'required|string',
            'paper_file' => 'required|file|mimes:pdf,doc,docx|max:20480',
            'authors' => 'required|array|min:1',
            'authors.*.name' => 'required|string|max:255',
            'authors.*.is_corresponding' => 'boolean'
        ]);

        // Process file upload
        $file = $request->file('paper_file');
        $fileName = Str::slug($request->title) . '-' . time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('papers', $fileName, 'public');

        // Create paper
        $paper = Paper::create([
            'conference_id' => $conference->id,
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'abstract' => $validated['abstract'],
            'keywords' => $validated['keywords'],
            'file_path' => $filePath,
            'status' => Paper::STATUS_SUBMITTED,
        ]);

        // Add authors
        foreach ($validated['authors'] as $index => $authorData) {
            $paper->authors()->create([
                'name' => $authorData['name'],
                'is_corresponding' => $authorData['is_corresponding'] ?? false,
                'order' => $index,
                'user_id' => auth()->id() // Set the first author as the authenticated user
            ]);
        }

        // Send notification to conference chairs
        event(new PaperSubmittedEvent($paper));

        return redirect()->route('papers.show', $paper)
            ->with('success', 'Paper submitted successfully!');
    }

    public function show(Paper $paper)
    {
        $this->authorize('view', $paper);

        return view('papers.show', compact('paper'));
    }

    public function edit(Paper $paper)
    {
        $this->authorize('update', $paper);

        // Only allow editing if status is submitted
        if ($paper->status !== Paper::STATUS_SUBMITTED) {
            return redirect()->back()
                ->with('error', 'You can only edit papers that are in "Submitted" status.');
        }

        return view('papers.edit', compact('paper'));
    }

    public function update(Request $request, Paper $paper)
    {
        $this->authorize('update', $paper);

        // Only allow updating if status is submitted
        if ($paper->status !== Paper::STATUS_SUBMITTED) {
            return redirect()->back()
                ->with('error', 'You can only update papers that are in "Submitted" status.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string|min:500|max:5000',
            'keywords' => 'required|string',
            'paper_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        // Update file if provided
        if ($request->hasFile('paper_file')) {
            // Delete old file
            Storage::disk('public')->delete($paper->file_path);

            // Store new file
            $file = $request->file('paper_file');
            $fileName = Str::slug($request->title) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('papers', $fileName, 'public');
            $validated['file_path'] = $filePath;
        }

        $paper->update([
            'title' => $validated['title'],
            'abstract' => $validated['abstract'],
            'keywords' => array_map('trim', explode(',', $validated['keywords'])),
            'file_path' => $validated['file_path'] ?? $paper->file_path,
        ]);

        return redirect()->route('papers.show', $paper)
            ->with('success', 'Paper updated successfully!');
    }

    public function destroy(Paper $paper)
    {
        $this->authorize('delete', $paper);

        // Only allow deletion if status is submitted
        if ($paper->status !== Paper::STATUS_SUBMITTED) {
            return redirect()->back()
                ->with('error', 'You can only delete papers that are in "Submitted" status.');
        }

        // Delete file
        Storage::disk('public')->delete($paper->file_path);

        $paper->delete();

        return redirect()->route('papers.index')
            ->with('success', 'Paper deleted successfully!');
    }

    public function assignReviewersForm(Paper $paper)
    {
        $this->authorize('assignReviewers', $paper);

        $reviewers = $paper->conference->reviewers()
            ->whereNotIn('users.id', $paper->reviews()->pluck('reviewer_id'))
            ->get();

        return view('papers.assign-reviewers', compact('paper', 'reviewers'));
    }

    /**
     * Assign reviewers to a paper.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Conference  $conference
     * @param  \App\Models\Paper  $paper
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignReviewers(Request $request, Conference $conference, Paper $paper)
    {
        $this->authorize('assignReviewers', $paper);

        $request->validate([
            'reviewer_ids' => ['required', 'array', 'min:2', 'max:5'],
            'reviewer_ids.*' => ['required', 'exists:users,id'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        // Get reviewer models
        $reviewers = User::whereIn('id', $request->reviewer_ids)->get();

        // Create review requests
        foreach ($reviewers as $reviewer) {
            $paper->reviewRequests()->create([
                'reviewer_id' => $reviewer->id,
                'status' => 'pending',
                'message' => $request->message,
            ]);
        }

        // Update paper status
        $paper->update(['status' => 'under_review']);

        // Dispatch event
        event(new ReviewRequestedEvent($paper, $reviewers->toArray(), $request->message));

        return redirect()->back()->with('success', 'Review requests sent successfully.');
    }

    public function showCameraReadyForm(Paper $paper)
    {
        // $this->authorize('submitCameraReady', $paper);

        if (!$paper->status === Paper::STATUS_ACCEPTED) {
            return redirect()->route('papers.show', $paper)
                ->with('error', 'Only accepted papers can submit camera-ready versions.');
        }

        return view('papers.camera-ready', compact('paper'));
    }

    public function submitCameraReady(Request $request, Paper $paper)
    {
        // $this->authorize('submitCameraReady', $paper);

        $validated = $request->validate([
            'camera_ready_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'copyright_form' => 'required|file|mimes:pdf|max:2048'
        ]);

        // Store files
        $paper->update([
            'camera_ready_file' => $request->file('camera_ready_file')->store('papers/camera-ready', 'public'),
            'copyright_form_file' => $request->file('copyright_form')->store('papers/copyright-forms', 'public'),
            'camera_ready_submitted_at' => now()
        ]);

        return redirect()->route('papers.show', $paper)
            ->with('success', 'Camera-ready version submitted successfully!');
    }

    public function requestReview(Request $request, Paper $paper)
    {
        $request->validate([
            'reviewer_ids' => ['required', 'array', 'min:2', 'max:5'],
            'reviewer_ids.*' => ['required', 'exists:users,id'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        // Get reviewer models
        $reviewers = User::whereIn('id', $request->reviewer_ids)->get();

        // Create review requests
        foreach ($reviewers as $reviewer) {
            $paper->reviewRequests()->create([
                'reviewer_id' => $reviewer->id,
                'status' => 'pending',
                'message' => $request->message,
            ]);
        }

        // Update paper status
        $paper->update(['status' => 'under_review']);

        // Dispatch event
        event(new ReviewRequestedEvent($paper, $reviewers->toArray(), $request->message));

        return redirect()->back()->with('success', 'Review requests sent successfully.');
    }

    public function acceptReview(Request $request, Paper $paper)
    {
        $this->authorize('review', $paper);

        $review = $paper->reviews()->where('reviewer_id', auth()->id())->firstOrFail();

        if ($review->status !== 'requested') {
            return redirect()->back()
                ->with('error', 'This review request has already been processed.');
        }

        $review->update([
            'status' => 'accepted',
            'accepted_at' => now()
        ]);

        // If all requested reviews are accepted, update paper status
        if ($paper->reviews()->where('status', 'requested')->count() === 0) {
            $paper->update(['status' => Paper::STATUS_UNDER_REVIEW]);
        }

        return redirect()->route('reviews.show', $review)
            ->with('success', 'Review request accepted. You can now start reviewing the paper.');
    }

    public function rejectReview(Request $request, Paper $paper)
    {
        $this->authorize('review', $paper);

        $review = $paper->reviews()->where('reviewer_id', auth()->id())->firstOrFail();

        if ($review->status !== 'requested') {
            return redirect()->back()
                ->with('error', 'This review request has already been processed.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $review->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => now()
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Review request rejected successfully.');
    }
}
