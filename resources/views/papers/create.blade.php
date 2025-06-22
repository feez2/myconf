<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Submit Paper to {{ $conference->title }}</h4>
                        <p class="mb-0 text-muted">Submission Deadline: {{ $conference->submission_deadline->format('F j, Y') }}</p>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('papers.store', $conference) }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="title" class="form-label">Paper Title *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Authors *</label>
                                <div id="authors-container">
                                    <div class="author-entry mb-3">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <input type="text" class="form-control @error('authors.0.name') is-invalid @enderror"
                                                       name="authors[0][name]" placeholder="Author Name" value="{{ auth()->user()->name }}" readonly required>
                                                @error('authors.0.name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-check mt-2">
                                                    <input type="checkbox" class="form-check-input" name="authors[0][is_corresponding]" value="1" checked>
                                                    <label class="form-check-label">Corresponding</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm mt-2 d-flex align-items-center gap-1" id="add-author">
                                    <i class="bi bi-person-plus-fill"></i>
                                    <span>Add Another Author</span>
                                </button>

                            </div>

                            <div class="mb-3">
                                <label for="abstract" class="form-label">Abstract *</label>
                                <textarea class="form-control @error('abstract') is-invalid @enderror"
                                          id="abstract" name="abstract" rows="6" required>{{ old('abstract') }}</textarea>
                                <small class="text-muted">Minimum 100 characters</small>
                                @error('abstract')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="keywords" class="form-label">Keywords *</label>
                                <input type="text" class="form-control @error('keywords') is-invalid @enderror"
                                       id="keywords" name="keywords" value="{{ old('keywords') }}" required>
                                <small class="text-muted">Comma-separated list (e.g., machine learning, data mining, AI)</small>
                                @error('keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="paper_file" class="form-label">Paper File *</label>
                                <input type="file" class="form-control @error('paper_file') is-invalid @enderror"
                                       id="paper_file" name="paper_file" required>
                                <small class="text-muted">PDF or Word document (max 20MB)</small>
                                @error('paper_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="{{ route('conferences.show', $conference) }}" class="btn btn-secondary me-md-2">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Submit Paper
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const authorsContainer = document.getElementById('authors-container');
            const addAuthorBtn = document.getElementById('add-author');
            let authorCount = 1;

            // Function to create a new author entry
            function createAuthorEntry(index) {
                const authorEntry = document.createElement('div');
                authorEntry.className = 'author-entry mb-3';
                authorEntry.innerHTML = `
                    <div class="row mb-2 align-items-center author-entry">
                        <div class="col-md-8">
                            <input type="text" class="form-control"
                                name="authors[${index}][name]" placeholder="Author Name" required>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check mb-0">
                                    <input type="checkbox" class="form-check-input" name="authors[${index}][is_corresponding]" value="1">
                                    <label class="form-check-label">Corresponding</label>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm remove-author">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                // Add event listener to the remove button
                authorEntry.querySelector('.remove-author').addEventListener('click', function() {
                    authorEntry.remove();
                });

                return authorEntry;
            }

            // Add author button click handler
            addAuthorBtn.addEventListener('click', function() {
                const authorEntry = createAuthorEntry(authorCount);
                authorsContainer.appendChild(authorEntry);
                authorCount++;
            });

            // Handle corresponding author checkbox changes
            authorsContainer.addEventListener('change', function(e) {
                if (e.target.type === 'checkbox' && e.target.name.includes('[is_corresponding]')) {
                    const checkboxes = authorsContainer.querySelectorAll('input[name$="[is_corresponding]"]');
                    checkboxes.forEach(checkbox => {
                        if (checkbox !== e.target) {
                            checkbox.checked = false;
                        }
                    });
                }
            });
        });
    </script>

</x-app-layout>
