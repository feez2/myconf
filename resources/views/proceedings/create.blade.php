<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Create Proceedings for {{ $conference->title }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('proceedings.store', $conference) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title', $conference->title . ' Proceedings') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="isbn" class="form-label">ISBN</label>
                                    <input type="text" class="form-control @error('isbn') is-invalid @enderror"
                                           id="isbn" name="isbn" value="{{ old('isbn') }}">
                                    @error('isbn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="issn" class="form-label">ISSN</label>
                                    <input type="text" class="form-control @error('issn') is-invalid @enderror"
                                           id="issn" name="issn" value="{{ old('issn') }}">
                                    @error('issn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="publisher" class="form-label">Publisher</label>
                                <input type="text" class="form-control @error('publisher') is-invalid @enderror"
                                       id="publisher" name="publisher" value="{{ old('publisher') }}">
                                @error('publisher')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="publication_date" class="form-label">Publication Date</label>
                                <input type="date" class="form-control @error('publication_date') is-invalid @enderror"
                                       id="publication_date" name="publication_date" value="{{ old('publication_date') }}">
                                @error('publication_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="front_matter" class="form-label">Front Matter (PDF)</label>
                                <input type="file" class="form-control @error('front_matter') is-invalid @enderror"
                                       id="front_matter" name="front_matter" accept=".pdf">
                                <div class="form-text">Upload a PDF file for the front matter (e.g., preface, table of contents)</div>
                                @error('front_matter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="back_matter" class="form-label">Back Matter (PDF)</label>
                                <input type="file" class="form-control @error('back_matter') is-invalid @enderror"
                                       id="back_matter" name="back_matter" accept=".pdf">
                                <div class="form-text">Upload a PDF file for the back matter (e.g., index, appendices)</div>
                                @error('back_matter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="cover_image" class="form-label">Cover Image</label>
                                <input type="file" class="form-control @error('cover_image') is-invalid @enderror"
                                       id="cover_image" name="cover_image" accept="image/jpeg,image/png">
                                <div class="form-text">Upload a cover image (JPEG or PNG, max 2MB)</div>
                                @error('cover_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('proceedings.index', $conference) }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Create Proceedings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
