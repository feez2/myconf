<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Paper: {{ $paper->title }}</h4>
                        <p class="mb-0 text-muted">Submitted to {{ $paper->conference->title }}</p>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('papers.update', $paper) }}" enctype="multipart/form-data">
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
                                <small class="text-muted">Minimum 500 characters</small>
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
                                <label for="paper_file" class="form-label">Paper File</label>
                                <input type="file" class="form-control @error('paper_file') is-invalid @enderror"
                                       id="paper_file" name="paper_file">
                                <small class="text-muted">Current file:
                                    <a href="{{ Storage::url($paper->file_path) }}" target="_blank">
                                        {{ basename($paper->file_path) }}
                                    </a>
                                </small>
                                @error('paper_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="{{ route('papers.show', $paper) }}" class="btn btn-secondary me-md-2">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Update Paper
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
