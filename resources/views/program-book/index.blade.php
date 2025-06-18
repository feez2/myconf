<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Program Book - {{ $conference->title }}</h4>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('program-book.select-conference') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Change Conference
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($programBook)
                            <div class="mb-4">
                                <h5>Program Book Details</h5>
                                <p><strong>Title:</strong> {{ $programBook->title }}</p>
                                <p><strong>Date Range:</strong> {{ $programBook->start_date->format('F j, Y') }} - {{ $programBook->end_date->format('F j, Y') }}</p>
                                <p><strong>Sessions:</strong> {{ $programBook->sessions->count() }}</p>
                                <p><strong>Presentations:</strong> {{ $programBook->sessions->sum(function($session) { return $session->presentations->count(); }) }}</p>

                                <div class="mt-4 d-flex flex-wrap gap-2">
                                    <a href="{{ route('program-book.edit', $programBook) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil-square me-1"></i> Edit Program Book
                                    </a>

                                    <a href="{{ route('program-book.manage-sessions', $programBook) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-calendar-event me-1"></i> Manage Sessions
                                    </a>

                                    <a href="{{ route('program-book.export', $programBook) }}" class="btn btn-sm btn-success">
                                        <i class="bi bi-file-earmark-arrow-down me-1"></i> Export PDF
                                    </a>
                                </div>

                            </div>

                            <hr>

                            <h5 class="mt-4">Schedule Overview</h5>
                            @if($programBook->sessions->isEmpty())
                                <div class="alert alert-info">
                                    No sessions scheduled yet.
                                </div>
                            @else
                                <div class="accordion" id="scheduleAccordion">
                                    @foreach($programBook->getScheduleByDayAttribute() as $date => $sessions)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading{{ $loop->index }}">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapse{{ $loop->index }}" aria-expanded="true"
                                                    aria-controls="collapse{{ $loop->index }}">
                                                    {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse show"
                                                aria-labelledby="heading{{ $loop->index }}" data-bs-parent="#scheduleAccordion">
                                                <div class="accordion-body">
                                                    <div class="list-group">
                                                        @foreach($sessions as $session)
                                                            <div class="list-group-item">
                                                                <div class="d-flex w-100 justify-content-between">
                                                                    <h5 class="mb-1">{{ $session->title }}</h5>
                                                                    <small>{{ $session->start_time->format('h:i A') }} - {{ $session->end_time->format('h:i A') }}</small>
                                                                </div>
                                                                <p class="mb-1"><small class="text-muted">{{ $session->location }}</small></p>
                                                                @if($session->session_chair)
                                                                    <p class="mb-1"><small>Session Chair: {{ $session->session_chair }}</small></p>
                                                                @endif

                                                                @if($session->presentations->isNotEmpty())
                                                                    <div class="mt-2">
                                                                        <h6>Presentations:</h6>
                                                                        <ul class="list-group list-group-flush">
                                                                            @foreach($session->presentations as $presentation)
                                                                                <li class="list-group-item">
                                                                                    <div class="d-flex w-100 justify-content-between">
                                                                                        <strong>{{ $presentation->title }}</strong>
                                                                                        <small>{{ $presentation->start_time->format('h:i A') }} - {{ $presentation->end_time->format('h:i A') }}</small>
                                                                                    </div>
                                                                                    <div>Speaker: {{ $presentation->speaker_name }}</div>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info">
                                No program book created yet for this conference.
                            </div>
                            @can('manageProgramBook', $conference)
                                <div class="d-grid">
                                    <a href="{{ route('program-book.create', $conference) }}" class="btn btn-primary">
                                        Create Program Book
                                    </a>
                                </div>
                            @endcan
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
