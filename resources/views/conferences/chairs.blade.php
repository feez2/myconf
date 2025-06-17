<x-app-layout>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Manage Chairs for {{ $conference->title }}</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('conferences.chairs.store', $conference) }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Conference Chairs</label>
                                <select class="form-select" name="chair_ids[]" multiple>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ $conference->chairs->contains($user->id) ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hold CTRL to select multiple</small>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Chairs</button>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Manage Program Chairs</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('conferences.program-chairs.store', $conference) }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Program Chairs</label>
                                <select class="form-select" name="program_chair_ids[]" multiple>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ $conference->programChairs->contains($user->id) ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hold CTRL to select multiple</small>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Program Chairs</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
