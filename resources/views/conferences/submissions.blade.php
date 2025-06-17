<x-app-layout>
    <div class="container py-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3>Submissions - {{ $conference->title }}</h3>
                <a href="{{ route('conferences.show', $conference) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Conference
                </a>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form action="{{ route('conferences.submissions', $conference) }}" method="GET" class="mb-4">
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
                                   placeholder="Search by title or author..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100 shadow-sm">
                                <i class="bi bi-funnel-fill me-1"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Submissions Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Authors</th>
                                <th>Submitted By</th>
                                <th>Status</th>
                                <th>Submission Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($papers as $paper)
                                <tr>
                                    <td>{{ $paper->title }}</td>
                                    <td>
                                        @foreach($paper->authors as $author)
                                            <span class="badge bg-info me-1">{{ $author->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $paper->user->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $paper->status === 'submitted' ? 'primary' :
                                            ($paper->status === 'under_review' ? 'warning' :
                                            ($paper->status === 'accepted' ? 'success' :
                                            ($paper->status === 'rejected' ? 'danger' : 'secondary'))) }}">
                                            {{ ucfirst(str_replace('_', ' ', $paper->status)) }}
                                        </span>
                                    </td>
                                    <td>{{ $paper->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('papers.show', $paper) }}"
                                               class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                            @if($paper->status === 'submitted' && auth()->user()->can('assignReviewers', $paper))
                                                {{-- <button type="button"
                                                        class="btn btn-sm btn-success"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#inviteReviewersModal{{ $paper->id }}">
                                                    <i class="bi bi-person-plus"></i> Assign Reviewers
                                                </button> --}}
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No submissions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $papers->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Invite Reviewers Modal -->
    @foreach($papers as $paper)
        @if($paper->status === 'submitted' && auth()->user()->can('assignReviewers', $paper))
            <div class="modal fade" id="inviteReviewersModal{{ $paper->id }}"
                 tabindex="-1" aria-labelledby="inviteReviewersModalLabel{{ $paper->id }}"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="inviteReviewersModalLabel{{ $paper->id }}">
                                Invite Reviewers for: {{ $paper->title }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('conferences.invite-reviewers', $conference) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="reviewer_ids{{ $paper->id }}" class="form-label">Select Reviewers</label>
                                    <select class="form-select" name="reviewer_ids[]" id="reviewer_ids{{ $paper->id }}" multiple required>
                                        @foreach($conference->programCommittees()->where('status', 'accepted')->with('user')->get() as $pc)
                                            <option value="{{ $pc->user->id }}">
                                                {{ $pc->user->name }} ({{ $pc->user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Hold CTRL to select multiple reviewers</small>
                                </div>
                                <div class="mb-3">
                                    <label for="message{{ $paper->id }}" class="form-label">Invitation Message (Optional)</label>
                                    <textarea class="form-control" name="message" id="message{{ $paper->id }}" rows="3"
                                            placeholder="Enter a personal message for the invitation"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Send Invitations</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</x-app-layout>
