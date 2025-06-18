<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Manage Sessions - {{ $programBook->title }}</h4>
                        <a href="{{ route('program-book.index', $programBook->conference) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Program Book
                        </a>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5>Add New Session</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('program-book.store-session', $programBook) }}" method="POST">
                                            @csrf

                                            <div class="mb-3">
                                                <label for="title" class="form-label">Session Title</label>
                                                <input type="text" name="title" id="title" class="form-control" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="type" class="form-label">Session Type</label>
                                                <select name="type" id="type" class="form-select">
                                                    <option value="regular">Regular Session</option>
                                                    <option value="keynote">Keynote</option>
                                                    <option value="workshop">Workshop</option>
                                                    <option value="panel">Panel Discussion</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="description" class="form-label">Description (optional)</label>
                                                <textarea name="description" id="description" rows="3" class="form-control"></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label for="date" class="form-label">Date</label>
                                                <input type="date" name="date" id="date" class="form-control" 
                                                    min="{{ $programBook->start_date->format('Y-m-d') }}"
                                                    max="{{ $programBook->end_date->format('Y-m-d') }}" required>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="start_time" class="form-label">Start Time</label>
                                                    <input type="time" name="start_time" id="start_time" class="form-control" required>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="end_time" class="form-label">End Time</label>
                                                    <input type="time" name="end_time" id="end_time" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="location" class="form-label">Location</label>
                                                <input type="text" name="location" id="location" class="form-control" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="session_chair" class="form-label">Session Chair (optional)</label>
                                                <input type="text" name="session_chair" id="session_chair" class="form-control">
                                            </div>

                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Add Session</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Sessions</h5>
                                    </div>
                                    <div class="card-body">
                                        @if($sessions->isEmpty())
                                            <div class="alert alert-info">
                                                No sessions added yet.
                                            </div>
                                        @else
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Title</th>
                                                            <th>Time</th>
                                                            <th>Location</th>
                                                            <th>Presentations</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($sessions as $session)
                                                            <tr>
                                                                <td>
                                                                    <strong>{{ $session->title }}</strong>
                                                                    <br>
                                                                    <small class="text-muted">{{ ucfirst($session->type) }}</small>
                                                                </td>
                                                                <td>
                                                                    {{ $session->date->format('M j, Y') }}<br>
                                                                    {{ $session->start_time->format('h:i A') }} - {{ $session->end_time->format('h:i A') }}
                                                                </td>
                                                                <td>{{ $session->location }}</td>
                                                                <td>{{ $session->presentations->count() }}</td>
                                                                <td>
                                                                    <div class="d-flex gap-2 flex-wrap">
                                                                        <button type="button"
                                                                                class="btn btn-sm btn-primary"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#editSessionModal{{ $session->id }}"
                                                                                title="Edit Session">
                                                                            <i class="bi bi-pencil-square"></i> Edit
                                                                        </button>

                                                                        <button type="button"
                                                                                class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                                                data-bs-target="#addPresentationModal{{ $session->id }}"
                                                                                title="Add Presentation">
                                                                            <i class="bi bi-plus-square"></i> Add Presentation
                                                                        </button>

                                                                        <form action="{{ route('program-book.delete-session', $session) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this session?')">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete Session">
                                                                                <i class="bi bi-trash3"></i> Delete
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            <!-- Presentations List -->
                                                            @if($session->presentations->isNotEmpty())
                                                                <tr>
                                                                    <td colspan="5">
                                                                        <div class="ps-4">
                                                                            <h6>Presentations:</h6>
                                                                            <div class="table-responsive">
                                                                                <table class="table table-sm">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Title</th>
                                                                                            <th>Time</th>
                                                                                            <th>Speaker</th>
                                                                                            <th>Actions</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @foreach($session->presentations as $presentation)
                                                                                            <tr>
                                                                                                <td>{{ $presentation->title }}</td>
                                                                                                <td>
                                                                                                    {{ $presentation->start_time->format('h:i A') }} -
                                                                                                    {{ $presentation->end_time->format('h:i A') }}
                                                                                                </td>
                                                                                                <td>{{ $presentation->speaker_name }}</td>
                                                                                                <td>
                                                                                                    <div class="d-flex gap-2 flex-wrap">
                                                                                                        <!-- Edit Button -->
                                                                                                        <button type="button"
                                                                                                                class="btn btn-sm btn-primary"
                                                                                                                data-bs-toggle="modal"
                                                                                                                data-bs-placement="top"
                                                                                                                title="Edit Presentation"
                                                                                                                data-bs-target="#editPresentationModal{{ $presentation->id }}"
                                                                                                                data-bs-toggle="modal">
                                                                                                            <i class="bi bi-pencil-square"></i> Edit
                                                                                                        </button>

                                                                                                        <!-- Delete Form -->
                                                                                                        <form action="{{ route('program-book.delete-presentation', $presentation) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this presentation?')">
                                                                                                            @csrf
                                                                                                            @method('DELETE')
                                                                                                            <button type="submit"
                                                                                                                    class="btn btn-sm btn-danger"
                                                                                                                    onclick="return confirm('Are you sure you want to delete this presentation?')">
                                                                                                                <i class="bi bi-trash"></i> Delete
                                                                                                            </button>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Move all modals to root level -->
    @foreach($sessions as $session)
        <!-- Edit Session Modal -->
        <div class="modal fade" id="editSessionModal{{ $session->id }}" tabindex="-1"
            aria-labelledby="editSessionModalLabel{{ $session->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSessionModalLabel{{ $session->id }}">Edit Session</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('program-book.update-session', $session) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit_title{{ $session->id }}" class="form-label">Session Title</label>
                                <input type="text" name="title" id="edit_title{{ $session->id }}"
                                    class="form-control" value="{{ $session->title }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_type{{ $session->id }}" class="form-label">Session Type</label>
                                <select name="type" id="edit_type{{ $session->id }}" class="form-select">
                                    <option value="regular" {{ $session->type === 'regular' ? 'selected' : '' }}>Regular Session</option>
                                    <option value="keynote" {{ $session->type === 'keynote' ? 'selected' : '' }}>Keynote</option>
                                    <option value="workshop" {{ $session->type === 'workshop' ? 'selected' : '' }}>Workshop</option>
                                    <option value="panel" {{ $session->type === 'panel' ? 'selected' : '' }}>Panel Discussion</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_description{{ $session->id }}" class="form-label">Description (optional)</label>
                                <textarea name="description" id="edit_description{{ $session->id }}"
                                    rows="3" class="form-control">{{ $session->description }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="edit_date{{ $session->id }}" class="form-label">Date</label>
                                <input type="date" name="date" id="edit_date{{ $session->id }}"
                                    class="form-control" value="{{ $session->date->format('Y-m-d') }}" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="edit_start_time{{ $session->id }}" class="form-label">Start Time</label>
                                    <input type="time" name="start_time" id="edit_start_time{{ $session->id }}"
                                        class="form-control" value="{{ $session->start_time->format('H:i') }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="edit_end_time{{ $session->id }}" class="form-label">End Time</label>
                                    <input type="time" name="end_time" id="edit_end_time{{ $session->id }}"
                                        class="form-control" value="{{ $session->end_time->format('H:i') }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="edit_location{{ $session->id }}" class="form-label">Location</label>
                                <input type="text" name="location" id="edit_location{{ $session->id }}"
                                    class="form-control" value="{{ $session->location }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_session_chair{{ $session->id }}" class="form-label">Session Chair (optional)</label>
                                <input type="text" name="session_chair" id="edit_session_chair{{ $session->id }}"
                                    class="form-control" value="{{ $session->session_chair }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Presentation Modal -->
        <div class="modal fade" id="addPresentationModal{{ $session->id }}" tabindex="-1"
            aria-labelledby="addPresentationModalLabel{{ $session->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPresentationModalLabel{{ $session->id }}">Add Presentation to {{ $session->title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('program-book.store-presentation', $session) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="paper_id{{ $session->id }}" class="form-label">Link to Paper (optional)</label>
                                <select name="paper_id" id="paper_id{{ $session->id }}" class="form-select">
                                    <option value="">-- Select Paper --</option>
                                    @foreach($acceptedPapers as $paper)
                                        <option value="{{ $paper->id }}">{{ $paper->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="presentation_title{{ $session->id }}" class="form-label">Presentation Title</label>
                                <input type="text" name="title" id="presentation_title{{ $session->id }}"
                                    class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="presentation_abstract{{ $session->id }}" class="form-label">Abstract (optional)</label>
                                <textarea name="abstract" id="presentation_abstract{{ $session->id }}"
                                    rows="3" class="form-control"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="presentation_start_time{{ $session->id }}" class="form-label">Start Time</label>
                                    <input type="time" name="start_time" id="presentation_start_time{{ $session->id }}"
                                        class="form-control" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="presentation_end_time{{ $session->id }}" class="form-label">End Time</label>
                                    <input type="time" name="end_time" id="presentation_end_time{{ $session->id }}"
                                        class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="speaker_name{{ $session->id }}" class="form-label">Speaker Name</label>
                                <input type="text" name="speaker_name" id="speaker_name{{ $session->id }}"
                                    class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="speaker_affiliation{{ $session->id }}" class="form-label">Speaker Affiliation (optional)</label>
                                <input type="text" name="speaker_affiliation" id="speaker_affiliation{{ $session->id }}"
                                    class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="speaker_bio{{ $session->id }}" class="form-label">Speaker Bio (optional)</label>
                                <textarea name="speaker_bio" id="speaker_bio{{ $session->id }}"
                                    rows="2" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="speaker_photo{{ $session->id }}" class="form-label">Speaker Photo (optional)</label>
                                <input type="file" name="speaker_photo" id="speaker_photo{{ $session->id }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Presentation</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @foreach($session->presentations as $presentation)
            <!-- Edit Presentation Modal -->
            <div class="modal fade" id="editPresentationModal{{ $presentation->id }}" tabindex="-1"
                aria-labelledby="editPresentationModalLabel{{ $presentation->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editPresentationModalLabel{{ $presentation->id }}">Edit Presentation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('program-book.update-presentation', $presentation) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="edit_paper_id{{ $presentation->id }}" class="form-label">Link to Paper (optional)</label>
                                    <select name="paper_id" id="edit_paper_id{{ $presentation->id }}" class="form-select">
                                        <option value="">-- Select Paper --</option>
                                        @foreach($acceptedPapers as $paper)
                                            <option value="{{ $paper->id }}" {{ $presentation->paper_id == $paper->id ? 'selected' : '' }}>
                                                {{ $paper->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_presentation_title{{ $presentation->id }}" class="form-label">Presentation Title</label>
                                    <input type="text" name="title" id="edit_presentation_title{{ $presentation->id }}"
                                        class="form-control" value="{{ $presentation->title }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_presentation_abstract{{ $presentation->id }}" class="form-label">Abstract (optional)</label>
                                    <textarea name="abstract" id="edit_presentation_abstract{{ $presentation->id }}"
                                        rows="3" class="form-control">{{ $presentation->abstract }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_presentation_start_time{{ $presentation->id }}" class="form-label">Start Time</label>
                                        <input type="time" name="start_time" id="edit_presentation_start_time{{ $presentation->id }}"
                                            class="form-control" value="{{ $presentation->start_time->format('H:i') }}" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="edit_presentation_end_time{{ $presentation->id }}" class="form-label">End Time</label>
                                        <input type="time" name="end_time" id="edit_presentation_end_time{{ $presentation->id }}"
                                            class="form-control" value="{{ $presentation->end_time->format('H:i') }}" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_speaker_name{{ $presentation->id }}" class="form-label">Speaker Name</label>
                                    <input type="text" name="speaker_name" id="edit_speaker_name{{ $presentation->id }}"
                                        class="form-control" value="{{ $presentation->speaker_name }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_speaker_affiliation{{ $presentation->id }}" class="form-label">Speaker Affiliation (optional)</label>
                                    <input type="text" name="speaker_affiliation" id="edit_speaker_affiliation{{ $presentation->id }}"
                                        class="form-control" value="{{ $presentation->speaker_affiliation }}">
                                </div>

                                <div class="mb-3">
                                    <label for="edit_speaker_bio{{ $presentation->id }}" class="form-label">Speaker Bio (optional)</label>
                                    <textarea name="speaker_bio" id="edit_speaker_bio{{ $presentation->id }}"
                                        rows="2" class="form-control">{{ $presentation->speaker_bio }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_speaker_photo{{ $presentation->id }}" class="form-label">Speaker Photo (optional)</label>
                                    <input type="file" name="speaker_photo" id="edit_speaker_photo{{ $presentation->id }}"
                                        class="form-control">
                                    @if($presentation->speaker_photo_path)
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($presentation->speaker_photo_path) }}" alt="Speaker photo" class="img-thumbnail" style="max-height: 100px;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Session time validation
        function setupSessionTimeValidation() {
            const sessionStartInputs = document.querySelectorAll('input[name="start_time"]');
            const sessionEndInputs = document.querySelectorAll('input[name="end_time"]');

            sessionStartInputs.forEach((startInput, index) => {
                const endInput = sessionEndInputs[index];
                
                startInput.addEventListener('change', function() {
                    endInput.min = this.value;
                    if (endInput.value && endInput.value <= this.value) {
                        endInput.value = '';
                    }
                });

                endInput.addEventListener('change', function() {
                    startInput.max = this.value;
                    if (startInput.value && startInput.value >= this.value) {
                        startInput.value = '';
                    }
                });
            });
        }

        // Presentation time validation
        function setupPresentationTimeValidation() {
            const presentationStartInputs = document.querySelectorAll('input[name="start_time"]');
            const presentationEndInputs = document.querySelectorAll('input[name="end_time"]');

            presentationStartInputs.forEach((startInput, index) => {
                const endInput = presentationEndInputs[index];
                
                startInput.addEventListener('change', function() {
                    endInput.min = this.value;
                    if (endInput.value && endInput.value <= this.value) {
                        endInput.value = '';
                    }
                });

                endInput.addEventListener('change', function() {
                    startInput.max = this.value;
                    if (startInput.value && startInput.value >= this.value) {
                        startInput.value = '';
                    }
                });
            });
        }

        // Initialize validation
        setupSessionTimeValidation();
        setupPresentationTimeValidation();

        // Re-initialize validation when modals are shown
        document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
            button.addEventListener('click', function() {
                setTimeout(() => {
                    setupSessionTimeValidation();
                    setupPresentationTimeValidation();
                }, 100);
            });
        });
    });
</script>
