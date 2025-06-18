<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $programBook->title }}</title>
    <style>
        @page {
            margin: 1in;
            size: A4;
        }
        
        body { 
            font-family: 'Times New Roman', serif; 
            line-height: 1.6; 
            margin: 0;
            padding: 0;
            color: #333;
        }
        
        .page-break { 
            page-break-after: always; 
        }
        
        /* Front Cover Page */
        .cover-page { 
            text-align: center; 
            padding: 120px 40px;
            background-color: #667eea;
            color: white;
            min-height: 100vh;
        }
        
        .cover-title { 
            font-size: 36px; 
            font-weight: bold; 
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .cover-subtitle {
            font-size: 24px;
            margin-bottom: 40px;
            font-style: italic;
        }
        
        .cover-date {
            font-size: 18px;
            margin-bottom: 60px;
            font-weight: 300;
        }
        
        .cover-logo {
            max-width: 200px;
            margin-bottom: 40px;
        }
        
        /* Content Pages */
        .content-page {
            padding: 60px 40px;
            background: white;
        }
        
        .page-title {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 40px;
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 15px;
        }
        
        .section-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #34495e;
            border-left: 4px solid #3498db;
            padding-left: 15px;
        }
        
        .description-text {
            font-size: 14px;
            line-height: 1.8;
            text-align: justify;
            margin-bottom: 30px;
        }
        
        /* Schedule Styling */
        .day-schedule { 
            margin-bottom: 40px; 
            page-break-inside: avoid; 
        }
        
        .day-title { 
            font-size: 18px; 
            font-weight: bold; 
            margin-bottom: 20px; 
            background: #34495e;
            color: white;
            padding: 12px 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .session { 
            margin-bottom: 25px;
            border: 1px solid #ecf0f1;
            padding: 20px;
            background: #f8f9fa;
        }
        
        .session-title { 
            font-weight: bold; 
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        
        .session-time { 
            font-style: italic; 
            color: #e74c3c;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .session-location { 
            color: #7f8c8d;
            margin-bottom: 10px;
            font-weight: 500;
        }
        
        .session-chair {
            color: #27ae60;
            font-style: italic;
            margin-bottom: 10px;
        }
        
        .session-description {
            font-size: 13px;
            color: #555;
            margin-bottom: 15px;
            font-style: italic;
        }
        
        .presentations-container {
            margin-top: 15px;
            border-left: 3px solid #3498db;
            padding-left: 15px;
        }
        
        .presentation { 
            margin-bottom: 15px;
            padding: 12px;
            background: white;
            border: 1px solid #ddd;
        }
        
        .presentation-title { 
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .presentation-time { 
            font-style: italic; 
            color: #e67e22;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .presentation-speaker { 
            margin-bottom: 5px;
            color: #27ae60;
            font-weight: 500;
        }
        
        .presentation-abstract {
            font-size: 12px;
            color: #666;
            font-style: italic;
            margin-top: 8px;
            padding: 8px;
            background: #f8f9fa;
        }
        
        /* Table of Contents */
        .toc {
            margin-bottom: 40px;
        }
        
        .toc-item {
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .toc-title {
            font-weight: bold;
            color: #2c3e50;
        }
        
        .toc-page {
            float: right;
            color: #7f8c8d;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
        }
        
        /* Page numbers */
        .page-number {
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 20px;
        }
        
        /* Clear floats */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <!-- Front Cover -->
    <div class="cover-page">
        @if($programBook->conference->logo_path)
            <img src="{{ storage_path('app/public/' . $programBook->conference->logo_path) }}" class="cover-logo">
        @endif
        <div class="cover-title">{{ $programBook->title }}</div>
        <div class="cover-subtitle">{{ $programBook->conference->title }}</div>
        <div class="cover-date">{{ $programBook->start_date->format('F j, Y') }} - {{ $programBook->end_date->format('F j, Y') }}</div>
        <div style="font-size: 14px; margin-top: 40px;">
            <p>Program Book</p>
            <p>Tentative Schedule</p>
        </div>
    </div>

    <!-- Table of Contents -->
    <div class="page-break"></div>
    <div class="content-page">
        <div class="page-title">Table of Contents</div>
        
        <div class="toc">
            <div class="toc-item clearfix">
                <span class="toc-title">Welcome Message</span>
                <span class="toc-page">3</span>
            </div>
            <div class="toc-item clearfix">
                <span class="toc-title">General Information</span>
                <span class="toc-page">4</span>
            </div>
            <div class="toc-item clearfix">
                <span class="toc-title">Conference Program</span>
                <span class="toc-page">5</span>
            </div>
        </div>
    </div>

    <!-- Welcome Message -->
    @if($programBook->welcome_message)
        <div class="page-break"></div>
        <div class="content-page">
            <div class="page-title">Welcome Message</div>
            <div class="description-text">{!! nl2br(e($programBook->welcome_message)) !!}</div>
        </div>
    @endif

    <!-- General Information -->
    @if($programBook->general_information)
        <div class="page-break"></div>
        <div class="content-page">
            <div class="page-title">General Information</div>
            <div class="description-text">{!! nl2br(e($programBook->general_information)) !!}</div>
        </div>
    @endif

    <!-- Schedule -->
    <div class="page-break"></div>
    <div class="content-page">
        <div class="page-title">Conference Program</div>
        <div class="section-title">Tentative Schedule</div>

        @if($scheduleByDay->isEmpty())
            <div style="text-align: center; padding: 40px; color: #7f8c8d; font-style: italic;">
                No sessions scheduled yet.
            </div>
        @else
            @foreach($scheduleByDay as $date => $sessions)
                <div class="day-schedule">
                    <div class="day-title">
                        {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                    </div>

                    @foreach($sessions as $session)
                        <div class="session">
                            <div class="session-title">{{ $session->title }}</div>
                            <div class="session-time">
                                {{ $session->start_time->format('h:i A') }} - {{ $session->end_time->format('h:i A') }}
                            </div>
                            <div class="session-location">
                                Location: {{ $session->location }}
                            </div>
                            @if($session->session_chair)
                                <div class="session-chair">
                                    Session Chair: {{ $session->session_chair }}
                                </div>
                            @endif

                            @if($session->description)
                                <div class="session-description">{{ $session->description }}</div>
                            @endif

                            @if($session->presentations->isNotEmpty())
                                <div class="presentations-container">
                                    <div style="font-weight: bold; margin-bottom: 10px; color: #2c3e50;">Presentations:</div>
                                    @foreach($session->presentations as $presentation)
                                        <div class="presentation">
                                            <div class="presentation-title">{{ $presentation->title }}</div>
                                            <div class="presentation-time">
                                                {{ $presentation->start_time->format('h:i A') }} - {{ $presentation->end_time->format('h:i A') }}
                                            </div>
                                            <div class="presentation-speaker">
                                                Speaker: {{ $presentation->speaker_name }}
                                                @if($presentation->speaker_affiliation)
                                                    ({{ $presentation->speaker_affiliation }})
                                                @endif
                                            </div>
                                            @if($presentation->abstract)
                                                <div class="presentation-abstract">
                                                    <strong>Abstract:</strong> {{ $presentation->abstract }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif
        
        <div class="footer">
            <p>This is a tentative schedule and may be subject to change.</p>
            <p>For the most up-to-date information, please visit the conference website.</p>
        </div>
    </div>

    <!-- Back Cover -->
    <div class="page-break"></div>
    <div class="cover-page">
        @if($programBook->conference->logo_path)
            <img src="{{ storage_path('app/public/' . $programBook->conference->logo_path) }}" class="cover-logo">
        @endif
        <div class="cover-title">{{ $programBook->conference->title }}</div>
        <div class="cover-subtitle">Program Book</div>
        <div class="cover-date">{{ $programBook->start_date->format('F j, Y') }} - {{ $programBook->end_date->format('F j, Y') }}</div>
        <div style="font-size: 14px; margin-top: 40px;">
            <p>Thank you for attending!</p>
        </div>
    </div>
</body>
</html>
