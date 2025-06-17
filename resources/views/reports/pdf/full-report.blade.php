<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Full Report - {{ $conference->title }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; }
        .conference-title { font-size: 24px; font-weight: bold; }
        .section-title { font-size: 20px; margin: 30px 0 15px; border-bottom: 1px solid #333; padding-bottom: 5px; }
        .stat-card { border: 1px solid #ddd; border-radius: 5px; padding: 15px; margin-bottom: 20px; }
        .stat-value { font-size: 20px; font-weight: bold; }
        .stat-label { font-size: 14px; color: #666; }
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
        .text-primary { color: #007bff; }
        .paper { margin-bottom: 15px; page-break-inside: avoid; }
        .paper-title { font-weight: bold; }
        .paper-authors { font-style: italic; }
        .badge { padding: 3px 6px; border-radius: 3px; font-size: 12px; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: black; }
        .distribution-item { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .distribution-score { font-weight: bold; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="header">
        <div class="conference-title">{{ $conference->title }} ({{ $conference->acronym }})</div>
        <h1>Conference Full Report</h1>
        <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>

    <div class="section-title">Submission Statistics</div>
    <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 30px;">
        <div class="stat-card" style="flex: 1; min-width: 200px;">
            <div class="stat-value">{{ $submissionStats['total'] }}</div>
            <div class="stat-label">Total Submissions</div>
        </div>
        <div class="stat-card" style="flex: 1; min-width: 200px;">
            <div class="stat-value text-success">{{ $submissionStats['accepted'] }}</div>
            <div class="stat-label">Accepted Papers</div>
        </div>
        <div class="stat-card" style="flex: 1; min-width: 200px;">
            <div class="stat-value text-danger">{{ $submissionStats['rejected'] }}</div>
            <div class="stat-label">Rejected Papers</div>
        </div>
        <div class="stat-card" style="flex: 1; min-width: 200px;">
            <div class="stat-value text-primary">{{ $submissionStats['approved_for_proceedings'] }}</div>
            <div class="stat-label">Approved for Proceedings</div>
        </div>
    </div>

    @if($reviewStats)
        <div class="section-title">Review Statistics</div>
        <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 30px;">
            <div class="stat-card" style="flex: 1; min-width: 200px;">
                <div class="stat-value">{{ $reviewStats['total_reviews'] }}</div>
                <div class="stat-label">Total Reviews</div>
            </div>
            <div class="stat-card" style="flex: 1; min-width: 200px;">
                <div class="stat-value">{{ number_format($reviewStats['average_score'], 2) }}</div>
                <div class="stat-label">Average Score</div>
            </div>
        </div>

        <div style="margin-bottom: 30px;">
            <h4>Score Distribution</h4>
            @foreach($reviewStats['score_distribution']->sortKeys() as $score => $count)
                <div class="distribution-item">
                    <span class="distribution-score">Score {{ $score }}</span>
                    <span>{{ $count }} reviews</span>
                </div>
            @endforeach
        </div>
    @endif

    <div class="page-break"></div>

    <div class="section-title">Accepted Papers ({{ $acceptedPapers->count() }})</div>
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

    <div class="page-break"></div>

    <div class="section-title">Rejected Papers ({{ $rejectedPapers->count() }})</div>
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
</body>
</html>
