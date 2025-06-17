<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Select Conference</h4>
                    </div>
                    <div class="card-body">
                        @if($conferences->isEmpty())
                            <div class="alert alert-info">
                                No conferences with papers available.
                            </div>
                        @else
                            <div class="list-group">
                                @foreach($conferences as $conference)
                                    <a href="{{ route('reports.index', $conference) }}"
                                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1">{{ $conference->title }}</h5>
                                            <p class="mb-1 text-muted">{{ $conference->acronym }}</p>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">
                                            {{ $conference->papers()->count() }} submissions
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
