<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>User Details</h4>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Users
                        </a>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-4">Name</dt>
                            <dd class="col-sm-8">{{ $user->name }}</dd>
                            <dt class="col-sm-4">Email</dt>
                            <dd class="col-sm-8">{{ $user->email }}</dd>
                            <dt class="col-sm-4">Role</dt>
                            <dd class="col-sm-8">
                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'author' ? 'primary' : 'success') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </dd>
                            <dt class="col-sm-4">Affiliation</dt>
                            <dd class="col-sm-8">{{ $user->affiliation }}</dd>
                            <dt class="col-sm-4">Country</dt>
                            <dd class="col-sm-8">{{ $user->country }}</dd>
                            <dt class="col-sm-4">Bio</dt>
                            <dd class="col-sm-8">{{ $user->bio }}</dd>
                        </dl>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            @if($user->role !== 'admin')
                            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 