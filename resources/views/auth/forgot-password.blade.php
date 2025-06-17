<x-guest-layout>
    <x-slot name="title">Forgot Password</x-slot>

    <div class="alert alert-info mb-4">
        Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
    </div>

    @if (session('status'))
        <div class="alert alert-success mb-4">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a class="auth-link ms-2" href="{{ route('login') }}">
                Remember password?
            </a>
            <button type="submit" class="btn btn-primary px-4">
                Email Password Reset Link
            </button>
        </div>
    </form>
</x-guest-layout>
