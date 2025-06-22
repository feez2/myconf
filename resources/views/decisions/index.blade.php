@php
use App\Models\Paper;
@endphp

<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Paper Decisions - {{ $conference->title }}</h4>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('decisions.select-conference') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Change Conference
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        {{-- @if($papers->isEmpty())
                            <div class="alert alert-info">
                                No papers found with completed reviews.
                            </div>
                        @else --}}
                            <!-- Filters -->
                            <form action="{{ route('decisions.index', $conference) }}" method="GET" class="mb-4">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <select name="status" class="form-select">
                                            <option value="">All Statuses</option>
                                            @foreach(\App\Models\Paper::statusOptions() as $value => $label)
                                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="search" class="form-control"
                                               placeholder="Search by paper title..." value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100 shadow-sm">
                                            <i class="bi bi-funnel-fill me-1"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </form>

                            @if($papers->isEmpty())
                                <div class="alert alert-info d-flex align-items-center justify-content-center gap-2 mt-4" role="alert">
                                    <i class="bi bi-info-circle-fill fs-4 text-info"></i>
                                    <div>
                                        <strong>No papers found.</strong>
                                    </div>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Author</th>
                                                <th>Reviews</th>
                                                <th>Average Score</th>
                                                <th>Status</th>
                                                <th>Decision Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($papers as $paper)
                                                <tr>
                                                    <td>{{ $paper->title }}</td>
                                                    <td>{{ $paper->author->name }}</td>
                                                    <td>
                                                        {{ $paper->reviews->where('status', 'completed')->count() }} /
                                                        {{ $paper->reviews->count() }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $completedReviews = $paper->reviews->where('status', 'completed');
                                                            $avgScore = $completedReviews->avg('score');
                                                        @endphp
                                                        {{ $avgScore ? number_format($avgScore, 1) : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $paper->status_color }}">
                                                            {{ $paper->status }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($paper->decision_made_at)
                                                            <span class="text-muted">
                                                                {{ $paper->decision_made_at->format('M d, Y') }}
                                                            </span>
                                                        @else
                                                            <span class="badge bg-warning">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!in_array($paper->status, [Paper::STATUS_ACCEPTED, Paper::STATUS_REJECTED]))
                                                            <a href="{{ route('decisions.create', $paper) }}"
                                                            class="btn btn-sm btn-success shadow-sm">
                                                                <i class="bi bi-check2-square me-1"></i> Make Decision
                                                            </a>
                                                        @else
                                                            <a href="{{ route('decisions.show', $paper) }}"
                                                            class="btn btn-sm btn-secondary shadow-sm">
                                                                <i class="bi bi-eye-fill me-1"></i> View Decision
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            {{-- @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">No papers found</td>
                                                </tr> --}}
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $papers->links('pagination::bootstrap-5') }}
                                </div>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
