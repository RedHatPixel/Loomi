<header class="bg-dark shadow-sm" data-aos="fade-down" data-aos-duration="800" style="z-index: 999">
    <div class="container-fluid px-3 py-2">
        <div class="d-flex justify-content-between align-items-center">

            <!-- Left: Brand + Dropdown Menu -->
            <div class="dropdown">
                <a href="{{ route('home') }}" 
                    class="d-flex align-items-center text-light text-decoration-none dropdown-toggle"
                    data-bs-toggle="dropdown" aria-expanded="false" aria-label="Main Menu">
                    <span class="fs-3 fw-bold text-primary me-2">Loomi</span>
                </a>
                <ul class="dropdown-menu shadow-sm rounded-3">
                    <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-house me-2"></i> Home</a></li>
                    <li><a class="dropdown-item" href="{{ route('products.index') }}"><i class="bi bi-grid me-2"></i> Products</a></li>
                </ul>
            </div>

            <!-- Right: User Profile -->
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-light text-decoration-none dropdown-toggle" 
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ Auth::user()->profile && Auth::user()->profile->avatar !== null ? asset('storage/' . Auth::user()->profile->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" 
                        class="rounded-circle" 
                        width="40" height="40" 
                        alt="Avatar">
                    <span class="ms-2 d-none d-sm-inline fw-semibold">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm rounded-3">
                    <li><a class="dropdown-item" href="{{ route('user.index') }}"><i class="bi bi-person-circle me-2"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="bi bi-bag-check me-2"></i> Orders</a></li>
                    <li><a class="dropdown-item" href="{{ route('wishlist.index') }}"><i class="bi bi-heart me-2"></i> Wishlist</a></li>
                    <li><a class="dropdown-item" href="{{ route('cart.index') }}"><i class="bi bi-cart me-2"></i> Cart</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('user.edit') }}"><i class="bi bi-pencil-square me-2"></i> Edit Profile</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-left me-2"></i> Sign Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
