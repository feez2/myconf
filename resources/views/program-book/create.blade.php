<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Create Program Book - {{ $conference->title }}</h4>
                        <a href="{{ route('program-book.index', $conference) }}" class="btn btn-secondary">
                            Back
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('program-book.store', $conference) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="title" class="form-label">Program Book Title</label>
                                <input type="text" name="title" id="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" name="date" id="date"
                                    class="form-control @error('date') is-invalid @enderror"
                                    value="{{ old('date') }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="welcome_message" class="form-label">Welcome Message (optional)</label>
                                <textarea name="welcome_message" id="welcome_message" rows="3"
                                    class="form-control @error('welcome_message') is-invalid @enderror">{{ old('welcome_message') }}</textarea>
                                @error('welcome_message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="general_information" class="form-label">General Information (optional)</label>
                                <textarea name="general_information" id="general_information" rows="3"
                                    class="form-control @error('general_information') is-invalid @enderror">{{ old('general_information') }}</textarea>
                                @error('general_information')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="cover_image" class="form-label">Cover Image (optional)</label>
                                <input type="file" name="cover_image" id="cover_image"
                                    class="form-control @error('cover_image') is-invalid @enderror">
                                @error('cover_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Recommended size: 1200x1600 pixels, max 2MB
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Create Program Book</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
