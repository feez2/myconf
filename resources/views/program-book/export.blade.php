<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $programBook->title }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .page-break { page-break-after: always; }
        .cover-page { text-align: center; padding: 100px 0; }
        .title { font-size: 24px; font-weight: bold; margin-bottom: 20px; }
        .conference-title { font-size: 18px; margin-bottom: 30px; }
        .publication-date { margin-bottom: 50px; }
        .day-schedule { margin-bottom: 40px; page-break-inside: avoid; }
        .day-title { font-size: 18px; font-weight: bold; margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .session { margin-bottom: 20px; }
        .session-title { font-weight: bold; font-size: 16px; }
        .session-time { font-style: italic; color: #555; }
        .session-location { color: #555; margin-bottom: 10px; }
        .presentation { margin-left: 20px; margin-bottom: 15px; }
        .presentation-title { font-weight: bold; }
        .presentation-time { font-style: italic; color: #555; }
        .presentation-speaker { margin-bottom: 5px; }
        .page-number { text-align: center; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <!-- Front Cover -->
    <div class="cover-page">
        @if($programBook->cover_image_path)
            <img src="{{ storage_path('app/public/' . $programBook->cover_image_path) }}" style="max-width: 100%; max-height: 600px;">
        @else
            <div class="title">{{ $programBook->title }}</div>
            <div class="conference-title">{{ $programBook->conference->title }}</div>
            <div class="publication-date">{{ $programBook->date->format('F j, Y') }}</div>
        @endif
    </div>

    <!-- Title Page -->
    <div class="page-break"></div>
    <div style="text-align: center; padding: 50px 0;">
        <h1>{{ $programBook->title }}</h1>
        <h2>{{ $programBook->conference->title }}</h2>
        <p>Date: {{ $programBook->date->format('F j, Y') }}</p>
    </div>

    <!-- Welcome Message -->
    @if($programBook->welcome_message)
        <div class="page-break"></div>
        <div style="padding: 50px;">
            <h2>Welcome Message</h2>
            <p>{!! nl2br(e($programBook->welcome_message)) !!}</p>
        </div>
    @endif

    <!-- General Information -->
    @if($programBook->general_information)
        <div class="page-break"></div>
        <div style="padding: 50px;">
            <h2>General Information</h2>
            <p>{!! nl2br(e($programBook->general_information)) !!}</p>
        </div>
    @endif

    <!-- Schedule -->
    <div class="page-break"></div>
    <div style="padding: 50px;">
        <h2>Conference Program</h2>

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
                            @if($session->session_chair)
                                | Session Chair: {{ $session->session_chair }}
                            @endif
                        </div>

                        @if($session->description)
                            <div style="margin-bottom: 10px;">{{ $session->description }}</div>
                        @endif

                        @if($session->presentations->isNotEmpty())
                            <div style="margin-top: 10px;">
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
                                            <div style="margin-top: 5px;">
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
    </div>

    <!-- Back Cover -->
    <div class="page-break"></div>
    <div class="cover-page">
        @if($programBook->conference->logo_path)
            <img src="{{ storage_path('app/public/' . $programBook->conference->logo_path) }}" style="max-width: 200px; margin-bottom: 20px;">
        @endif
        <div class="title">{{ $programBook->conference->title }}</div>
        <div class="publication-date">Program Book - {{ $programBook->date->format('F j, Y') }}</div>
    </div>
</body>
</html>
