<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Submit Camera-Ready Version</h4>
                        <p class="mb-0">For: {{ $paper->title }}</p>
                    </div>
                    <div class="card-body">
                        @if($paper->conference->camera_ready_deadline && now()->gt($paper->conference->camera_ready_deadline))
                            <div class="alert alert-warning">
                                <strong>Note:</strong> The camera-ready deadline has passed. Late submissions may not be included in the proceedings.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('papers.submit-camera-ready', $paper) }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="camera_ready_file" class="form-label">Camera-Ready Paper *</label>
                                <input type="file" class="form-control @error('camera_ready_file') is-invalid @enderror"
                                       id="camera_ready_file" name="camera_ready_file" required>
                                @error('camera_ready_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    PDF or Word document (max 10MB). Please ensure it follows the conference template.
                                </small>
                            </div>

                            <div class="mb-3">
                                <label for="copyright_form" class="form-label">Copyright Transfer Form *</label>
                                <input type="file" class="form-control @error('copyright_form') is-invalid @enderror"
                                       id="copyright_form" name="copyright_form" required>
                                @error('copyright_form')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Signed copyright form (PDF, max 2MB).
                                    <a href="#" target="_blank">Download form template</a>
                                </small>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('papers.show', $paper) }}" class="btn btn-secondary me-md-2">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Submit Camera-Ready Version
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
