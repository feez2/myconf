<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Submit Revision - {{ $paper->title }}</h4>
                        <p class="mb-0 text-muted">Conference: {{ $paper->conference->title }}</p>
                        <div class="alert alert-warning mt-2 mb-0">
                            <strong>Revision Required:</strong> Please revise your paper according to the decision notes below and resubmit. After submission, your paper will be reviewed for a new decision.
                        </div>
                    </div>
                    <div class="card-body">
                        @if($paper->decision_notes)
                            <div class="mb-4">
                                <h5>Decision Notes</h5>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        {!! nl2br(e($paper->decision_notes)) !!}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('papers.submit-revision', $paper) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="title" class="form-label">Paper Title *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title', $paper->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="abstract" class="form-label">Abstract *</label>
                                <textarea class="form-control @error('abstract') is-invalid @enderror"
                                          id="abstract" name="abstract" rows="6" required>{{ old('abstract', $paper->abstract) }}</textarea>
                                <small class="text-muted">Minimum 100 characters</small>
                                @error('abstract')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="keywords" class="form-label">Keywords *</label>
                                <input type="text" class="form-control @error('keywords') is-invalid @enderror"
                                       id="keywords" name="keywords" value="{{ old('keywords', $paper->keywords) }}" required>
                                <small class="text-muted">Comma-separated list (e.g., machine learning, data mining, AI)</small>
                                @error('keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="paper_file" class="form-label">Revised Paper File *</label>
                                <input type="file" class="form-control @error('paper_file') is-invalid @enderror"
                                       id="paper_file" name="paper_file" required>
                                <small class="text-muted">
                                    <!-- Current file:
                                    <a href="{{ Storage::url($paper->file_path) }}" target="_blank">
                                        {{ basename($paper->file_path) }}
                                    </a>
                                    <br> -->
                                    PDF or Word document (max 20MB)
                                </small>
                                @error('paper_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="revision_summary" class="form-label">Revision Summary *</label>
                                <textarea class="form-control @error('revision_summary') is-invalid @enderror"
                                          id="revision_summary" name="revision_summary" rows="4" required
                                          placeholder="Please provide a summary of the changes made in this revision...">{{ old('revision_summary') }}</textarea>
                                <small class="text-muted">Briefly describe the key changes and improvements made in this revision.</small>
                                @error('revision_summary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="{{ route('papers.index') }}" class="btn btn-secondary me-md-2">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-upload me-1"></i> Submit Revision
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
