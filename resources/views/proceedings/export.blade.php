<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $proceedings->title }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .page-break { page-break-after: always; }
        .cover-page { text-align: center; padding: 100px 0; }
        .title { font-size: 24px; font-weight: bold; margin-bottom: 20px; }
        .conference-title { font-size: 18px; margin-bottom: 30px; }
        .publication-date { margin-bottom: 50px; }
        .paper { margin-bottom: 30px; page-break-inside: avoid; }
        .paper-title { font-weight: bold; font-size: 16px; }
        .paper-authors { font-style: italic; margin-bottom: 10px; }
        .paper-abstract { margin-bottom: 15px; }
        .paper-keywords { font-size: 14px; }
        .page-number { text-align: center; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <!-- Front Cover -->
    <div class="cover-page">
        @if($proceedings->front_cover_path)
            <img src="{{ storage_path('app/public/' . $proceedings->front_cover_path) }}" style="max-width: 100%;">
        @else
            <div class="title">{{ $proceedings->title }}</div>
            <div class="conference-title">{{ $proceedings->conference->title }}</div>
            <div class="publication-date">Published: {{ $proceedings->publication_date->format('F j, Y') }}</div>
        @endif
    </div>

    <!-- Title Page -->
    <div class="page-break"></div>
    <div style="text-align: center; padding: 50px 0;">
        <h1>{{ $proceedings->title }}</h1>
        <h2>{{ $proceedings->conference->title }}</h2>
        <p>Published: {{ $proceedings->publication_date->format('F j, Y') }}</p>
        @if($proceedings->isbn)
            <p>ISBN: {{ $proceedings->isbn }}</p>
        @endif
        @if($proceedings->issn)
            <p>ISSN: {{ $proceedings->issn }}</p>
        @endif
    </div>

    <!-- Table of Contents -->
    <div class="page-break"></div>
    <h2>Table of Contents</h2>
    <hr>
    @foreach($papers as $paper)
        <div style="margin-bottom: 10px;">
            <span style="font-weight: bold;">{{ $paper->title }}</span><br>
            <span style="font-style: italic;">{{ $paper->authors->pluck('name')->join(', ') }}</span>
            <span style="float: right;">Page {{ $loop->index + 4 }}</span>
        </div>
    @endforeach

    <!-- Papers -->
    @foreach($papers as $paper)
        <div class="page-break"></div>
        <div class="paper">
            <div class="paper-title">{{ $paper->title }}</div>
            <div class="paper-authors">
                {{ $paper->authors->pluck('name')->join(', ') }}
            </div>
            <div class="paper-abstract">
                <strong>Abstract:</strong> {{ $paper->abstract }}
            </div>
            <div class="paper-keywords">
                <strong>Keywords:</strong> {{ $paper->keywords }}
            </div>
        </div>
    @endforeach

    <!-- Back Cover -->
    <div class="page-break"></div>
    <div class="cover-page">
        @if($proceedings->back_cover_path)
            <img src="{{ storage_path('app/public/' . $proceedings->back_cover_path) }}" style="max-width: 100%;">
        @else
            <div class="title">{{ $proceedings->conference->title }} Proceedings</div>
            <div class="publication-date">{{ $proceedings->publication_date->format('F j, Y') }}</div>
        @endif
    </div>
</body>
</html>
