<x-app-layout>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Proceedings - {{ $conference->title }}</h2>
            @can('manageProceedings', $conference)
                <a href="{{ route('proceedings.create', $conference) }}" class="btn btn-primary">
                    Create New Proceedings
                </a>
            @endcan
        </div>

        <div class="card mb-4">
            <div class="card-body">
                @if(!$proceedings)
                    <div class="alert alert-info">
                        No proceedings have been created yet.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Papers</th>
                                    <th>Published</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $proceedings->title }}</td>
                                    <td>
                                        <span class="badge
                                            @if($proceedings->status === 'published') bg-success
                                            @elseif($proceedings->status === 'archived') bg-secondary
                                            @else bg-warning text-dark
                                            @endif">
                                            {{ $proceedings->status_name }}
                                        </span>
                                    </td>
                                    <td>{{ $includedPapers->count() }}</td>
                                    <td>{{ $proceedings->publication_date?->format('Y') ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('proceedings.show', $proceedings) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        @can('manageProceedings', $conference)
                                            <a href="{{ route('proceedings.edit', $proceedings) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        @if($proceedings)
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Papers in Proceedings</h5>
                            <span class="badge bg-primary">{{ $includedPapers->count() }}</span>
                        </div>
                        <div class="card-body">
                            @if($includedPapers->isEmpty())
                                <div class="alert alert-info">
                                    No papers have been added to these proceedings yet.
                                </div>
                            @else
                                <div class="list-group">
                                    @foreach($includedPapers as $paper)
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">{{ $paper->title }}</h6>
                                                    <small class="text-muted">by {{ $paper->author->name }}</small>
                                                </div>
                                                <form action="{{ route('proceedings.remove-paper', ['conference' => $conference, 'paper' => $paper]) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure you want to remove this paper from proceedings?')">
                                                        Remove
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Available Accepted Papers</h5>
                            <span class="badge bg-primary">{{ $acceptedPapers->count() }}</span>
                        </div>
                        <div class="card-body">
                            @if($acceptedPapers->isEmpty())
                                <div class="alert alert-info">
                                    No accepted papers available to add to proceedings.
                                </div>
                            @else
                                <form action="{{ route('proceedings.assign-papers', $conference) }}" method="POST">
                                    @csrf
                                    <div class="list-group mb-3">
                                        @foreach($acceptedPapers as $paper)
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="paper_ids[]" value="{{ $paper->id }}"
                                                           id="paper_{{ $paper->id }}">
                                                    <label class="form-check-label" for="paper_{{ $paper->id }}">
                                                        <div>
                                                            <h6 class="mb-1">{{ $paper->title }}</h6>
                                                            <small class="text-muted">by {{ $paper->author->name }}</small>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        Add Selected Papers to Proceedings
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
