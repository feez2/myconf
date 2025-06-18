<x-app-layout>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Proceedings Report: {{ $conference->title }}</h2>
                    <div>
                        <a href="{{ route('reports.download.proceedings', $conference) }}" class="btn btn-success">
                            <i class="bi bi-download"></i> Download as PDF
                        </a>
                        <a href="{{ route('reports.index', $conference) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Reports
                        </a>
                    </div>
                </div>

                @if($proceedings)
                    <!-- Proceedings Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>Proceedings Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted">Publication Details</h6>
                                    <p><strong>Title:</strong> {{ $proceedings->title }}</p>
                                    <p><strong>Publication Date:</strong> {{ $proceedings->publication_date ? $proceedings->publication_date->format('F j, Y') : 'Not set' }}</p>
                                    <p><strong>ISBN:</strong> {{ $proceedings->isbn ?: 'Not assigned' }}</p>
                                    <p><strong>ISSN:</strong> {{ $proceedings->issn ?: 'Not assigned' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">Statistics</h6>
                                    <p><strong>Total Papers:</strong> {{ $stats['total_papers'] }}</p>
                                    <p><strong>Total Pages:</strong> {{ $stats['total_pages'] }}</p>
                                    <p><strong>Average Pages per Paper:</strong> {{ $stats['total_papers'] > 0 ? number_format($stats['total_pages'] / $stats['total_papers'], 1) : 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Published Papers -->
                    <div class="card">
                        <div class="card-header">
                            <h4>Published Papers ({{ $papers->count() }})</h4>
                        </div>
                        <div class="card-body">
                            @if($papers->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Title</th>
                                                <th>Authors</th>
                                                <th>Pages</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($papers as $index => $paper)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <strong>{{ $paper->title }}</strong>
                                                        @if($paper->abstract)
                                                            <br><small class="text-muted">{{ Str::limit($paper->abstract, 100) }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($paper->authors->count() > 0)
                                                            {{ $paper->authors->pluck('name')->implode(', ') }}
                                                        @else
                                                            {{ $paper->user ? $paper->user->name : 'Unknown' }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $paper->pages ?: 'Not set' }}</td>
                                                    <td>
                                                        <span class="badge bg-success">Published</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-journal-x fs-1 text-muted"></i>
                                    <p class="text-muted mt-2">No papers have been published in the proceedings yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-journal-x fs-1 text-muted"></i>
                            <h4 class="text-muted mt-3">No Proceedings Created</h4>
                            <p class="text-muted">Proceedings have not been created for this conference yet.</p>
                            <a href="{{ route('proceedings.create', $conference) }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Create Proceedings
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> 