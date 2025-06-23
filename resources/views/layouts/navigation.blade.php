@php
use App\Models\Conference;
@endphp

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">MYCONF</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    @if(auth()->user()->role === 'reviewer' || auth()->user()->programCommittees()->exists())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reviews.index') }}">My Reviews</a>
                        </li>
                    @endif
                    @if(auth()->user()->role === 'author')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('papers.index') }}">My Submissions</a>
                        </li>
                    @endif
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('decisions.select-conference') }}">Paper Decisions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('proceedings.select-conference') }}">Proceedings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('program-book.select-conference') }}">Program Book</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reports.select-conference') }}">Report</a>
                        </li>
                    @endif

                @endauth
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="{{ route('notifications.index') }}">
                            <i class="bi bi-bell"></i>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown" style="min-width: 350px;">
                            <li>
                                @include('components.notification-dropdown', [
                                    'notifications' => auth()->user()->unreadNotifications()->limit(5)->get()
                                ])
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-center" href="{{ route('notifications.index') }}">
                                    View All Notifications
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
