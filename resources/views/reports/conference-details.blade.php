<x-app-layout>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Conference Details: {{ $conference->title }}</h2>
                    <div style="display:flex;">
                        <a href="{{ route('reports.download.conference-details', $conference) }}" class="btn btn-success">
                            <i class="bi bi-download"></i> Download as PDF
                        </a>
                        <a href="{{ route('reports.index', $conference) }}" class="btn btn-secondary ms-2">
                            Back to Reports
                        </a>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Basic Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Title:</strong> {{ $details['basic_info']['title'] }}</p>
                                <p><strong>Acronym:</strong> {{ $details['basic_info']['acronym'] }}</p>
                                <p><strong>Venue:</strong> {{ $details['basic_info']['venue'] }}</p>
                                <p><strong>Website:</strong>
                                    @if($details['basic_info']['website'])
                                        <a href="{{ $details['basic_info']['website'] }}" target="_blank">{{ $details['basic_info']['website'] }}</a>
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Start Date:</strong> {{ $details['basic_info']['start_date']->format('F j, Y') }}</p>
                                <p><strong>End Date:</strong> {{ $details['basic_info']['end_date']->format('F j, Y') }}</p>
                                <p><strong>Description:</strong></p>
                                <p class="text-muted">{{ $details['basic_info']['description'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Committees -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Committees</h4>
                    </div>
                    <div class="card-body">
                        <!-- Authors -->
                        <div class="mb-4">
                            <h5>Authors</h5>
                            @php
                                $papers = $conference->papers()
                                    ->with(['user', 'authors'])
                                    ->get();
                                $authors = $papers->map(function($paper) {
                                    return [
                                        'name' => $paper->user->name,
                                        'email' => $paper->user->email,
                                        'affiliation' => $paper->user->affiliation,
                                        'user_id' => $paper->user->id,
                                        'paper_title' => $paper->title,
                                        'paper_status' => $paper->status,
                                        'submitted_at' => $paper->created_at
                                    ];
                                })->unique('user_id')->sortBy('name');
                            @endphp
                            @if($authors->isEmpty())
                                <p class="text-muted">No authors have submitted papers yet.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Affiliation</th>
                                                <th>Paper Title</th>
                                                <th>Status</th>
                                                <th>Submitted Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($authors as $author)
                                                <tr>
                                                    <td>{{ $author['name'] }}</td>
                                                    <td>{{ $author['email'] }}</td>
                                                    <td>{{ $author['affiliation'] ?? 'Not specified' }}</td>
                                                    <td>{{ $author['paper_title'] }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $author['paper_status'] === 'submitted' ? 'primary' :
                                                            ($author['paper_status'] === 'under_review' ? 'warning' :
                                                            ($author['paper_status'] === 'accepted' ? 'success' :
                                                            ($author['paper_status'] === 'rejected' ? 'danger' : 'secondary'))) }}">
                                                            {{ ucfirst(str_replace('_', ' ', $author['paper_status'])) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($author['submitted_at'])->format('M d, Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <!-- Reviewers -->
                        <div>
                            <h5>Reviewers</h5>
                            @if($details['committees']['reviewers']->isEmpty())
                                <p class="text-muted">No reviewers assigned.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Affiliation</th>
                                                <th>Papers Reviewed</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($details['committees']['reviewers'] as $member)
                                                <tr>
                                                    <td>{{ $member->user->name }}</td>
                                                    <td>{{ $member->user->email }}</td>
                                                    <td>{{ $member->user->affiliation ?? 'Not specified' }}</td>
                                                    <td>
                                                        {{ $conference->papers()
                                                            ->whereHas('reviews', function($query) use ($member) {
                                                                $query->where('reviewer_id', $member->user_id);
                                                            })
                                                            ->count() }}
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

                <!-- Important Dates -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Important Dates</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Submission Deadline:</strong>
                                    @if($details['important_dates']['submission_deadline'])
                                        {{ $details['important_dates']['submission_deadline']->format('F j, Y') }}
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </p>
                                <p><strong>Review Deadline:</strong>
                                    @if($details['important_dates']['review_deadline'])
                                        {{ $details['important_dates']['review_deadline']->format('F j, Y') }}
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Notification Date:</strong>
                                    @if($details['important_dates']['notification_date'])
                                        {{ $details['important_dates']['notification_date']->format('F j, Y') }}
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </p>
                                <p><strong>Camera Ready Deadline:</strong>
                                    @if($details['important_dates']['camera_ready_deadline'])
                                        {{ $details['important_dates']['camera_ready_deadline']->format('F j, Y') }}
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h4>Statistics</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="text-primary">{{ $details['statistics']['total_submissions'] }}</h5>
                                        <p class="text-muted mb-0">Total Submissions</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="text-success">{{ $details['statistics']['accepted_papers'] }}</h5>
                                        <p class="text-muted mb-0">Accepted Papers</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="text-danger">{{ $details['statistics']['rejected_papers'] }}</h5>
                                        <p class="text-muted mb-0">Rejected Papers</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="text-info">{{ $details['statistics']['total_reviewers'] }}</h5>
                                        <p class="text-muted mb-0">Total Reviewers</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
