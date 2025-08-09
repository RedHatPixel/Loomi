<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100 py-1 px-3">
    <div class="d-flex flex-column flex-lg-row justify-content-between w-100">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link active text-white" href="{{ route('home') }}">Home</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('products.index') }}">Products</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="./">Start Selling</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="./">FAQs</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="./">About</a></li>
        </ul>

        @auth
            <div class="d-flex gap-4 align-items-center">
            <span class="lead fs-6 text-white">
                Hi, there {{ Auth::user()->name }}
            </span>
            <a href="{{ route('wishlist.index') }}" class="text-white">
                <i class="bi bi-suit-heart-fill fs-4 text-primary"></i>
            </a>
            <a href="{{ route('user.index') }}" class="text-white">
                <i class="bi bi-person-fill fs-3 text-primary"></i>
            </a>
        </div>
        @endauth

        @guest
            <div class="d-flex gap-2 my-2">
                <a class="btn btn-outline-light" href="{{ route('show.login') }}">
                    <i class="bi bi-door-open"></i>
                    Log in
                </a>
                <a class="btn btn-primary" href="{{ route('show.register') }}">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Sign up
                </a>
            </div>
        @endguest
    </div>
</nav>