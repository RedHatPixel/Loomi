<footer class="py-5 px-3">
    <div class="row">
        <!-- Brand / About -->
        <div class="col-6 col-md-3 mb-3">
            <h5 class="fw-bold text-primary">Loomi</h5>
            <p class="text-muted small">
                Loomi is your trusted destination for quality products at the best prices. 
                Shop with confidence and enjoy a seamless online shopping experience.
            </p>
        </div>

        <!-- Quick Links -->
        <div class="col-6 col-md-2 mb-3">
            <h6 class="fw-bold">Quick Links</h6>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="{{ route('home') }}" class="nav-link p-0 text-muted">Home</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('products.index') }}" class="nav-link p-0 text-muted">Shop</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('wishlist.index') }}" class="nav-link p-0 text-muted">Wishlist</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('cart.index') }}" class="nav-link p-0 text-muted">Cart</a>
                </li>
            </ul>
        </div>

        <!-- Sorting / Explore -->
        <div class="col-6 col-md-2 mb-3">
            <h6 class="fw-bold">Explore</h6>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'latest'])) }}" 
                        class="nav-link p-0 text-muted">Latest Products</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'top_sales'])) }}" 
                        class="nav-link p-0 text-muted">Best Sellers</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'high_ratings'])) }}" 
                        class="nav-link p-0 text-muted">Top Rated</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'price_low'])) }}" 
                        class="nav-link p-0 text-muted">Budget Finds</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center py-4 mt-4 border-top">
        <p class="mb-0 text-muted small">© 2025 Loomi. All rights reserved.</p>
        <ul class="list-unstyled d-flex mb-0">
            <li class="ms-3">
                <a class="text-muted fs-5" href="https://www.instagram.com/" aria-label="Instagram">
                    <i class="bi bi-instagram"></i>
                </a>
            </li>
            <li class="ms-3">
                <a class="text-muted fs-5" href="https://www.facebook.com/" aria-label="Facebook">
                    <i class="bi bi-facebook"></i>
                </a>
            </li>
            <li class="ms-3">
                <a class="text-muted fs-5" href="https://twitter.com/" aria-label="Twitter">
                    <i class="bi bi-twitter"></i>
                </a>
            </li>
            <li class="ms-3">
                <a class="text-muted fs-5" href="https://www.youtube.com/" aria-label="YouTube">
                    <i class="bi bi-youtube"></i>
                </a>
            </li>
        </ul>
    </div>
</footer>
