<x-app-layout>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Program Committee - {{ $conference->title }}</h2>
            <a href="{{ route('conferences.show', $conference) }}" class="btn btn-outline-secondary">
                Back to Conference
            </a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Current Committee Members</h4>
                    </div>
                    <div class="card-body">
                        @if($members->isEmpty())
                            <div class="alert alert-info">
                                No committee members have been added yet.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($members as $member)
                                        <tr>
                                            <td>{{ $member->user->name }}</td>
                                            <td>{{ $member->user->email }}</td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ ucfirst(str_replace('_', ' ', $member->role)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge
                                                    @if($member->status === 'pending') bg-warning
                                                    @elseif($member->status === 'accepted') bg-success
                                                    @else bg-danger
                                                    @endif">
                                                    {{ ucfirst($member->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @can('managePC', $conference)
                                                    <form action="{{ route('pc-members.destroy', $member) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remove this member?')">
                                                            Remove
                                                        </button>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Invite New Members</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('pc-members.store', $conference) }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Select Reviewer</label>
                                <select name="user_id" class="form-select" required>
                                    <option value="">Select a reviewer</option>
                                    @foreach($potentialMembers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select" required>
                                    <option value="reviewer">Reviewer</option>
                                    <option value="area_chair">Area Chair</option>
                                    <option value="program_chair">Program Chair</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Personal Message (Optional)</label>
                                <textarea name="message" class="form-control" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Send Invitation</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
