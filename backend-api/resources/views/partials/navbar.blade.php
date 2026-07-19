<nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm py-2">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('assets/images/fnfLogo.jpg') }}" height="40" alt="Logo" class="me-2">
        </a>

        <div class="d-flex align-items-center order-lg-last">

            @auth
                    {{-- Notification --}}
                <div class="dropdown me-3">
                    <a href="#" class="text-secondary position-relative" id="notifDropdown" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fa-regular fa-bell fs-5"></i>
                        @if (auth()->user()->unreadNotifications->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                style="font-size: 0.6rem;">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3 anim-up notification-list notif-dropdown-mobile"
                        aria-labelledby="notifDropdown" style="min-width: 300px; max-height: 400px; overflow-y: auto;">
                        <li class="px-3 py-2 fw-bold border-bottom d-flex justify-content-between align-items-center">
                            <span>Notifications</span>
                            @if (auth()->user()->unreadNotifications->count() > 0)
                                <a href="{{ route('notifications.markRead') }}"
                                    class="small text-primary text-decoration-none" style="font-size: 10px;">Mark all as
                                    read</a>
                            @endif
                        </li>

                        @forelse(auth()->user()->unreadNotifications as $notification)
                            <li>
                                <a class="dropdown-item py-2 border-bottom" href="{{ $notification->data['url'] ?? '#' }}">
                                    <div class="d-flex flex-column">
                                        <span class="small text-dark fw-medium">{{ $notification->data['message'] }}</span>
                                        <small class="text-muted"
                                            style="font-size: 10px;">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li class="px-3 py-4 text-center">
                                <i class="fa-solid fa-bell-slash text-muted mb-2 d-block"></i>
                                <span class="small text-muted">No new notifications</span>
                            </li>
                        @endforelse

                        <li><a class="dropdown-item text-center small text-primary fw-bold" href="#">View All</a></li>
                    </ul>
                </div>
                    {{-- User Profile --}}
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle hide-arrow"
                        id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="position-relative">
                            <img
                            src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : asset('assets/images/default-user.jpg') }}"
                                alt="user" width="38" height="38"
                                class="rounded-circle border border-2 border-primary shadow-sm profile-img">
                            <span
                                class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle"></span>
                        </div>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3 anim-up profile-dropdown"
                        aria-labelledby="profileDropdown">
                        <li class="px-3 py-3 bg-light rounded-top-3">
                            <div class="d-flex align-items-center">
                                <img src="{{ auth()->user()->profile_photo_url ?? asset('assets/images/default-user.jpg') }}"
                                    width="45" height="45" class="rounded-circle me-2 border border-2 border-white">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark lh-1">{{ auth()->user()->name }}</span>
                                    <small class="text-muted mt-1"
                                        style="font-size: 11px;">{{ auth()->user()->email }}</small>
                                </div>
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider m-0">
                        </li>
                        <li><a class="dropdown-item py-2 mt-2" href="{{ route('profile.edit') }}"><i
                                    class="fa-solid fa-user-circle me-2 text-primary"></i> My Profile</a></li>
                        <li><a class="dropdown-item py-2" href="#"><i
                                    class="fa-solid fa-suitcase-rolling me-2 text-primary"></i> My Bookings</a></li>
                        <li><a class="dropdown-item py-2" href="#"><i class="fa-solid fa-heart me-2 text-primary"></i>
                                Wishlist</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('settings') }}"><i class="fa-solid fa-gear me-2 text-primary"></i> Settings</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item text-danger py-2" type="submit">
                                    <i class="fa-solid fa-power-off me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <div class="d-none d-sm-flex gap-2">
                    <a href="{{ route('login') }}"
                        class="btn btn-outline-primary btn-sm px-4 rounded-pill fw-medium">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-4 rounded-pill fw-medium">Sign
                        Up</a>
                </div>
                <div class="d-block d-sm-none d-sm-flex gap-2">
                    <a href="{{ route('login') }}"
                        class="btn btn-outline-primary btn-sm px-4 rounded-pill fw-medium">Sign In</a>

                </div>
            @endauth

            <button class="navbar-toggler ms-3 border-0 shadow-none p-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <i class="fa-solid fa-bars-staggered fs-4 text-dark"></i>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 mt-2 mt-lg-0">
                <li class="nav-item"><a class="nav-link active fw-medium" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link fw-medium" href="#">Packages</a></li>
                <li class="nav-item"><a class="nav-link fw-medium" href="#">Contact</a></li>
                @guest
                    <li class="nav-item d-sm-none mt-2">
                        <hr class="dropdown-divider">
                    </li>
                    <div class="d-flex gap-2">
                        <li class="nav-item d-sm-none"><a class=" btn btn-outline-primary btn-sm px-4 rounded-pill fw-medium"
                                href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item d-sm-none"><a class="btn btn-primary btn-sm px-4 rounded-pill fw-medium" href="{{ route('register') }}">Sign Up</a></li>
                    </div>

                @endguest
            </ul>
        </div>
    </div>
</nav>
