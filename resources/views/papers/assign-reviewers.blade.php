<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Assign Reviewers for: {{ $paper->title }}</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('papers.assign-reviewers', $paper) }}">
                            @csrf

                            <div class="mb-4">
                                <h5>Available Reviewers</h5>
                                <p class="text-muted">Select 2-3 reviewers for this paper</p>

                                <div class="list-group">
                                    @foreach($reviewers as $reviewer)
                                        <label class="list-group-item">
                                            <input class="form-check-input me-1"
                                                   type="checkbox"
                                                   name="reviewer_ids[]"
                                                   value="{{ $reviewer->id }}">
                                            {{ $reviewer->name }} ({{ $reviewer->email }})
                                            <span class="badge bg-secondary float-end">
                                                {{ $reviewer->pivot->role }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('papers.show', $paper) }}" class="btn btn-secondary me-md-2">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Assign Reviewers
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
