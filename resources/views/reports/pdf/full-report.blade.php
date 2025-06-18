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
    <div class="section-title">Program Committees</div>
    
    @if($details['committees']['program_chairs']->isNotEmpty())
        <div class="subsection-title">Program Chairs</div>
        <table class="committee-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Affiliation</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details['committees']['program_chairs'] as $member)
                    <tr>
                        <td>{{ $member->user->name }}</td>
                        <td>{{ $member->user->email }}</td>
                        <td>{{ $member->user->affiliation ?? 'Not specified' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($details['committees']['area_chairs']->isNotEmpty())
        <div class="subsection-title">Area Chairs</div>
        <table class="committee-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Affiliation</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details['committees']['area_chairs'] as $member)
                    <tr>
                        <td>{{ $member->user->name }}</td>
                        <td>{{ $member->user->email }}</td>
                        <td>{{ $member->user->affiliation ?? 'Not specified' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($details['committees']['reviewers']->isNotEmpty())
        <div class="subsection-title">Reviewers</div>
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
                        <td>{{ $papers->filter(function($paper) use ($member) { return $paper->reviews->where('reviewer_id', $member->user_id)->count() > 0; })->count() }}</td>
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
</body>
</html>
