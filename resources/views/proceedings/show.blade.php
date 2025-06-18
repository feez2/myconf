<x-app-layout>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>{{ $proceedings->title }}</h3>
                        <span class="badge
                            @if($proceedings->status === 'published') bg-success
                            @elseif($proceedings->status === 'archived') bg-secondary
                            @else bg-warning text-dark
                            @endif">
                            {{ $proceedings->status_name }}
                        </span>
                    </div>
                    <div class="card-body">
                        @if($proceedings->cover_image)
                            <div class="text-center mb-4">
                                <img src="{{ Storage::url($proceedings->cover_image) }}"
                                     alt="Proceedings cover"
                                     class="img-fluid rounded"
                                     style="max-height: 300px;">
                            </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Conference</h5>
                                <p>{{ $proceedings->conference->title }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5>Publication Date</h5>
                                <p>{{ $proceedings->publication_date?->format('F j, Y') ?? 'Not specified' }}</p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>ISBN</h5>
                                <p>{{ $proceedings->isbn ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5>ISSN</h5>
                                <p>{{ $proceedings->issn ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Publisher</h5>
                            <p>{{ $proceedings->publisher ?? '-' }}</p>
                        </div>

                        <div class="d-flex gap-2">
                            @if($proceedings->front_matter_file)
                                <a href="{{ Storage::url($proceedings->front_matter_file) }}"
                                   class="btn btn-outline-primary" target="_blank">
                                    Download Front Matter
                                </a>
                            @endif
                            @if($proceedings->back_matter_file)
                                <a href="{{ Storage::url($proceedings->back_matter_file) }}"
                                   class="btn btn-outline-primary" target="_blank">
                                    Download Back Matter
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5>Included Papers ({{ $proceedings->papers->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @if($proceedings->papers->isEmpty())
                            <div class="alert alert-info">
                                No papers have been added to these proceedings yet.
                            </div>
                        @else
                            <div class="list-group">
                                @foreach($proceedings->papers as $paper)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $paper->title }}</h6>
                                            <small class="text-muted">by {{ $paper->author->name }}</small>
                                        </div>
                                        <div>
                                            @if($paper->camera_ready_file)
                                                <a href="{{ Storage::url($paper->camera_ready_file) }}"
                                                   class="btn btn-sm btn-outline-primary" target="_blank">
                                                    Download
                                                </a>
                                            @else
                                                <span class="badge bg-warning text-dark">No camera-ready version</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Actions</h5>
                    </div>
                    <div class="card-body">
                        @can('manageProceedings', $proceedings->conference)
                            <a href="{{ route('proceedings.assign-papers', $proceedings) }}"
                            class="btn btn-primary w-100 mb-2 rounded-pill shadow-sm">
                                <i class="bi bi-journal-plus me-1"></i> Assign Papers
                            </a>

                            <a href="{{ route('proceedings.edit', $proceedings) }}"
                            class="btn btn-info w-100 mb-2 rounded-pill shadow-sm">
                                <i class="bi bi-pencil-square me-1"></i> Edit Proceedings
                            </a>

                            @if($proceedings->generated_pdf_file)
                                <a href="{{ route('proceedings.download', $proceedings->conference) }}"
                                class="btn btn-success w-100 mb-2 rounded-pill shadow-sm">
                                    <i class="bi bi-download me-1"></i> Download PDF
                                </a>
                                <button class="btn btn-warning w-100 mb-2 rounded-pill shadow-sm"
                                        onclick="confirm('Regenerate proceedings PDF? This will update the PDF with any changes.') && document.getElementById('regenerate-form').submit()">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Regenerate PDF
                                </button>
                                <form id="regenerate-form" action="{{ route('proceedings.regenerate', $proceedings) }}"
                                    method="POST" class="d-none">
                                    @csrf
                                </form>
                            @else
                                <button class="btn btn-success w-100 mb-2 rounded-pill shadow-sm"
                                        onclick="confirm('Generate full proceedings?') && document.getElementById('generate-form').submit()">
                                    <i class="bi bi-gear-fill me-1"></i> Generate Proceedings
                                </button>

                                <form id="generate-form" action="{{ route('proceedings.generate', $proceedings) }}"
                                    method="POST" class="d-none">
                                    @csrf
                                </form>
                            @endif
                        @endcan

                        <a href="{{ route('proceedings.index', $proceedings->conference) }}"
                        class="btn btn-secondary w-100 rounded-pill shadow-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back to Proceedings
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5>Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6>Total Pages</h6>
                            <p class="fs-4">{{ $proceedings->papers->count() * 10 }} (estimated)</p>
                        </div>
                        <div class="mb-3">
                            <h6>Camera-Ready Submissions</h6>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar"
                                     style="width: {{ ($proceedings->papers->whereNotNull('camera_ready_file')->count() / max(1, $proceedings->papers->count())) * 100 }}%">
                                    {{ $proceedings->papers->whereNotNull('camera_ready_file')->count() }}/{{ $proceedings->papers->count() }}
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h6>Copyright Forms</h6>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar"
                                     style="width: {{ ($proceedings->papers->whereNotNull('copyright_form_file')->count() / max(1, $proceedings->papers->count())) * 100 }}%">
                                    {{ $proceedings->papers->whereNotNull('copyright_form_file')->count() }}/{{ $proceedings->papers->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
