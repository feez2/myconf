<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Proceedings for {{ $proceedings->conference->title }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('proceedings.update', $proceedings) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title', $proceedings->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="isbn" class="form-label">ISBN</label>
                                    <input type="text" class="form-control @error('isbn') is-invalid @enderror"
                                           id="isbn" name="isbn" value="{{ old('isbn', $proceedings->isbn) }}">
                                    @error('isbn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="issn" class="form-label">ISSN</label>
                                    <input type="text" class="form-control @error('issn') is-invalid @enderror"
                                           id="issn" name="issn" value="{{ old('issn', $proceedings->issn) }}">
                                    @error('issn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="publisher" class="form-label">Publisher</label>
                                <input type="text" class="form-control @error('publisher') is-invalid @enderror"
                                       id="publisher" name="publisher" value="{{ old('publisher', $proceedings->publisher) }}">
                                @error('publisher')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="publication_date" class="form-label">Publication Date</label>
                                <input type="date" class="form-control @error('publication_date') is-invalid @enderror"
                                       id="publication_date" name="publication_date"
                                       value="{{ old('publication_date', $proceedings->publication_date?->format('Y-m-d')) }}">
                                @error('publication_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror"
                                        id="status" name="status" required>
                                    @foreach(['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'] as $value => $label)
                                        <option value="{{ $value }}" {{ old('status', $proceedings->status) === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="front_matter" class="form-label">Front Matter (PDF)</label>
                                @if($proceedings->front_matter_file)
                                    <div class="mb-2">
                                        <a href="{{ Storage::url($proceedings->front_matter_file) }}"
                                           class="btn btn-sm btn-outline-primary" target="_blank">
                                            View Current Front Matter
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('front_matter') is-invalid @enderror"
                                       id="front_matter" name="front_matter" accept=".pdf">
                                <div class="form-text">Upload a new PDF file to replace the current front matter</div>
                                @error('front_matter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="back_matter" class="form-label">Back Matter (PDF)</label>
                                @if($proceedings->back_matter_file)
                                    <div class="mb-2">
                                        <a href="{{ Storage::url($proceedings->back_matter_file) }}"
                                           class="btn btn-sm btn-outline-primary" target="_blank">
                                            View Current Back Matter
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('back_matter') is-invalid @enderror"
                                       id="back_matter" name="back_matter" accept=".pdf">
                                <div class="form-text">Upload a new PDF file to replace the current back matter</div>
                                @error('back_matter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="cover_image" class="form-label">Cover Image</label>
                                @if($proceedings->cover_image)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($proceedings->cover_image) }}"
                                             alt="Current cover" class="img-thumbnail" style="max-height: 200px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('cover_image') is-invalid @enderror"
                                       id="cover_image" name="cover_image" accept="image/jpeg,image/png">
                                <div class="form-text">Upload a new cover image to replace the current one (JPEG or PNG, max 2MB)</div>
                                @error('cover_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('proceedings.show', $proceedings) }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Update Proceedings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
