<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Review Statistics - {{ $conference->title }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; }
        .conference-title { font-size: 24px; font-weight: bold; }
        .stat-card { border: 1px solid #ddd; border-radius: 5px; padding: 15px; margin-bottom: 20px; }
        .stat-value { font-size: 24px; font-weight: bold; }
        .stat-label { font-size: 14px; color: #666; }
        .distribution-item { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .distribution-score { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="conference-title">{{ $conference->title }} ({{ $conference->acronym }})</div>
        <h2>Review Statistics</h2>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total_reviews'] }}</div>
                <div class="stat-label">Total Reviews</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card">
                <div class="stat-value">{{ number_format($stats['average_score'], 2) }}</div>
                <div class="stat-label">Average Score</div>
            </div>
        </div>
    </div>

    <div style="margin-top: 30px;">
        <h4>Score Distribution</h4>
        @foreach($stats['score_distribution']->sortKeys() as $score => $count)
            <div class="distribution-item">
                <span class="distribution-score">Score {{ $score }}</span>
                <span>{{ $count }} reviews</span>
            </div>
        @endforeach
    </div>

    <div style="margin-top: 30px; font-size: 12px; color: #666; text-align: center;">
        Generated on {{ now()->format('F j, Y \a\t g:i A') }}
    </div>
</body>
</html>
