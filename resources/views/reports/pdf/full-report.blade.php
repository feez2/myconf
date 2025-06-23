<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Full Report - {{ $conference->title }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
        .conference-title { font-size: 24px; font-weight: bold; color: #1e88e5; }
        .section-title { font-size: 18px; margin: 30px 0 15px; border-bottom: 1px solid #333; padding-bottom: 5px; color: #333; }
        .subsection-title { font-size: 16px; margin: 20px 0 10px; color: #555; }
        .stat-card { border: 1px solid #ddd; border-radius: 5px; padding: 15px; margin-bottom: 20px; background-color: #f8f9fa; }
        .stat-value { font-size: 20px; font-weight: bold; }
        .stat-label { font-size: 14px; color: #666; }
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
        .text-primary { color: #007bff; }
        .text-warning { color: #ffc107; }
        .text-info { color: #17a2b8; }
        .paper { margin-bottom: 15px; page-break-inside: avoid; padding: 10px; border-left: 3px solid #007bff; background-color: #f8f9fa; }
        .paper-title { font-weight: bold; color: #333; }
        .paper-authors { font-style: italic; color: #666; margin: 5px 0; }
        .badge { padding: 3px 8px; border-radius: 3px; font-size: 12px; font-weight: bold; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: black; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-info { background-color: #17a2b8; color: white; }
        .distribution-item { display: flex; justify-content: space-between; margin-bottom: 5px; padding: 5px; background-color: #f8f9fa; }
        .distribution-score { font-weight: bold; }
        .page-break { page-break-after: always; }
        .chart-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .chart-table th, .chart-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .chart-table th { background-color: #f2f2f2; font-weight: bold; }
        .chart-table tr:nth-child(even) { background-color: #f9f9f9; }
        .committee-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .committee-table th, .committee-table td { border: 1px solid #ddd; padding: 6px; text-align: left; font-size: 12px; }
        .committee-table th { background-color: #f2f2f2; font-weight: bold; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 15px 0; }
        .info-section { background-color: #f8f9fa; padding: 15px; border-radius: 5px; }
        .info-section h4 { margin-top: 0; color: #333; }
        .progress-bar { width: 100%; background-color: #e9ecef; border-radius: 3px; margin: 5px 0; }
        .progress-fill { height: 20px; background-color: #007bff; border-radius: 3px; text-align: center; color: white; line-height: 20px; font-size: 12px; }
        
        /* Chart Styles */
        .chart-container { margin: 20px 0; }
        .pie-chart { display: flex; justify-content: space-around; align-items: center; margin: 20px 0; }
        .pie-segment { width: 120px; height: 120px; border-radius: 50%; position: relative; margin: 10px; }
        .pie-label { text-align: center; margin-top: 10px; font-size: 12px; font-weight: bold; }
        .pie-value { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-weight: bold; font-size: 14px; }
        
        .bar-chart { margin: 20px 0; }
        .bar-item { display: flex; align-items: center; margin: 10px 0; }
        .bar-label { width: 150px; font-size: 12px; }
        .bar-container { flex: 1; height: 30px; background-color: #e9ecef; border-radius: 3px; margin: 0 10px; position: relative; }
        .bar-fill { height: 100%; border-radius: 3px; position: relative; }
        .bar-value { position: absolute; right: 5px; top: 50%; transform: translateY(-50%); color: white; font-weight: bold; font-size: 12px; }
        
        .line-chart { margin: 20px 0; }
        .line-point { display: inline-block; width: 60px; text-align: center; margin: 0 5px; }
        .line-bar { background-color: #007bff; margin: 0 auto; border-radius: 3px 3px 0 0; }
        .line-label { font-size: 10px; margin-top: 5px; }
        
        .chart-legend { display: flex; flex-wrap: wrap; justify-content: center; margin: 15px 0; }
        .legend-item { display: flex; align-items: center; margin: 5px 10px; font-size: 12px; }
        .legend-color { width: 15px; height: 15px; margin-right: 5px; border-radius: 2px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="conference-title">{{ $conference->title }} ({{ $conference->acronym }})</div>
        <h1>Comprehensive Conference Report</h1>
        <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>

    <!-- Conference Details Section -->
    <div class="section-title">Conference Details</div>
    
    <div class="info-grid">
        <div class="info-section">
            <h4>Basic Information</h4>
            <p><strong>Title:</strong> {{ $details['basic_info']['title'] }}</p>
            <p><strong>Acronym:</strong> {{ $details['basic_info']['acronym'] }}</p>
            <p><strong>Start Date:</strong> {{ $details['basic_info']['start_date'] ? $details['basic_info']['start_date']->format('F j, Y') : 'Not set' }}</p>
            <p><strong>End Date:</strong> {{ $details['basic_info']['end_date'] ? $details['basic_info']['end_date']->format('F j, Y') : 'Not set' }}</p>
            <p><strong>Venue:</strong> {{ $details['basic_info']['venue'] ?? 'Not specified' }}</p>
            @if($details['basic_info']['website'])
                <p><strong>Website:</strong> {{ $details['basic_info']['website'] }}</p>
            @endif
        </div>
        
        <div class="info-section">
            <h4>Important Dates</h4>
            <p><strong>Submission Deadline:</strong> {{ $details['important_dates']['submission_deadline'] ? $details['important_dates']['submission_deadline']->format('F j, Y') : 'Not set' }}</p>
            <p><strong>Review Deadline:</strong> {{ $details['important_dates']['review_deadline'] ? $details['important_dates']['review_deadline']->format('F j, Y') : 'Not set' }}</p>
            <p><strong>Notification Date:</strong> {{ $details['important_dates']['notification_date'] ? $details['important_dates']['notification_date']->format('F j, Y') : 'Not set' }}</p>
            <p><strong>Camera Ready Deadline:</strong> {{ $details['important_dates']['camera_ready_deadline'] ? $details['important_dates']['camera_ready_deadline']->format('F j, Y') : 'Not set' }}</p>
        </div>
    </div>

    @if($details['basic_info']['description'])
        <div class="info-section">
            <h4>Description</h4>
            <p>{{ $details['basic_info']['description'] }}</p>
        </div>
    @endif

    <!-- Committees Section -->
    <div class="section-title">Committees</div>

    <!-- Authors -->
    <h3>Authors</h3>
    @php
        $authorPapers = $conference->papers()->with(['user', 'authors'])->get();
        $authors = $authorPapers->map(function($paper) {
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
        <table class="committee-table">
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
        <table class="committee-table">
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

    <!-- Analytics Section -->
    <div class="section-title">Analytics Dashboard</div>

    <!-- Chart 1: Submissions by Status -->
    <div class="subsection-title">Submissions by Status</div>
    @if(array_sum($chartData) > 0)
        <!-- Visual Pie Chart -->
        <div class="chart-container">
            <div class="pie-chart">
                @php
                    $colors = ['#007bff', '#ffc107', '#17a2b8', '#28a745', '#dc3545', '#6c757d'];
                    $colorIndex = 0;
                @endphp
                @foreach($chartData as $status => $count)
                    @if($count > 0)
                        @php
                            $percentage = array_sum($chartData) > 0 ? ($count / array_sum($chartData)) * 100 : 0;
                            $color = $colors[$colorIndex % count($colors)];
                            $colorIndex++;
                        @endphp
                        <div style="text-align: center;">
                            <div class="pie-segment" style="background-color: {{ $color }}; width: {{ max(30, $percentage * 2) }}px; height: {{ max(30, $percentage * 2) }}px;">
                                <div class="pie-value">{{ $count }}</div>
                            </div>
                            <div class="pie-label">{{ $status }}<br>({{ round($percentage, 1) }}%)</div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        
        <!-- Data Table -->
        <table class="chart-table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($chartData as $status => $count)
                    @if($count > 0)
                        <tr>
                            <td>{{ $status }}</td>
                            <td>{{ $count }}</td>
                            <td>{{ array_sum($chartData) > 0 ? round(($count / array_sum($chartData)) * 100, 1) : 0 }}%</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else
        <p>No submission data available.</p>
    @endif

    <!-- Chart 2: Submission Count Over Time -->
    <div class="subsection-title">Submission Count Over Time</div>
    @if(!empty($submissionsOverTime))
        <!-- Visual Line Chart -->
        <div class="chart-container">
            <div class="line-chart">
                @php
                    $maxCount = max($submissionsOverTime);
                    $maxHeight = 100;
                @endphp
                @foreach($submissionsOverTime as $month => $count)
                    @php
                        $height = $maxCount > 0 ? ($count / $maxCount) * $maxHeight : 0;
                    @endphp
                    <div class="line-point">
                        <div class="line-bar" style="height: {{ $height }}px; background-color: #007bff;"></div>
                        <div class="line-label">{{ $month }}</div>
                        <div style="font-size: 10px; font-weight: bold;">{{ $count }}</div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Data Table -->
        <table class="chart-table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Submissions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($submissionsOverTime as $month => $count)
                    <tr>
                        <td>{{ $month }}</td>
                        <td>{{ $count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No submission timeline data available.</p>
    @endif

    <!-- Chart 3: Review Completion Progress -->
    <div class="subsection-title">Review Completion Progress</div>
    @if(!empty($reviewCompletionData))
        <!-- Visual Bar Chart -->
        <div class="chart-container">
            <div class="bar-chart">
                @foreach($reviewCompletionData as $data)
                    <div class="bar-item">
                        <div class="bar-label">{{ $data['reviewer'] }}</div>
                        <div class="bar-container">
                            <div class="bar-fill" style="width: {{ $data['percentage'] }}%; background-color: #28a745;">
                                <div class="bar-value">{{ $data['completed'] }}/{{ $data['assigned'] }}</div>
                            </div>
                        </div>
                        <div style="width: 80px; text-align: right; font-size: 12px;">{{ $data['percentage'] }}%</div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Data Table -->
        <table class="chart-table">
            <thead>
                <tr>
                    <th>Reviewer</th>
                    <th>Assigned</th>
                    <th>Completed</th>
                    <th>Completion Rate</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviewCompletionData as $data)
                    <tr>
                        <td>{{ $data['reviewer'] }}</td>
                        <td>{{ $data['assigned'] }}</td>
                        <td>{{ $data['completed'] }}</td>
                        <td>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $data['percentage'] }}%">
                                    {{ $data['percentage'] }}%
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No review completion data available.</p>
    @endif

    <!-- Chart 4: Acceptance vs Rejection Rates -->
    <div class="subsection-title">Acceptance vs Rejection Rates</div>
    @if(array_sum($acceptanceData) > 0)
        <!-- Visual Doughnut Chart -->
        <div class="chart-container">
            <div class="pie-chart">
                @php
                    $colors = ['#28a745', '#dc3545', '#ffc107'];
                    $colorIndex = 0;
                @endphp
                @foreach($acceptanceData as $decision => $count)
                    @if($count > 0)
                        @php
                            $percentage = array_sum($acceptanceData) > 0 ? ($count / array_sum($acceptanceData)) * 100 : 0;
                            $color = $colors[$colorIndex % count($colors)];
                            $colorIndex++;
                        @endphp
                        <div style="text-align: center;">
                            <div class="pie-segment" style="background-color: {{ $color }}; width: {{ max(40, $percentage * 3) }}px; height: {{ max(40, $percentage * 3) }}px;">
                                <div class="pie-value">{{ $count }}</div>
                            </div>
                            <div class="pie-label">{{ $decision }}<br>({{ round($percentage, 1) }}%)</div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        
        <!-- Data Table -->
        <table class="chart-table">
            <thead>
                <tr>
                    <th>Decision</th>
                    <th>Count</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($acceptanceData as $decision => $count)
                    @if($count > 0)
                        <tr>
                            <td>{{ $decision }}</td>
                            <td>{{ $count }}</td>
                            <td>{{ array_sum($acceptanceData) > 0 ? round(($count / array_sum($acceptanceData)) * 100, 1) : 0 }}%</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else
        <p>No decision data available.</p>
    @endif

    <!-- Chart 5: Submissions per Conference -->
    <div class="subsection-title">Submissions per Conference</div>
    @if(!empty($conferencesData))
        <!-- Visual Horizontal Bar Chart -->
        <div class="chart-container">
            <div class="bar-chart">
                @php
                    $maxCount = max($conferencesData);
                @endphp
                @foreach($conferencesData as $conferenceTitle => $count)
                    @php
                        $percentage = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
                    @endphp
                    <div class="bar-item">
                        <div class="bar-label" style="font-size: 10px;">{{ Str::limit($conferenceTitle, 20) }}</div>
                        <div class="bar-container">
                            <div class="bar-fill" style="width: {{ $percentage }}%; background-color: #17a2b8;">
                                <div class="bar-value">{{ $count }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Data Table -->
        <table class="chart-table">
            <thead>
                <tr>
                    <th>Conference</th>
                    <th>Paper Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($conferencesData as $conferenceTitle => $count)
                    <tr>
                        <td>{{ $conferenceTitle }}</td>
                        <td>{{ $count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No conference data available.</p>
    @endif

    <div class="page-break"></div>

    <!-- Review and Decisions Section -->
    <div class="section-title">Review and Decisions</div>
    
    <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 30px;">
        <div class="stat-card" style="flex: 1; min-width: 200px;">
            <div class="stat-value">{{ $reviewStatsDetailed['total_reviews'] }}</div>
            <div class="stat-label">Total Reviews</div>
        </div>
        <div class="stat-card" style="flex: 1; min-width: 200px;">
            <div class="stat-value">{{ number_format($reviewStatsDetailed['average_reviews_per_paper'], 1) }}</div>
            <div class="stat-label">Average Reviews per Paper</div>
        </div>
        <div class="stat-card" style="flex: 1; min-width: 200px;">
            <div class="stat-value">{{ number_format($reviewStatsDetailed['review_completion_rate'], 1) }}%</div>
            <div class="stat-label">Review Completion Rate</div>
        </div>
    </div>

    @if($papers->isNotEmpty())
        <div class="subsection-title">Papers and Reviews</div>
        @foreach($papers as $paper)
            <div class="paper">
                <div class="paper-title">{{ $paper->title }}</div>
                <div class="paper-authors">
                    @foreach($paper->authors as $author)
                        {{ $author->name }}@if(!$loop->last), @endif
                    @endforeach
                </div>
                <div style="margin: 10px 0;">
                    <span class="badge badge-{{ $paper->status === 'accepted' ? 'success' : ($paper->status === 'rejected' ? 'danger' : 'warning') }}">
                        {{ ucfirst(str_replace('_', ' ', $paper->status)) }}
                    </span>
                    <span class="badge badge-info">{{ $paper->reviews->count() }} Reviews</span>
                </div>
                
                @if($paper->reviews->isNotEmpty())
                    <div style="margin-top: 10px;">
                        <strong>Reviews:</strong>
                        @foreach($paper->reviews as $review)
                            <div style="margin-left: 20px; margin-top: 5px;">
                                <strong>{{ $review->reviewer->name }}</strong>: 
                                Score: {{ $review->score ?? 'Not provided' }}, 
                                Status: {{ ucfirst($review->status) }}
                                @if($review->recommendation)
                                    , Recommendation: {{ ucfirst(str_replace('_', ' ', $review->recommendation)) }}
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
                
                @if($paper->decision_notes)
                    <div style="margin-top: 10px;">
                        <strong>Decision Notes:</strong> {{ $paper->decision_notes }}
                    </div>
                @endif
            </div>
        @endforeach
    @endif

    <div class="page-break"></div>

    <!-- Accepted Papers Section -->
    <div class="section-title">Accepted Papers ({{ $acceptedPapers->count() }})</div>
    @if($acceptedPapers->isNotEmpty())
        @foreach($acceptedPapers as $paper)
            <div class="paper">
                <div class="paper-title">{{ $paper->title }}</div>
                <div class="paper-authors">
                    @foreach($paper->authors as $author)
                        {{ $author->name }}@if(!$loop->last), @endif
                    @endforeach
                </div>
                <div style="margin-top: 5px;">
                    <span class="badge {{ $paper->approved_for_proceedings ? 'badge-success' : 'badge-warning' }}">
                        {{ $paper->approved_for_proceedings ? 'Approved for Proceedings' : 'Not in Proceedings' }}
                    </span>
                </div>
            </div>
        @endforeach
    @else
        <p>No accepted papers.</p>
    @endif

    <div class="page-break"></div>

    <!-- Rejected Papers Section -->
    <div class="section-title">Rejected Papers ({{ $rejectedPapers->count() }})</div>
    @if($rejectedPapers->isNotEmpty())
        @foreach($rejectedPapers as $paper)
            <div class="paper">
                <div class="paper-title">{{ $paper->title }}</div>
                <div class="paper-authors">
                    @foreach($paper->authors as $author)
                        {{ $author->name }}@if(!$loop->last), @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    @else
        <p>No rejected papers.</p>
    @endif

    <div class="page-break"></div>

    <!-- Proceedings Section -->
    <div class="section-title">Proceedings</div>
    @if($proceedings)
        <div class="info-grid">
            <div class="info-section">
                <h4>Proceedings Information</h4>
                <p><strong>Title:</strong> {{ $proceedings->title }}</p>
                <p><strong>Publication Date:</strong> {{ $proceedings->publication_date ? $proceedings->publication_date->format('F j, Y') : 'Not set' }}</p>
                <p><strong>ISBN:</strong> {{ $proceedings->isbn ?: 'Not assigned' }}</p>
                <p><strong>ISSN:</strong> {{ $proceedings->issn ?: 'Not assigned' }}</p>
            </div>
            <div class="info-section">
                <h4>Publication Statistics</h4>
                <div class="stat-card">
                    <div class="stat-value">{{ $proceedingsStats['total_papers'] }}</div>
                    <div class="stat-label">Total Papers</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $proceedingsStats['total_pages'] }}</div>
                    <div class="stat-label">Total Pages</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $proceedingsStats['total_papers'] > 0 ? number_format($proceedingsStats['total_pages'] / $proceedingsStats['total_papers'], 1) : 0 }}</div>
                    <div class="stat-label">Average Pages per Paper</div>
                </div>
            </div>
        </div>
        <div class="subsection-title">Published Papers ({{ $proceedingsPapers->count() }})</div>
        @if($proceedingsPapers->count() > 0)
            <table class="chart-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Authors</th>
                        <th>Pages</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($proceedingsPapers as $index => $paper)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $paper->title }}</strong><br><small>{{ Str::limit($paper->abstract, 100) }}</small></td>
                            <td>
                                @if($paper->authors->count() > 0)
                                    {{ $paper->authors->pluck('name')->implode(', ') }}
                                @else
                                    {{ $paper->user ? $paper->user->name : 'Unknown' }}
                                @endif
                            </td>
                            <td>{{ $paper->pages ?: 'Not set' }}</td>
                            <td><span class="badge badge-success">Published</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No papers have been published in the proceedings yet.</p>
        @endif
    @else
        <p>Proceedings have not been created for this conference yet.</p>
    @endif

    <div class="page-break"></div>

    <!-- Program Book Section -->
    <div class="section-title">Program Book</div>
    @if($programBook)
        <div class="info-grid">
            <div class="info-section">
                <h4>Program Book Information</h4>
                <p><strong>Title:</strong> {{ $programBook->title }}</p>
                <p><strong>Date Range:</strong> {{ $programBook->start_date->format('F j, Y') }} - {{ $programBook->end_date->format('F j, Y') }}</p>
                @if($programBook->welcome_message)
                    <p><strong>Welcome Message:</strong> {{ Str::limit($programBook->welcome_message, 100) }}</p>
                @endif
                @if($programBook->general_information)
                    <p><strong>General Information:</strong> {{ Str::limit($programBook->general_information, 100) }}</p>
                @endif
            </div>
            <div class="info-section">
                <h4>Event Statistics</h4>
                <div class="stat-card">
                    <div class="stat-value">{{ $programBookStats['total_sessions'] }}</div>
                    <div class="stat-label">Total Sessions</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $programBookStats['total_presentations'] }}</div>
                    <div class="stat-label">Total Presentations</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $programBookStats['total_sessions'] > 0 ? number_format($programBookStats['total_presentations'] / $programBookStats['total_sessions'], 1) : 0 }}</div>
                    <div class="stat-label">Average Presentations per Session</div>
                </div>
            </div>
        </div>
        <div class="subsection-title">Program Schedule</div>
        @if($sessions->count() > 0)
            @foreach($sessions as $date => $daySessions)
                <div class="subsection-title" style="color: #007bff; border-bottom: 2px solid #007bff; padding: 10px 0; margin: 20px 0 10px 0; font-size: 14px; font-weight: bold;">
                    {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                </div>
                @foreach($daySessions as $session)
                    <div class="paper" style="border-left: 3px solid #007bff;">
                        <div class="paper-title">{{ $session->title }}</div>
                        <div class="paper-authors">{{ ucfirst($session->type) }} Session</div>
                        <div style="font-size: 12px; color: #666;">
                            <strong>Time:</strong> {{ $session->start_time->format('g:i A') }} - {{ $session->end_time->format('g:i A') }} |
                            <strong>Location:</strong> {{ $session->location }}
                            @if($session->session_chair)
                                | <strong>Session Chair:</strong> {{ $session->session_chair }}
                            @endif
                            | <strong>Presentations:</strong> {{ $session->presentations->count() }}
                        </div>
                        @if($session->description)
                            <div style="color: #666; margin-bottom: 10px; font-style: italic;">{{ $session->description }}</div>
                        @endif
                        @if($session->presentations->count() > 0)
                            <table class="chart-table" style="font-size: 11px;">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Title</th>
                                        <th>Speaker</th>
                                        <th>Affiliation</th>
                                        <th>Paper</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($session->presentations as $presentation)
                                        <tr>
                                            <td>{{ $presentation->start_time->format('g:i A') }} - {{ $presentation->end_time->format('g:i A') }}</td>
                                            <td><strong>{{ $presentation->title }}</strong><br><span style="color: #666; font-size: 10px;">{{ Str::limit($presentation->abstract, 80) }}</span></td>
                                            <td>{{ $presentation->speaker }}</td>
                                            <td>{{ $presentation->affiliation }}</td>
                                            <td>{{ $presentation->paper ? $presentation->paper->title : '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No presentations in this session.</p>
                        @endif
                    </div>
                @endforeach
            @endforeach
        @else
            <p>No sessions scheduled in the program book yet.</p>
        @endif
    @else
        <p>Program book has not been created for this conference yet.</p>
    @endif
</body>
</html>
