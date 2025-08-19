<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm py-2"
        data-aos="fade-down" data-aos-duration="800">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand d-flex align-items-center me-3" href="{{ route('home') }}">
                <i class="bi bi-bag-fill text-primary fs-3 me-2"></i>
                <span class="fs-4 fw-bold text-primary">Loomi</span>
            </a>

            <!-- Toggler (Mobile) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Left Nav -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-2">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active text-primary fw-bold' : '' }}" 
                            href="{{ route('home') }}">
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.index') ? 'active text-primary fw-bold' : '' }}" 
                            href="{{ route('products.index') }}">
                            Products
                        </a>
                    </li>
                </ul>

                <!-- Search Bar -->
                <form action="{{ route('products.index') }}" method="GET" 
                        class="d-flex flex-grow-1 justify-content-center my-2 my-lg-0 mx-lg-3">
                    <div class="input-group w-100 w-md-75 w-lg-50">
                        <input 
                            type="search" 
                            name="search" 
                            class="form-control border-0 rounded-start" 
                            placeholder="Search products..."
                            aria-label="Search"
                            value="{{ request('search') }}"
                        >
                        <button class="btn btn-primary rounded-end" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    @foreach(request()->except('search') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                </form>

                <!-- Right Side -->
                <div class="d-flex align-items-center justify-content-center gap-3 my-3 my-lg-0">
                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="text-white position-relative">
                        <i class="bi bi-cart fs-5"></i>
                        @auth
                            @php $count = App\Models\Cart::where('user_id', Auth::id())->count() @endphp
                            @if($count > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $count }}
                                </span>
                            @endif
                        @endauth
                    </a>

                    @auth
                        <a href="{{ route('wishlist.index') }}" class="text-white position-relative">
                            <i class="bi bi-heart-fill fs-5"></i>
                            @php $count = App\Models\Wishlist::where('user_id', Auth::id())->count() @endphp
                            @if($count > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $count }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('orders.index') }}" class="text-white position-relative">
                            <i class="bi bi-box-seam-fill fs-5"></i>
                            @php $count = App\Models\Order::where('user_id', Auth::id())->count() @endphp
                            @if($count > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $count }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('user.index') }}" class="text-white">
                            <i class="bi bi-person-circle fs-5"></i>
                        </a>
                    @endauth

                    @guest
                        <a class="btn btn-outline-light btn-sm" href="{{ route('show.login') }}">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                        <a class="btn btn-primary btn-sm" href="{{ route('show.register') }}">
                            Sign Up
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>
</header>
