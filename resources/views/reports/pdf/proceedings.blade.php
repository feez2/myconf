<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Proceedings Report - {{ $conference->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        .header h2 {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 16px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h3 {
            color: #007bff;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .info-row {
            display: table-row;
        }
        .info-cell {
            display: table-cell;
            padding: 5px 10px;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: bold;
            width: 40%;
        }
        .info-value {
            width: 60%;
        }
        .stats-grid {
            display: table;
            width: 100%;
        }
        .stats-row {
            display: table-row;
        }
        .stats-cell {
            display: table-cell;
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .stats-header {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .papers-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .papers-table th,
        .papers-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .papers-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .papers-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-badge {
            background-color: #28a745;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 20px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Proceedings Report</h1>
        <h2>{{ $conference->title }}</h2>
        <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>

    @if($proceedings)
        <div class="section">
            <h3>Proceedings Information</h3>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-cell info-label">Title:</div>
                    <div class="info-cell info-value">{{ $proceedings->title }}</div>
                </div>
                <div class="info-row">
                    <div class="info-cell info-label">Publication Date:</div>
                    <div class="info-cell info-value">{{ $proceedings->publication_date ? $proceedings->publication_date->format('F j, Y') : 'Not set' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-cell info-label">ISBN:</div>
                    <div class="info-cell info-value">{{ $proceedings->isbn ?: 'Not assigned' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-cell info-label">ISSN:</div>
                    <div class="info-cell info-value">{{ $proceedings->issn ?: 'Not assigned' }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <h3>Publication Statistics</h3>
            <div class="stats-grid">
                <div class="stats-row">
                    <div class="stats-cell stats-header">Total Papers</div>
                    <div class="stats-cell stats-header">Total Pages</div>
                    <div class="stats-cell stats-header">Average Pages per Paper</div>
                </div>
                <div class="stats-row">
                    <div class="stats-cell">{{ $stats['total_papers'] }}</div>
                    <div class="stats-cell">{{ $stats['total_pages'] }}</div>
                    <div class="stats-cell">{{ $stats['total_papers'] > 0 ? number_format($stats['total_pages'] / $stats['total_papers'], 1) : 0 }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <h3>Published Papers ({{ $papers->count() }})</h3>
            @if($papers->count() > 0)
                <table class="papers-table">
                    <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 50%">Title</th>
                            <th style="width: 25%">Authors</th>
                            <th style="width: 10%">Pages</th>
                            <th style="width: 10%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($papers as $index => $paper)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $paper->title }}</strong>
                                    @if($paper->abstract)
                                        <br><small>{{ Str::limit($paper->abstract, 100) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($paper->authors->count() > 0)
                                        {{ $paper->authors->pluck('name')->implode(', ') }}
                                    @else
                                        {{ $paper->user ? $paper->user->name : 'Unknown' }}
                                    @endif
                                </td>
                                <td>{{ $paper->pages ?: 'Not set' }}</td>
                                <td><span class="status-badge">Published</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">
                    No papers have been published in the proceedings yet.
                </div>
            @endif
        </div>
    @else
        <div class="section">
            <h3>Proceedings Status</h3>
            <div class="no-data">
                Proceedings have not been created for this conference yet.
            </div>
        </div>
    @endif
</body>
</html> 