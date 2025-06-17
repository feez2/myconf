<x-app-layout>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>{{ $conference->title }} ({{ $conference->acronym }})</h3>
                        <div>
                            <!-- @if(auth()->user()->role === 'admin')
                                <a href="{{ route('conferences.submissions', $conference) }}" class="btn btn-primary me-2">
                                    <i class="bi bi-file-earmark-text"></i> Manage Submissions
                                </a>
                            @endif -->
                            @can('update', $conference)
                                <a href="{{ route('conferences.edit', $conference) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Edit Conference
                                </a>
                            @endcan
                        </div>
                        <span class="badge
                            @if($conference->status === 'upcoming') bg-info
                            @elseif($conference->status === 'ongoing') bg-success
                            @else bg-secondary
                            @endif">
                            {{ ucfirst($conference->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        @if($conference->logo)
                            <div class="text-center mb-4">
                                <img src="{{ asset('storage/' . $conference->logo) }}" alt="{{ $conference->title }} logo"
                                     class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                        @endif

                        <div class="mb-4">
                            <h5>Description</h5>
                            <p>{{ $conference->description }}</p>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Location</h5>
                                <p>{{ $conference->location }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5>Dates</h5>
                                <p>
                                    {{ $conference->start_date->format('F j, Y') }} to
                                    {{ $conference->end_date->format('F j, Y') }}
                                </p>
                            </div>
                        </div>

                        @if($conference->website)
                            <div class="mb-4">
                                <h5>Website</h5>
                                <a href="{{ $conference->website }}" target="_blank">{{ $conference->website }}</a>
                            </div>
                        @endif

                        <div class="d-flex justify-content-end">
                            @can('admin')
                                <a href="{{ route('conferences.edit', $conference) }}" class="btn btn-primary me-2">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('conferences.destroy', $conference) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
                @can('manageSettings', $conference)
                    <div class="card mt-4 ">
                        <div class="card-header">
                            <h5>Conference Administration</h5>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('conferences.chairs.index', $conference) }}" class="btn btn-outline-primary">
                                Manage Chairs
                            </a>
                        </div>
                    </div>
                @endcan
                @can('managePC', $conference)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>Program Committee</h5>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('pc-members.index', $conference) }}" class="btn btn-outline-primary">
                                Manage Program Committee
                            </a>
                        </div>
                    </div>
                @endcan
            </div>
            <div class="col-md-4">
                @auth
                    @if($conference->isAcceptingSubmissions() && auth()->user()->role === 'author')
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5>Paper Submission</h5>
                            </div>
                            <div class="card-body">
                                <a href="{{ route('papers.create', $conference) }}" class="btn btn-primary px-4 py-2 shadow-sm">
                                    <i class="bi bi-upload me-1"></i> Submit a Paper
                                </a>
                                <p class="mt-2 mb-0 text-muted">
                                    Submission deadline: {{ $conference->submission_deadline->format('F j, Y \a\t g:i a') }}
                                </p>
                            </div>
                        </div>
                    @elseif(auth()->user()->role === 'author')
                        <div class="card mt-4">
                            <div class="card-header bg-warning text-white">
                                <h5>Submission Closed</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">
                                    The submission deadline for this conference has passed.
                                    <br>
                                    <small class="text-muted">
                                        Deadline was: {{ $conference->submission_deadline->format('F j, Y \a\t g:i a') }}
                                    </small>
                                </p>
                            </div>
                        </div>
                    @endif
                @endauth
                @can('admin')
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Quick Stats</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Papers Submitted</span>
                                <span class="badge bg-primary rounded-pill">{{ $conference->papers->count() }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Accepted Papers</span>
                                <span class="badge bg-success rounded-pill">
                                    {{ $conference->papers->where('status', 'accepted')->count() }}
                                </span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Pending Reviews</span>
                                <span class="badge bg-warning rounded-pill">
                                    {{ $conference->papers->where('status', 'pending')->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5>Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <a href="{{ route('conferences.submissions', $conference) }}" class="btn btn-primary text-white rounded-pill shadow-sm">
                                <i class="bi bi-folder-check me-1"></i> Submissions
                            </a>
                            <a href="{{ route('conferences.track-invitations', $conference) }}" class="btn btn-info text-white rounded-pill shadow-sm">
                                <i class="bi bi-person-lines-fill me-1"></i> Invitations
                            </a>
                            <a href="{{ route('reports.index', $conference) }}" class="btn btn-success text-white rounded-pill shadow-sm">
                                <i class="bi bi-bar-chart-line me-1"></i> Reports
                            </a>
                        </div>
                    </div>
                </div>
                @endcan
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send Invitations</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
