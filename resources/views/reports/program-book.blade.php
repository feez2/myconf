<x-app-layout>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Program Book Report: {{ $conference->title }}</h2>
                    <div style="display:flex;">
                        <a href="{{ route('reports.download.program-book', $conference) }}" class="btn btn-success">
                            <i class="bi bi-download"></i> Download as PDF
                        </a>
                        <a href="{{ route('reports.index', $conference) }}" class="btn btn-secondary ms-2">
                            <i class="bi bi-arrow-left"></i> Back to Reports
                        </a>
                    </div>
                </div>

                @if($programBook)
                    <!-- Program Book Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>Program Book Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted">Event Details</h6>
                                    <p><strong>Title:</strong> {{ $programBook->title }}</p>
                                    <p><strong>Date Range:</strong> {{ $programBook->start_date->format('F j, Y') }} - {{ $programBook->end_date->format('F j, Y') }}</p>
                                    @if($programBook->welcome_message)
                                        <p><strong>Welcome Message:</strong> {{ Str::limit($programBook->welcome_message, 100) }}</p>
                                    @endif
                                    @if($programBook->general_information)
                                        <p><strong>General Information:</strong> {{ Str::limit($programBook->general_information, 100) }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">Statistics</h6>
                                    <p><strong>Total Sessions:</strong> {{ $stats['total_sessions'] }}</p>
                                    <p><strong>Total Presentations:</strong> {{ $stats['total_presentations'] }}</p>
                                    <p><strong>Average Presentations per Session:</strong> {{ $stats['total_sessions'] > 0 ? number_format($stats['total_presentations'] / $stats['total_sessions'], 1) : 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Program Schedule -->
                    <div class="card">
                        <div class="card-header">
                            <h4>Program Schedule</h4>
                        </div>
                        <div class="card-body">
                            @if($sessions->count() > 0)
                                @foreach($sessions as $date => $daySessions)
                                    <div class="mb-4">
                                        <h5 class="text-primary border-bottom pb-2">
                                            {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                                        </h5>

                                        @foreach($daySessions as $session)
                                            <div class="card mb-3">
                                                <div class="card-header bg-light">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-3">
                                                            <h6 class="mb-0">{{ $session->title }}</h6>
                                                            <small class="text-muted">{{ ucfirst($session->type) }} Session</small>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <small class="text-muted">
                                                                <i class="bi bi-clock"></i>
                                                                {{ $session->start_time->format('g:i A') }} - {{ $session->end_time->format('g:i A') }}
                                                            </small>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <small class="text-muted">
                                                                <i class="bi bi-geo-alt"></i>
                                                                {{ $session->location }}
                                                            </small>
                                                        </div>
                                                        <div class="col-md-2">
                                                            @if($session->session_chair)
                                                                <small class="text-muted">
                                                                    <i class="bi bi-person"></i>
                                                                    {{ $session->session_chair }}
                                                                </small>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-2">
                                                            <span class="badge bg-info">{{ $session->presentations->count() }} presentations</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    @if($session->description)
                                                        <p class="text-muted mb-3">{{ $session->description }}</p>
                                                    @endif

                                                    @if($session->presentations->count() > 0)
                                                        <div class="table-responsive">
                                                            <table class="table table-sm">
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
                                                                                <small class="text-muted">
                                                                                    {{ $presentation->start_time->format('g:i A') }} - {{ $presentation->end_time->format('g:i A') }}
                                                                                </small>
                                                                            </td>
                                                                            <td>
                                                                                <strong>{{ $presentation->title }}</strong>
                                                                                @if($presentation->abstract)
                                                                                    <br><small class="text-muted">{{ Str::limit($presentation->abstract, 80) }}</small>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                {{ $presentation->speaker_name }}
                                                                            </td>
                                                                            <td>
                                                                                @if($presentation->speaker_affiliation)
                                                                                    <small class="text-muted">{{ $presentation->speaker_affiliation }}</small>
                                                                                @else
                                                                                    <span class="text-muted">-</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                @if($presentation->paper_id)
                                                                                    <a href="{{ route('papers.show', $presentation->paper_id) }}" class="btn btn-sm btn-primary">
                                                                                        View Paper
                                                                                    </a>
                                                                                @else
                                                                                    <span class="text-muted">No paper</span>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @else
                                                        <p class="text-muted text-center py-3">No presentations scheduled for this session.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-calendar-x fs-1 text-muted"></i>
                                    <p class="text-muted mt-2">No sessions have been scheduled yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-calendar-x fs-1 text-muted"></i>
                            <h4 class="text-muted mt-3">No Program Book Created</h4>
                            <p class="text-muted">Program book has not been created for this conference yet.</p>
                            <a href="{{ route('program-book.create', $conference) }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Create Program Book
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
