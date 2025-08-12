<header class="bg-dark shadow px-4 py-2">   
    <div class="d-flex gap-3 justify-content-between align-items-center w-100"> 
        <div class="dropdown"> 
            <a href="{{ route('home') }}" 
                class="d-flex align-items-center gap-1 text-light text-decoration-none dropdown-toggle"
                data-bs-toggle="dropdown" aria-expanded="false" aria-label="Bootstrap menu"> 
                <span class="fs-2 text-primary">Loomi</span>
            </a>
            <ul class="dropdown-menu text-small shadow"> 
                <li><a class="dropdown-item" href="{{ route('home') }}">Home</a></li>
                <li><a class="dropdown-item" href="{{ route('products.index') }}">Products</a></li>
                <li><a class="dropdown-item" href="#">Start Selling...</a></li> 
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('wishlist.index') }}">
                    <i class="bi bi-heart"></i> Wishlist
                </a></li>
                <li><a class="dropdown-item" href="{{ route('cart.index') }}">
                    <i class="bi bi-cart"></i> Cart
                </a></li>
            </ul> 
        </div> 
        <div class="dropdown"> 
            <a href="#" class="d-block text-light text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> 
                <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle"> 
            </a> 
            <ul class="dropdown-menu text-small shadow"> 
                <li><a class="dropdown-item" href="{{ route('user.index') }}">
                    <i class="bi bi-person-circle"></i> Profile
                </a></li> 
                <li><a class="dropdown-item" href="{{ route('orders.index') }}">
                    <i class="bi bi-box2"></i> Orders
                </a></li> 
                <li><a class="dropdown-item" href="{{ route('user.edit') }}">
                    <i class="bi bi-pen"></i> Edit
                </a></li> 
                <li><hr class="dropdown-divider"></li> 
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-box-arrow-left"></i> Sign out
                        </button>
                    </form>
                </li> 
            </ul> 
        </div> 
    </div> 
</header>