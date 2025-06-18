<x-app-layout>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Your Notifications</h2>
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">
                    Mark All as Read
                </button>
            </form>
        </div>

        <div class="card">
            <div class="card-body p-0">
                @if($notifications->isEmpty())
                    <div class="text-center py-5">
                        <h5>No notifications found</h5>
                        <p class="text-muted">You don't have any notifications at this time.</p>
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($notifications as $notification)
                            <a href="{{ route('notifications.redirect', $notification) }}"
                               class="list-group-item list-group-item-action {{ is_null($notification->read_at) ? 'bg-light' : '' }}">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1">{{ $notification->data['title'] ?? 'Notification' }}</h6>
                                    <small class="text-muted">{{ $notification->created_at->format('M j, Y g:i a') }}</small>
                                </div>
                                <p class="mb-1">{{ $notification->data['message'] ?? '' }}</p>
                                <form action="{{ route('notifications.mark-as-read', $notification) }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </a>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $notifications->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
