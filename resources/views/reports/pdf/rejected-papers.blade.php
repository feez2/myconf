<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rejected Papers - {{ $conference->title }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; }
        .conference-title { font-size: 24px; font-weight: bold; }
        .paper { margin-bottom: 20px; page-break-inside: avoid; }
        .paper-title { font-weight: bold; }
        .paper-authors { font-style: italic; }
    </style>
</head>
<body>
    <div class="header">
        <div class="conference-title">{{ $conference->title }} ({{ $conference->acronym }})</div>
        <h2>Rejected Papers</h2>
        <p>Total: {{ $papers->count() }}</p>
    </div>

    @foreach($papers as $paper)
        <div class="paper">
            <div class="paper-title">{{ $paper->title }}</div>
            <div class="paper-authors">
                @foreach($paper->authors as $author)
                    {{ $author->name }}@if(!$loop->last), @endif
                @endforeach
            </div>
        </div>
    @endforeach

    <div style="margin-top: 30px; font-size: 12px; color: #666; text-align: center;">
        Generated on {{ now()->format('F j, Y \a\t g:i A') }}
    </div>
</body>
</html>
