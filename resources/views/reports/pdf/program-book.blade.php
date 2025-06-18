<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Program Book Report - {{ $conference->title }}</title>
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
        .day-header {
            color: #007bff;
            border-bottom: 2px solid #007bff;
            padding: 10px 0;
            margin: 20px 0 10px 0;
            font-size: 14px;
            font-weight: bold;
        }
        .session-card {
            border: 1px solid #ddd;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .session-header {
            background-color: #f8f9fa;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .session-title {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 5px;
        }
        .session-type {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
        }
        .session-meta {
            font-size: 11px;
            color: #666;
        }
        .session-body {
            padding: 8px;
        }
        .session-description {
            color: #666;
            margin-bottom: 10px;
            font-style: italic;
        }
        .presentations-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        .presentations-table th,
        .presentations-table td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }
        .presentations-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .presentations-table tr:nth-child(even) {
            background-color: #f9f9f9;
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
        .presentation-title {
            font-weight: bold;
        }
        .presentation-abstract {
            color: #666;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Program Book Report</h1>
        <h2>{{ $conference->title }}</h2>
        <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>

    @if($programBook)
        <div class="section">
            <h3>Program Book Information</h3>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-cell info-label">Title:</div>
                    <div class="info-cell info-value">{{ $programBook->title }}</div>
                </div>
                <div class="info-row">
                    <div class="info-cell info-label">Date Range:</div>
                    <div class="info-cell info-value">{{ $programBook->start_date->format('F j, Y') }} - {{ $programBook->end_date->format('F j, Y') }}</div>
                </div>
                @if($programBook->welcome_message)
                <div class="info-row">
                    <div class="info-cell info-label">Welcome Message:</div>
                    <div class="info-cell info-value">{{ Str::limit($programBook->welcome_message, 100) }}</div>
                </div>
                @endif
                @if($programBook->general_information)
                <div class="info-row">
                    <div class="info-cell info-label">General Information:</div>
                    <div class="info-cell info-value">{{ Str::limit($programBook->general_information, 100) }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="section">
            <h3>Event Statistics</h3>
            <div class="stats-grid">
                <div class="stats-row">
                    <div class="stats-cell stats-header">Total Sessions</div>
                    <div class="stats-cell stats-header">Total Presentations</div>
                    <div class="stats-cell stats-header">Average Presentations per Session</div>
                </div>
                <div class="stats-row">
                    <div class="stats-cell">{{ $stats['total_sessions'] }}</div>
                    <div class="stats-cell">{{ $stats['total_presentations'] }}</div>
                    <div class="stats-cell">{{ $stats['total_sessions'] > 0 ? number_format($stats['total_presentations'] / $stats['total_sessions'], 1) : 0 }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <h3>Program Schedule</h3>
            @if($sessions->count() > 0)
                @foreach($sessions as $date => $daySessions)
                    <div class="day-header">
                        {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                    </div>
                    
                    @foreach($daySessions as $session)
                        <div class="session-card">
                            <div class="session-header">
                                <div class="session-title">{{ $session->title }}</div>
                                <div class="session-type">{{ ucfirst($session->type) }} Session</div>
                                <div class="session-meta">
                                    <strong>Time:</strong> {{ $session->start_time->format('g:i A') }} - {{ $session->end_time->format('g:i A') }} | 
                                    <strong>Location:</strong> {{ $session->location }}
                                    @if($session->session_chair)
                                        | <strong>Session Chair:</strong> {{ $session->session_chair }}
                                    @endif
                                    | <strong>Presentations:</strong> {{ $session->presentations->count() }}
                                </div>
                            </div>
                            <div class="session-body">
                                @if($session->description)
                                    <div class="session-description">{{ $session->description }}</div>
                                @endif
                                
                                @if($session->presentations->count() > 0)
                                    <table class="presentations-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 15%">Time</th>
                                                <th style="width: 35%">Title</th>
                                                <th style="width: 25%">Speaker</th>
                                                <th style="width: 15%">Affiliation</th>
                                                <th style="width: 10%">Paper</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($session->presentations as $presentation)
                                                <tr>
                                                    <td>
                                                        {{ $presentation->start_time->format('g:i A') }} - {{ $presentation->end_time->format('g:i A') }}
                                                    </td>
                                                    <td>
                                                        <div class="presentation-title">{{ $presentation->title }}</div>
                                                        @if($presentation->abstract)
                                                            <div class="presentation-abstract">{{ Str::limit($presentation->abstract, 80) }}</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $presentation->speaker_name }}
                                                    </td>
                                                    <td>
                                                        @if($presentation->speaker_affiliation)
                                                            {{ $presentation->speaker_affiliation }}
                                                        @else
                                                            <span style="color: #666;">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($presentation->paper_id)
                                                            <span style="color: #007bff;">Available</span>
                                                        @else
                                                            <span style="color: #666;">No paper</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="no-data">No presentations scheduled for this session.</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endforeach
            @else
                <div class="no-data">
                    No sessions have been scheduled yet.
                </div>
            @endif
        </div>
    @else
        <div class="section">
            <h3>Program Book Status</h3>
            <div class="no-data">
                Program book has not been created for this conference yet.
            </div>
        </div>
    @endif
</body>
</html> 