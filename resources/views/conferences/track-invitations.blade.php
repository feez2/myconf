<x-app-layout>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Track Invitations - {{ $conference->title }}</h2>
            <div style="display:flex;">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inviteReviewersModal">
                    <i class="bi bi-person-plus"></i> Invite Reviewers
                </button>
                <a href="{{ route('conferences.show', $conference) }}" class="btn btn-secondary ms-2">
                    <i class="bi bi-arrow-left"></i> Back to Conference
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <!-- Filters -->
                <form action="{{ route('conferences.track-invitations', $conference) }}" method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control"
                                   placeholder="Search by name or email..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100 shadow-sm">
                                <i class="bi bi-funnel-fill me-1"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Invitations Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Invited At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invitations as $invitation)
                                <tr>
                                    <td>{{ $invitation->user->name }}</td>
                                    <td>{{ $invitation->user->email }}</td>
                                    <td>{{ ucfirst($invitation->role) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $invitation->status === 'pending' ? 'warning' :
                                            ($invitation->status === 'accepted' ? 'success' : 'danger') }}">
                                            {{ ucfirst($invitation->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $invitation->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        @if($invitation->status === 'pending')
                                            <form action="{{ route('conferences.resend-invitation', [$conference, $invitation]) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning text-white shadow-sm px-3 d-inline-flex align-items-center">
                                                    <i class="bi bi-envelope me-1"></i> Resend
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No invitations found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $invitations->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Invite Reviewers Modal -->
    <div class="modal fade" id="inviteReviewersModal" tabindex="-1" aria-labelledby="inviteReviewersModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inviteReviewersModalLabel">Invite Reviewers</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('conferences.invite-reviewers', $conference) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="reviewer_ids" class="form-label">Select Reviewers</label>
                            <select class="form-select" name="reviewer_ids[]" id="reviewer_ids" multiple>
                                @foreach($reviewers as $reviewer)
                                    <option value="{{ $reviewer->id }}">
                                        {{ $reviewer->name }} ({{ $reviewer->email }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hold CTRL to select multiple reviewers</small>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Invitation Message (Optional)</label>
                            <textarea class="form-control" id="message" name="message" rows="3"
                                    placeholder="Enter a personal message for the invitation"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light border px-4 shadow-sm" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary text-white px-4 shadow-sm">
                            <i class="bi bi-send me-1"></i> Send Invitations
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
