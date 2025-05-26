<header class="{{ request()->is('/') || request()->is('about-us') || request()->is('payment') || request()->is('payment/success') ? 'position-absolute' : '' }} top-0 start-0 w-100 z-3">
    <nav class="navbar navbar-expand-lg navbar-dark py-3">
        <div class="container-fluid bg-transparennt">
            <!-- Left Navigation -->
            <div class="navbar-collapse collapse d-lg-flex justify-content-between align-items-center w-100">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 flex-grow-1 mt-4 justify-content-around ">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('about-us') ? 'active' : '' }}" href="{{ url('about-us') }}">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('services') ? 'active' : '' }}" href="{{ url('/services') }}">Services</a>
                    </li>
                </ul>

                <!-- Center Logo -->
                <a class="navbar-brand mx-auto d-none d-lg-block" href="{{ url('/') }}">
                    <img src="{{ Storage::url('logo.png') }}" alt="{{ config('app.name', 'Laravel') }}" height="50">
                </a>

                <!-- Right Navigation -->
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 flex-grow-1 mt-4 justify-content-around">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('gallery') ? 'active' : '' }}" href="{{ url('/gallery') }}">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('kubo-room') ? 'active' : '' }}" href="{{ url('/kubo-room') }}">Room</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('payment') ? 'active' : '' }}" href="{{ url('/payment') }}">Accommodation</a>
                    </li>
                </ul>

                <!-- Mobile Toggle Button -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
    </nav>
</header>

<style>
@media (max-width: 991.98px) {
    .navbar-collapse {
        background: rgba(0, 0, 0, 0.9);
        padding: 1rem;
        border-radius: 8px;
        margin-top: 1rem;
    }
}
</style>
