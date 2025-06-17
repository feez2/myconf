<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Edit Program Book - {{ $programBook->title }}</h4>
                        <a href="{{ route('program-book.index', $programBook->conference) }}" class="btn btn-secondary">
                            Back to Program Book
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('program-book.update', $programBook) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="title" class="form-label">Program Book Title</label>
                                <input type="text" name="title" id="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title', $programBook->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" name="date" id="date"
                                    class="form-control @error('date') is-invalid @enderror"
                                    value="{{ old('date', $programBook->date->format('Y-m-d')) }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="welcome_message" class="form-label">Welcome Message</label>
                                <textarea name="welcome_message" id="welcome_message" rows="3"
                                    class="form-control @error('welcome_message') is-invalid @enderror">{{ old('welcome_message', $programBook->welcome_message) }}</textarea>
                                @error('welcome_message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="general_information" class="form-label">General Information</label>
                                <textarea name="general_information" id="general_information" rows="3"
                                    class="form-control @error('general_information') is-invalid @enderror">{{ old('general_information', $programBook->general_information) }}</textarea>
                                @error('general_information')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="cover_image" class="form-label">Cover Image</label>
                                <input type="file" name="cover_image" id="cover_image"
                                    class="form-control @error('cover_image') is-invalid @enderror">
                                @error('cover_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($programBook->cover_image_path)
                                    <div class="mt-2">
                                        <p>Current Cover Image:</p>
                                        <img src="{{ Storage::url($programBook->cover_image_path) }}"
                                            alt="Current cover image" class="img-thumbnail" style="max-height: 200px;">
                                    </div>
                                @endif
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Update Program Book</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
