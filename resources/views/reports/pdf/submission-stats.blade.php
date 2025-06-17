<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Submission Statistics - {{ $conference->title }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; }
        .conference-title { font-size: 24px; font-weight: bold; }
        .stat-card { border: 1px solid #ddd; border-radius: 5px; padding: 15px; margin-bottom: 20px; }
        .stat-value { font-size: 24px; font-weight: bold; }
        .stat-label { font-size: 14px; color: #666; }
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
        .text-primary { color: #007bff; }
    </style>
</head>
<body>
    <div class="header">
        <div class="conference-title">{{ $conference->title }} ({{ $conference->acronym }})</div>
        <h2>Submission Statistics</h2>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Submissions</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card">
                <div class="stat-value text-success">{{ $stats['accepted'] }}</div>
                <div class="stat-label">Accepted Papers</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card">
                <div class="stat-value text-danger">{{ $stats['rejected'] }}</div>
                <div class="stat-label">Rejected Papers</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card">
                <div class="stat-value text-primary">{{ $stats['approved_for_proceedings'] }}</div>
                <div class="stat-label">Approved for Proceedings</div>
            </div>
        </div>
    </div>

    <div style="margin-top: 30px; font-size: 12px; color: #666; text-align: center;">
        Generated on {{ now()->format('F j, Y \a\t g:i A') }}
    </div>
</body>
</html>
