<x-app-layout>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>Review Details</h3>
                        <span class="badge bg-{{ $review->isCompleted() ? 'success' : 'warning' }}">
                            {{ $review->isCompleted() ? 'Completed' : 'Pending' }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h5>Paper Title</h5>
                            <p>{{ $review->paper->title }}</p>
                        </div>

                        <div class="mb-4">
                            <h5>Conference</h5>
                            <p>
                                <a href="{{ route('conferences.show', $review->paper->conference) }}">
                                    {{ $review->paper->conference->title }} ({{ $review->paper->conference->acronym }})
                                </a>
                            </p>
                        </div>

                        @if($review->isCompleted())
                            <div class="mb-4">
                                <h5>Review Score</h5>
                                <div class="display-4 fw-bold text-primary">
                                    {{ $review->score }}/10
                                </div>
                            </div>

                            <div class="mb-4">
                                <h5>Recommendation</h5>
                                <span class="badge
                                    @if($review->recommendation === 'accept') bg-success
                                    @elseif($review->recommendation === 'reject') bg-danger
                                    @else bg-warning text-dark
                                    @endif">
                                    {{ $review->recommendation_name }}
                                </span>
                            </div>

                            <div class="mb-4">
                                <h5>Comments to Author</h5>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        {!! nl2br(e($review->comments)) !!}
                                    </div>
                                </div>
                            </div>

                            @if($review->confidential_comments && (auth()->user()->isProgramChair($review->paper->conference) || auth()->user()->role === 'admin'))
                                <div class="mb-4">
                                    <h5>Confidential Comments</h5>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            {!! nl2br(e($review->confidential_comments)) !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info">
                                This review has not been completed yet.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Review Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6>Reviewer</h6>
                            <p>{{ $review->reviewer->name }}</p>
                        </div>
                        <div class="mb-3">
                            <h6>Assigned On</h6>
                            <p>{{ $review->assigned_at ? $review->assigned_at->format('F j, Y') : 'Not assigned yet' }}</p>
                        </div>
                        @if($review->isCompleted())
                            <div class="mb-3">
                                <h6>Completed On</h6>
                                <p>{{ $review->completed_at->format('F j, Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                @can('update', $review)
                    <div class="card">
                        <div class="card-header">
                            <h5>Actions</h5>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('reviews.edit', $review) }}" class="btn btn-primary w-100 mb-2">
                                Submit Review
                            </a>
                            <a href="{{ route('papers.show', $review->paper) }}" class="btn btn-outline-secondary w-100">
                                View Paper
                            </a>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
