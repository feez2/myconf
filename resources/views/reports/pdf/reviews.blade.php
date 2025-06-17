<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Review and Decisions Report - {{ $conference->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .header h2 {
            font-size: 14px;
            color: #666;
            margin: 0;
        }
        .stats-container {
            margin-bottom: 20px;
        }
        .stats-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .stat-box {
            width: 32%;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .stat-box h3 {
            font-size: 14px;
            margin: 0 0 5px 0;
            color: #666;
        }
        .stat-box .value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            color: white;
        }
        .badge-success { background-color: #28a745; }
        .badge-danger { background-color: #dc3545; }
        .badge-warning { background-color: #ffc107; color: #000; }
        .badge-info { background-color: #17a2b8; }
        .badge-primary { background-color: #007bff; }
        .review-section {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .review-section:last-child {
            border-bottom: none;
        }
        .decision-notes {
            background-color: #f8f9fa;
            padding: 8px;
            margin: 5px 0;
            border-radius: 3px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Review and Decisions Report</h1>
        <h2>{{ $conference->title }}</h2>
        <p>Generated on {{ now()->format('F j, Y') }}</p>
    </div>

    <!-- Review Statistics -->
    <div class="stats-container">
        <h3>Review Statistics</h3>
        <div class="stats-row">
            <div class="stat-box">
                <h3>Total Reviews</h3>
                <div class="value">{{ $reviewStats['total_reviews'] }}</div>
            </div>
            <div class="stat-box">
                <h3>Average Reviews per Paper</h3>
                <div class="value">{{ number_format($reviewStats['average_reviews_per_paper'], 1) }}</div>
            </div>
            <div class="stat-box">
                <h3>Review Completion Rate</h3>
                <div class="value">{{ number_format($reviewStats['review_completion_rate'], 1) }}%</div>
            </div>
        </div>
    </div>

    <!-- Papers and Reviews -->
    <h3>Papers and Reviews</h3>
    @if($papers->isEmpty())
        <p>No papers have been submitted yet.</p>
    @else
        @foreach($papers as $paper)
            <div style="margin-bottom: 20px; page-break-inside: avoid;">
                <h4>{{ $paper->title }}</h4>
                <p><strong>Submitted by:</strong> {{ $paper->user->name }}</p>
                
                <table>
                    <tr>
                        <th style="width: 20%">Status</th>
                        <td>
                            <span class="badge badge-{{ $paper->status === 'accepted' ? 'success' : 
                                ($paper->status === 'rejected' ? 'danger' : 
                                ($paper->status === 'revision_required' ? 'warning' : 'primary')) }}">
                                {{ ucfirst(str_replace('_', ' ', $paper->status)) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Reviews</th>
                        <td>
                            @if($paper->reviews->isEmpty())
                                <p>No reviews yet</p>
                            @else
                                @foreach($paper->reviews as $review)
                                    <div class="review-section">
                                        <p><strong>Reviewer:</strong> {{ $review->reviewer->name }}</p>
                                        <p><strong>Score:</strong> {{ $review->score }}/5</p>
                                        @if($review->recommendation)
                                            <p><strong>Recommendation:</strong> {{ ucfirst(str_replace('_', ' ', $review->recommendation)) }}</p>
                                        @endif
                                        @if($review->comments)
                                            <p><strong>Comments:</strong></p>
                                            <p>{{ $review->comments }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                    @if($paper->status === 'accepted' || $paper->status === 'rejected' || $paper->status === 'revision_required')
                        <tr>
                            <th>Decision</th>
                            <td>
                                @if($paper->decision_notes)
                                    <div class="decision-notes">
                                        {{ $paper->decision_notes }}
                                    </div>
                                @endif
                                @if($paper->decision_made_at)
                                    <p><strong>Decision Date:</strong> {{ $paper->decision_made_at->format('F j, Y') }}</p>
                                @endif
                                @if($paper->camera_ready_deadline)
                                    <p><strong>Camera Ready Deadline:</strong> {{ $paper->camera_ready_deadline->format('F j, Y') }}</p>
                                @endif
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
            @if(!$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    @endif
</body>
</html> 