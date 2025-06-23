<x-guest-layout>
    <x-slot name="title">Create MYCONF Account</x-slot>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-4">
            <label for="name" class="form-label">Full Name</label>
            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="username">
            @error('email')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password">
            @error('password')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            @error('password_confirmation')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Login as Reviewer Checkbox -->
        <div class="mb-4 form-check">
            <input id="reviewer" type="checkbox" class="form-check-input" name="reviewer" value="1" {{ old('reviewer') ? 'checked' : '' }}>
            <label for="reviewer" class="form-check-label">Login as Reviewer</label>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a class="auth-link" href="{{ route('login') }}">
                Already registered?
            </a>

            <button type="submit" class="btn btn-primary px-4">
                Register
            </button>
        </div>
    </form>
</x-guest-layout>
