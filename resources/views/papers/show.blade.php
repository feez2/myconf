<x-app-layout>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>{{ $paper->title }}</h3>
                        <span class="badge
                            @if($paper->status === 'submitted') bg-primary
                            @elseif($paper->status === 'under_review') bg-warning text-dark
                            @elseif($paper->status === 'accepted') bg-success
                            @else bg-danger
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $paper->status)) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h5>Conference</h5>
                            <p>
                                <a href="{{ route('conferences.show', $paper->conference) }}">
                                    {{ $paper->conference->title }} ({{ $paper->conference->acronym }})
                                </a>
                            </p>
                        </div>

                        <div class="mb-4">
                            <h5>Abstract</h5>
                            <p>{{ $paper->abstract }}</p>
                        </div>

                        <div class="mb-4">
                            <h5>Keywords</h5>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(explode(',', $paper->keywords) as $keyword)
                                    <span class="badge bg-light text-dark">{{ trim($keyword) }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Paper File</h5>
                            <a href="{{ route('papers.download', $paper) }}" class="btn btn-outline-primary" target="_blank">
                                Download Paper
                            </a>
                            @if($paper->revision_submitted_at)
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle"></i> This is a revised version submitted on {{ $paper->revision_submitted_at->format('F j, Y') }}
                                    </small>
                                </div>
                            @endif
                        </div>

                        @if($paper->revision_summary)
                            <div class="mb-4">
                                <h5>Revision Summary</h5>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        {!! nl2br(e($paper->revision_summary)) !!}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($paper->status === 'accepted' || $paper->status === 'rejected')
                            <div class="mb-4">
                                <h5>Decision Comments</h5>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        {{ $paper->decision_comments ?? 'No comments provided.' }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($paper->reviews->isNotEmpty())
                            <div class="mb-4">
                                <h5>Reviews</h5>
                                @foreach($paper->reviews as $review)
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            Reviewer #{{ $loop->iteration }}
                                            <span class="float-end">Score: {{ $review->score }}/10</span>
                                        </div>
                                        <div class="card-body">
                                            <h6>Comments:</h6>
                                            <p>{{ $review->comments }}</p>
                                            <h6>Recommendation:</h6>
                                            <p>{{ $review->recommendation_name }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="d-flex justify-content-end">
                            @can('update', $paper)
                                @if($paper->status === \App\Models\Paper::STATUS_SUBMITTED && isset($paper->conference->submission_deadline) && now()->lte($paper->conference->submission_deadline))
                                    <a href="{{ route('papers.edit', $paper) }}" class="btn btn-primary me-2">
                                        Edit Paper
                                    </a>
                                @endif
                                @if($paper->status === \App\Models\Paper::STATUS_REVISION_REQUIRED)
                                    <a href="{{ route('papers.revision', $paper) }}" class="btn btn-warning me-2">
                                        Submit Revision
                                    </a>
                                @endif
                                @if($paper->status === \App\Models\Paper::STATUS_ACCEPTED)
                                    @if(!$paper->camera_ready_file)
                                        <a href="{{ route('papers.showCameraReadyForm', $paper) }}" class="btn btn-success me-2">
                                            Submit Camera Ready
                                        </a>
                                    @else
                                        <span class="badge bg-success me-2">Camera Ready Submitted</span>
                                    @endif
                                @endif
                            @endcan
                            @can('delete', $paper)
                                <form action="{{ route('papers.destroy', $paper) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                                        Delete Paper
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Submission Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6>Submitted By</h6>
                            <p>{{ $paper->user?->name ?? 'N/A' }}</p>
                        </div>
                        <div class="mb-3">
                            <h6>Submission Date</h6>
                            <p>{{ $paper->created_at->format('F j, Y \a\t g:i a') }}</p>
                        </div>
                        <div class="mb-3">
                            <h6>Last Updated</h6>
                            <p>{{ $paper->updated_at->format('F j, Y \a\t g:i a') }}</p>
                        </div>
                    </div>
                </div>

                @if($paper->average_score)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Review Summary</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="display-4 fw-bold text-primary mb-2">
                                {{ number_format($paper->average_score, 1) }}/10
                            </div>
                            <div class="text-muted">
                                Average Review Score
                            </div>
                        </div>
                    </div>
                @endif
                <span class="badge
                    @if($paper->status === \App\Models\Paper::STATUS_SUBMITTED) bg-primary
                    @elseif($paper->status === \App\Models\Paper::STATUS_UNDER_REVIEW) bg-warning text-dark
                    @elseif($paper->status === \App\Models\Paper::STATUS_REVISION_REQUIRED) bg-info text-dark
                    @elseif($paper->status === \App\Models\Paper::STATUS_ACCEPTED) bg-success
                    @elseif($paper->status === \App\Models\Paper::STATUS_REJECTED) bg-danger
                    @else bg-secondary
                    @endif">
                    {{ \App\Models\Paper::statusOptions()[$paper->status] }}
                </span>
            </div>
            @if($paper->decision_made_at)
            <div class="card mb-4">
                <div class="card-header bg-{{ $paper->status === \App\Models\Paper::STATUS_ACCEPTED ? 'success' : 'info' }} text-white">
                    <h5>Final Decision</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Decision</h6>
                        <p>{{ \App\Models\Paper::statusOptions()[$paper->status] }}</p>
                    </div>
                    <div class="mb-3">
                        <h6>Decision Notes</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                {!! nl2br(e($paper->decision_notes)) !!}
                            </div>
                        </div>
                    </div>
                    @if($paper->camera_ready_deadline)
                    <div class="mb-3">
                        <h6>Camera Ready Deadline</h6>
                        <p>{{ $paper->camera_ready_deadline->format('F j, Y') }}</p>
                    </div>
                    @endif
                    <div class="mb-3">
                        <h6>Decided By</h6>
                        <p>{{ $paper->decisionMaker->name }} on {{ $paper->decision_made_at->format('F j, Y') }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
