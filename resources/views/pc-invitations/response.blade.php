<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Program Committee Invitation Response</h4>
                    </div>
                    <div class="card-body">
                        @if($status === 'success')
                            <div class="alert alert-success">
                                {{ $message }}
                            </div>
                        @else
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @endif

                        <div class="text-center mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                Go to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
