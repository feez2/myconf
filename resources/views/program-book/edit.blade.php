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

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label">Start Date</label>
                                        <input type="date" name="start_date" id="start_date"
                                            class="form-control @error('start_date') is-invalid @enderror"
                                            value="{{ old('start_date', $programBook->start_date->format('Y-m-d')) }}"
                                            min="{{ $programBook->conference->start_date->format('Y-m-d') }}"
                                            max="{{ $programBook->conference->end_date->format('Y-m-d') }}" required>
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label">End Date</label>
                                        <input type="date" name="end_date" id="end_date"
                                            class="form-control @error('end_date') is-invalid @enderror"
                                            value="{{ old('end_date', $programBook->end_date->format('Y-m-d')) }}"
                                            min="{{ $programBook->conference->start_date->format('Y-m-d') }}"
                                            max="{{ $programBook->conference->end_date->format('Y-m-d') }}" required>
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            startDateInput.addEventListener('change', function() {
                endDateInput.min = this.value;
                if (endDateInput.value && endDateInput.value < this.value) {
                    endDateInput.value = this.value;
                }
            });

            endDateInput.addEventListener('change', function() {
                startDateInput.max = this.value;
                if (startDateInput.value && startDateInput.value > this.value) {
                    startDateInput.value = this.value;
                }
            });
        });
    </script>
</x-app-layout>
