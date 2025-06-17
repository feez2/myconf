<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Conference: {{ $conference->title }}</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('conferences.update', $conference) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="title" class="form-label">Conference Title *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title', $conference->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="acronym" class="form-label">Acronym *</label>
                                <input type="text" class="form-control @error('acronym') is-invalid @enderror"
                                       id="acronym" name="acronym" value="{{ old('acronym', $conference->acronym) }}" required>
                                @error('acronym')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="3" required>{{ old('description', $conference->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="location" class="form-label">Location *</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror"
                                       id="location" name="location" value="{{ old('location', $conference->location) }}" required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label">Start Date *</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                           id="start_date" name="start_date"
                                           value="{{ old('start_date', $conference->start_date->format('Y-m-d')) }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label">End Date *</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                           id="end_date" name="end_date"
                                           value="{{ old('end_date', $conference->end_date->format('Y-m-d')) }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="submission_deadline" class="form-label">Submission Deadline *</label>
                                    <input type="date" class="form-control @error('submission_deadline') is-invalid @enderror"
                                        id="submission_deadline" name="submission_deadline" 
                                        value="{{ old('submission_deadline', $conference->submission_deadline ? $conference->submission_deadline->format('Y-m-d') : '') }}" required>
                                    @error('submission_deadline')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="review_deadline" class="form-label">Review Deadline</label>
                                    <input type="date" class="form-control @error('review_deadline') is-invalid @enderror"
                                        id="review_deadline" name="review_deadline" 
                                        value="{{ old('review_deadline', $conference->review_deadline ? $conference->review_deadline->format('Y-m-d') : '') }}">
                                    @error('review_deadline')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="website" class="form-label">Website URL</label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror"
                                       id="website" name="website" value="{{ old('website', $conference->website) }}">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="logo" class="form-label">Conference Logo</label>
                                @if($conference->logo)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $conference->logo) }}" alt="Current logo"
                                             class="img-thumbnail" style="max-height: 100px;">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="remove_logo" name="remove_logo">
                                            <label class="form-check-label" for="remove_logo">
                                                Remove current logo
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                       id="logo" name="logo">
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Max 2MB (JPEG, PNG, JPG, GIF)</small>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('conferences.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Conference</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
