<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Accepted Papers - {{ $conference->title }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; }
        .conference-title { font-size: 24px; font-weight: bold; }
        .paper { margin-bottom: 20px; page-break-inside: avoid; }
        .paper-title { font-weight: bold; }
        .paper-authors { font-style: italic; }
        .paper-status { margin-top: 5px; }
        .badge { padding: 3px 6px; border-radius: 3px; font-size: 12px; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: black; }
    </style>
</head>
<body>
    <div class="header">
        <div class="conference-title">{{ $conference->title }} ({{ $conference->acronym }})</div>
        <h2>Accepted Papers</h2>
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
            <div class="paper-status">
                <span class="badge {{ $paper->approved_for_proceedings ? 'badge-success' : 'badge-warning' }}">
                    {{ $paper->approved_for_proceedings ? 'Approved for Proceedings' : 'Not in Proceedings' }}
                </span>
            </div>
        </div>
    @endforeach

    <div style="margin-top: 30px; font-size: 12px; color: #666; text-align: center;">
        Generated on {{ now()->format('F j, Y \a\t g:i A') }}
    </div>
</body>
</html>
