<x-app-layout>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Review and Decisions: {{ $conference->title }}</h2>
                    <div style="display:flex;">
                        <a href="{{ route('reports.download.reviews', $conference) }}" class="btn btn-success">
                            <i class="bi bi-download"></i> Download as PDF
                        </a>
                        <a href="{{ route('reports.index', $conference) }}" class="btn btn-secondary ms-2">
                            Back to Reports
                        </a>
                    </div>
                </div>

                <!-- Review Statistics -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Review Statistics</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="text-primary">{{ $reviewStats['total_reviews'] }}</h5>
                                        <p class="text-muted mb-0">Total Reviews</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="text-success">{{ number_format($reviewStats['average_reviews_per_paper'], 1) }}</h5>
                                        <p class="text-muted mb-0">Average Reviews per Paper</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="text-info">{{ number_format($reviewStats['review_completion_rate'], 1) }}%</h5>
                                        <p class="text-muted mb-0">Review Completion Rate</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Papers and Reviews -->
                <div class="card">
                    <div class="card-header">
                        <h4>Papers and Reviews</h4>
                    </div>
                    <div class="card-body">
                        @if($papers->isEmpty())
                            <p class="text-muted">No papers have been submitted yet.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Paper Title</th>
                                            <th>Authors</th>
                                            <th>Status</th>
                                            <th>Reviews</th>
                                            <th>Decision</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($papers as $paper)
                                            <tr>
                                                <td>{{ $paper->title }}</td>
                                                <td>
                                                    {{-- @foreach($paper->authors as $author)
                                                        <div>{{ $author->name }}</div>
                                                    @endforeach --}}
                                                    {{ $paper->user->name }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $paper->status === 'submitted' ? 'primary' :
                                                        ($paper->status === 'under_review' ? 'warning' :
                                                        ($paper->status === 'accepted' ? 'success' :
                                                        ($paper->status === 'rejected' ? 'danger' : 'secondary'))) }}">
                                                        {{ ucfirst(str_replace('_', ' ', $paper->status)) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($paper->reviews->isEmpty())
                                                        <span class="text-muted">No reviews yet</span>
                                                    @else
                                                        <div class="small">
                                                            @foreach($paper->reviews as $review)
                                                                <div class="mb-2 border-bottom pb-2">
                                                                    <div class="fw-bold">{{ $review->reviewer->name }}</div>
                                                                    <div class="mb-1">
                                                                        <span class="badge bg-primary">Score: {{ $review->score }}/5</span>
                                                                        @if($review->recommendation)
                                                                            <span class="badge bg-info ms-1">{{ ucfirst(str_replace('_', ' ', $review->recommendation)) }}</span>
                                                                        @endif
                                                                    </div>
                                                                    @if($review->comments)
                                                                        <div class="text-muted">{{ $review->comments }}</div>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($paper->status === 'accepted' || $paper->status === 'rejected' || $paper->status === 'revision_required')
                                                        <div class="mb-2">
                                                            <span class="badge bg-{{ $paper->status === 'accepted' ? 'success' : 
                                                                ($paper->status === 'rejected' ? 'danger' : 'warning') }}">
                                                                {{ ucfirst(str_replace('_', ' ', $paper->status)) }}
                                                            </span>
                                                        </div>
                                                        @if($paper->decision_notes)
                                                            <div class="card bg-light mb-2">
                                                                <div class="card-body p-2">
                                                                    {!! nl2br(e($paper->decision_notes)) !!}
                                                                </div>
                                                            </div>
                                                        @endif
                                                        @if($paper->decision_made_at)
                                                            <div class="text-muted small">
                                                                Decided on {{ $paper->decision_made_at->format('F j, Y') }}
                                                            </div>
                                                        @endif
                                                        @if($paper->camera_ready_deadline)
                                                            <div class="text-muted small mt-1">
                                                                Camera Ready Deadline: {{ $paper->camera_ready_deadline->format('F j, Y') }}
                                                            </div>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">No decision yet</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
