<x-app-layout>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Conference Management</h2>
            @can('admin')
                <a href="{{ route('conferences.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Create New Conference
                </a>
            @endcan
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <!-- Filters -->
                <form action="{{ route('conferences.index') }}" method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control"
                                   placeholder="Search by title or acronym..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100 shadow-sm">
                                <i class="bi bi-funnel-fill me-1"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>

                @if($conferences->isEmpty())
                    <div class="alert alert-info d-flex align-items-center justify-content-center gap-2 mt-4" role="alert">
                        <i class="bi bi-info-circle-fill fs-4 text-info"></i>
                        <div>
                            <strong>No conferences found.</strong>
                        </div>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Acronym</th>
                                    <th>Location</th>
                                    <th>Dates</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($conferences as $conference)
                                    <tr>
                                        <td>{{ $conference->title }}</td>
                                        <td>{{ $conference->acronym }}</td>
                                        <td>{{ $conference->location }}</td>
                                        <td>
                                            {{ $conference->start_date->format('M d, Y') }} -
                                            {{ $conference->end_date->format('M d, Y') }}
                                        </td>
                                        <td>
                                            <span class="badge
                                                @if($conference->status === 'upcoming') bg-info
                                                @elseif($conference->status === 'ongoing') bg-success
                                                @else bg-secondary
                                                @endif">
                                                {{ ucfirst($conference->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('conferences.show', $conference) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                            @can('admin')
                                                <a href="{{ route('conferences.edit', $conference) }}" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <form action="{{ route('conferences.destroy', $conference) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                {{-- @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No conferences found</td>
                                    </tr> --}}
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $conferences->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
