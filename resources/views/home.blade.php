<x-app-layout>
    <div class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold">Welcome to MYCONF</h1>
            <p class="lead">Your comprehensive conference management solution</p>
            @guest
                <div class="mt-4">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 me-2">Get Started</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">Login</a>
                </div>
            @endguest
        </div>
    </div>

    <div class="container">
        <div class="row mb-5">
            <div class="col-md-8 mx-auto text-center">
                <h2 class="mb-4">Upcoming Conferences</h2>
                <p class="lead">Join leading researchers and professionals at these upcoming events</p>
            </div>
        </div>

        <div class="row g-4">
            @forelse($conferences as $conference)
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">{{ $conference->title }}</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">{{ Str::limit($conference->description, 150) }}</p>
                            <ul class="list-group list-group-flush mb-3">
                                <li class="list-group-item"><strong>Location:</strong> {{ $conference->location }}</li>
                                <li class="list-group-item"><strong>Date:</strong> {{ $conference->start_date->format('M d, Y') }} - {{ $conference->end_date->format('M d, Y') }}</li>
                                <li class="list-group-item"><strong>Status:</strong> <span class="badge bg-primary">{{ ucfirst($conference->status) }}</span></li>
                            </ul>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="#" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        No upcoming conferences at the moment. Check back later!
                    </div>
                </div>
            @endforelse
        </div>

        <div class="row mt-5">
            <div class="col-md-10 mx-auto">
                <div class="card">
                    <div class="card-body text-center p-5">
                        <h3 class="card-title mb-4">Why Choose MYCONF?</h3>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="p-3">
                                    <div class="mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="var(--primary-color)" class="bi bi-easel" viewBox="0 0 16 16">
                                            <path d="M8 0a.5.5 0 0 1 .473.337L9.046 2H14a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1.85l1.323 3.837a.5.5 0 1 1-.946.326L11.092 11H8.5v3a.5.5 0 0 1-1 0v-3H4.908l-1.435 4.163a.5.5 0 1 1-.946-.326L3.85 11H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h4.954L7.527.337A.5.5 0 0 1 8 0zM2 3v7h12V3H2z"/>
                                        </svg>
                                    </div>
                                    <h5>Easy Submission</h5>
                                    <p class="text-muted">Simple and intuitive paper submission process</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3">
                                    <div class="mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="var(--primary-color)" class="bi bi-people" viewBox="0 0 16 16">
                                            <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"/>
                                        </svg>
                                    </div>
                                    <h5>Collaborative Review</h5>
                                    <p class="text-muted">Efficient peer review process with clear communication</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3">
                                    <div class="mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="var(--primary-color)" class="bi bi-graph-up" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0Zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5Z"/>
                                        </svg>
                                    </div>
                                    <h5>Comprehensive Analytics</h5>
                                    <p class="text-muted">Detailed reports and statistics for organizers</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
