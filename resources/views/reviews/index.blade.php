<x-app-layout>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Papers to Review</h2>
        </div>

        <div class="card">
            <div class="card-body">
                {{-- @if($papers->isEmpty())
                    <div class="text-center py-4">
                        <h5>No papers available for review</h5>
                        <p class="text-muted">There are no submitted papers in your assigned conferences at this time.</p>
                    </div>
                @else --}}
                    <!-- Filters -->
                    <form action="{{ route('reviews.index') }}" method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <select name="review_status" class="form-select">
                                    <option value="">All Review Statuses</option>
                                    <option value="not_started" {{ request('review_status') == 'not_started' ? 'selected' : '' }}>Not Started</option>
                                    <option value="pending" {{ request('review_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ request('review_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control"
                                       placeholder="Search by paper title..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-funnel-fill me-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Paper Title</th>
                                    <th>Conference</th>
                                    <th>Author</th>
                                    <th>Submitted</th>
                                    <th>Review Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($papers as $paper)
                                <tr>
                                    <td>{{ Str::limit($paper->title, 50) }}</td>
                                    <td>{{ $paper->conference->acronym }}</td>
                                    <td>{{ $paper->author->name }}</td>
                                    <td>{{ $paper->created_at->diffForHumans() }}</td>
                                    <td>
                                        @if($paper->reviews->isEmpty())
                                            <span class="badge bg-warning text-dark">Not Started</span>
                                        @else
                                            @php
                                                $review = $paper->reviews->first();
                                            @endphp
                                            <span class="badge
                                                @if($review->status === 'completed') bg-success
                                                @else bg-warning text-dark
                                                @endif">
                                                {{ $review->status === 'completed' ? 'Completed' : 'Pending' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('papers.show', $paper) }}" class="btn btn-sm btn-info px-3 shadow-sm">
                                            <i class="bi bi-file-earmark-text me-1"></i> View Paper
                                        </a>

                                        @if($paper->reviews->isEmpty())
                                            <form action="{{ route('reviews.store') }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="paper_id" value="{{ $paper->id }}">
                                                <button type="submit" class="btn btn-sm btn-primary px-3 shadow-sm">
                                                    <i class="bi bi-play-circle me-1"></i> Start Review
                                                </button>
                                            </form>
                                        @else
                                            @php
                                                $review = $paper->reviews->first();
                                            @endphp

                                            @if($review->status === 'pending')
                                                <a href="{{ route('reviews.edit', $review) }}" class="btn btn-sm btn-success px-3 shadow-sm">
                                                    <i class="bi bi-pencil-square me-1"></i> Submit Review
                                                </a>
                                            @else
                                                <a href="{{ route('reviews.show', $review) }}" class="btn btn-sm btn-secondary px-3 shadow-sm">
                                                    <i class="bi bi-eye me-1"></i> View Review
                                                </a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No papers found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $papers->links() }}
                    </div>
                {{-- @endif --}}
            </div>
        </div>
    </div>
</x-app-layout>
