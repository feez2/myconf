@if($notifications->count() > 0)
    <div class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="navbarDropdown">
        <div class="list-group" style="min-width: 300px;">
            @foreach($notifications as $notification)
                <a href="{{ $notification->data['link'] ?? '#' }}"
                   class="list-group-item list-group-item-action {{ is_null($notification->read_at) ? 'bg-light' : '' }}">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-1">{{ $notification->data['title'] ?? 'Notification' }}</h6>
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-1">{{ $notification->data['message'] ?? '' }}</p>
                    <form action="{{ route('notifications.mark-as-read', $notification) }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </a>
            @endforeach
            <div class="list-group-item text-center">
                <a href="{{ route('notifications.index') }}" class="text-primary">View All Notifications</a>
            </div>
        </div>
    </div>
@else
    <div class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="navbarDropdown">
        <p class="mb-0 text-muted">No new notifications</p>
    </div>
@endif
