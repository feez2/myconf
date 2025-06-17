<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Assign Papers to {{ $proceedings->title }}</h4>
                    </div>
                    <div class="card-body">
                        @if($papers->isEmpty())
                            <div class="alert alert-info">
                                No accepted papers available to assign to these proceedings.
                            </div>
                        @else
                            <form action="{{ route('proceedings.store-assigned-papers', $proceedings) }}" method="POST">
                                @csrf
                                <div class="list-group mb-4">
                                    @foreach($papers as $paper)
                                        <div class="list-group-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       name="paper_ids[]" value="{{ $paper->id }}"
                                                       id="paper_{{ $paper->id }}">
                                                <label class="form-check-label w-100" for="paper_{{ $paper->id }}">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="mb-1">{{ $paper->title }}</h6>
                                                            <small class="text-muted">
                                                                by {{ $paper->author->name }}
                                                                @if($paper->authors->isNotEmpty())
                                                                    and {{ $paper->authors->count() }} co-authors
                                                                @endif
                                                            </small>
                                                        </div>
                                                        <div>
                                                            @if($paper->camera_ready_file)
                                                                <span class="badge bg-success">Camera-ready submitted</span>
                                                            @else
                                                                <span class="badge bg-warning text-dark">No camera-ready version</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary">
                                        Add Selected Papers to Proceedings
                                    </button>
                                    <a href="{{ route('proceedings.show', $proceedings) }}" class="btn btn-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
