<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Conference Details - {{ $conference->title }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; }
        .conference-title { font-size: 24px; font-weight: bold; }
        .section-title { font-size: 20px; margin: 30px 0 15px; border-bottom: 1px solid #333; padding-bottom: 5px; }
        .info-row { margin-bottom: 10px; }
        .info-label { font-weight: bold; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f5f5f5; }
        .badge { padding: 3px 6px; border-radius: 3px; font-size: 12px; }
        .badge-primary { background-color: #007bff; color: white; }
        .badge-warning { background-color: #ffc107; color: black; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-secondary { background-color: #6c757d; color: white; }
        .stat-card { border: 1px solid #ddd; border-radius: 5px; padding: 15px; margin-bottom: 20px; }
        .stat-value { font-size: 20px; font-weight: bold; }
        .stat-label { font-size: 14px; color: #666; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="header">
        <div class="conference-title">{{ $conference->title }} ({{ $conference->acronym }})</div>
        <h1>Conference Details Report</h1>
        <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>

    <!-- Basic Information -->
    <div class="section-title">Basic Information</div>
    <div class="info-row">
        <span class="info-label">Title:</span> {{ $details['basic_info']['title'] }}
    </div>
    <div class="info-row">
        <span class="info-label">Acronym:</span> {{ $details['basic_info']['acronym'] }}
    </div>
    <div class="info-row">
        <span class="info-label">Venue:</span> {{ $details['basic_info']['venue'] }}
    </div>
    <div class="info-row">
        <span class="info-label">Website:</span> {{ $details['basic_info']['website'] ?? 'Not specified' }}
    </div>
    <div class="info-row">
        <span class="info-label">Start Date:</span> {{ $details['basic_info']['start_date']->format('F j, Y') }}
    </div>
    <div class="info-row">
        <span class="info-label">End Date:</span> {{ $details['basic_info']['end_date']->format('F j, Y') }}
    </div>
    <div class="info-row">
        <span class="info-label">Description:</span><br>
        {{ $details['basic_info']['description'] }}
    </div>

    <div class="page-break"></div>

    <!-- Committees -->
    <div class="section-title">Committees</div>

    <!-- Authors -->
    <h3>Authors</h3>
    @php
        $papers = $conference->papers()
            ->with(['user', 'authors'])
            ->get();
        $authors = $papers->map(function($paper) {
            return [
                'name' => $paper->user->name,
                'email' => $paper->user->email,
                'affiliation' => $paper->user->affiliation,
                'paper_title' => $paper->title,
                'paper_status' => $paper->status,
                'submitted_at' => $paper->created_at
            ];
        })->unique('name')->sortBy('name');
    @endphp
    @if($authors->isEmpty())
        <p>No authors have submitted papers yet.</p>
    @else
        <table class="table">
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
                            <span class="badge badge-{{ $author['paper_status'] === 'submitted' ? 'primary' :
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
    @endif

    <!-- Reviewers -->
    <h3>Reviewers</h3>
    @if($details['committees']['reviewers']->isEmpty())
        <p>No reviewers assigned.</p>
    @else
        <table class="table">
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
    @endif

    <div class="page-break"></div>

    <!-- Important Dates -->
    <div class="section-title">Important Dates</div>
    <div class="info-row">
        <span class="info-label">Submission Deadline:</span>
        {{ $details['important_dates']['submission_deadline'] ? $details['important_dates']['submission_deadline']->format('F j, Y') : 'Not specified' }}
    </div>
    <div class="info-row">
        <span class="info-label">Review Deadline:</span>
        {{ $details['important_dates']['review_deadline'] ? $details['important_dates']['review_deadline']->format('F j, Y') : 'Not specified' }}
    </div>
    <div class="info-row">
        <span class="info-label">Notification Date:</span>
        {{ $details['important_dates']['notification_date'] ? $details['important_dates']['notification_date']->format('F j, Y') : 'Not specified' }}
    </div>
    <div class="info-row">
        <span class="info-label">Camera Ready Deadline:</span>
        {{ $details['important_dates']['camera_ready_deadline'] ? $details['important_dates']['camera_ready_deadline']->format('F j, Y') : 'Not specified' }}
    </div>

    <!-- Statistics -->
    <div class="section-title">Statistics</div>
    <div style="display: flex; flex-wrap: wrap; gap: 15px;">
        <div class="stat-card" style="flex: 1; min-width: 200px;">
            <div class="stat-value">{{ $details['statistics']['total_submissions'] }}</div>
            <div class="stat-label">Total Submissions</div>
        </div>
        <div class="stat-card" style="flex: 1; min-width: 200px;">
            <div class="stat-value">{{ $details['statistics']['accepted_papers'] }}</div>
            <div class="stat-label">Accepted Papers</div>
        </div>
        <div class="stat-card" style="flex: 1; min-width: 200px;">
            <div class="stat-value">{{ $details['statistics']['rejected_papers'] }}</div>
            <div class="stat-label">Rejected Papers</div>
        </div>
        <div class="stat-card" style="flex: 1; min-width: 200px;">
            <div class="stat-value">{{ $details['statistics']['total_reviewers'] }}</div>
            <div class="stat-label">Total Reviewers</div>
        </div>
    </div>
</body>
</html> 