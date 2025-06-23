<x-app-layout>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>My Submitted Papers</h2>
            <a href="{{ route('conferences.index') }}" class="btn btn-primary">
                <i class="bi bi-upload me-1"></i> Submit to New Conference
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <!-- Filters -->
                <form action="{{ route('papers.index') }}" method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                @foreach($statusOptions as $key => $label)
                                    <option value="{{ $key }}" {{ request('status', $status ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control"
                                   placeholder="Search by title or conference acronym..." value="{{ request('search', $search ?? '') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100 shadow-sm">
                                <i class="bi bi-funnel-fill me-1"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>

                @if($papers->isEmpty())
                    <div class="alert alert-info d-flex align-items-center justify-content-center gap-2 mt-4" role="alert">
                        <i class="bi bi-info-circle-fill fs-4 text-info"></i>
                        <div>
                            <strong>No papers found.</strong>
                        </div>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Conference</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($papers as $paper)
                                    <tr>
                                        <td>{{ $paper->title }}</td>
                                        <td>{{ $paper->conference->acronym }}</td>
                                        <td>
                                            <span class="badge
                                                @if($paper->status === 'submitted') bg-primary
                                                @elseif($paper->status === 'under_review') bg-warning text-dark
                                                @elseif($paper->status === 'accepted') bg-success
                                                @else bg-danger
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $paper->status)) }}
                                            </span>
                                        </td>
                                        <td>{{ $paper->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('papers.show', $paper) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                            @if($paper->status === 'submitted' && isset($paper->conference->submission_deadline) && now()->lte($paper->conference->submission_deadline) && auth()->user()->can('update', $paper))
                                                <a href="{{ route('papers.edit', $paper) }}" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                            @endif
                                            @if($paper->status === 'accepted')
                                                @if(!$paper->camera_ready_file)
                                                    <a href="{{ route('papers.showCameraReadyForm', $paper) }}" class="btn btn-sm btn-success">
                                                        <i class="bi bi-upload"></i> Camera Ready
                                                    </a>
                                                @else
                                                    <span class="badge bg-success">Camera Ready Submitted</span>
                                                @endif
                                            @endif
                                            @if($paper->status === 'revision_required')
                                                <a href="{{ route('papers.revision', $paper) }}" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil-square"></i> Revision
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $papers->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
