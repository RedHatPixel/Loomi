<div class="card shadow-sm border-0">
    <div class="card-body text-center">
        <a href="{{ route('user.index') }}">
            <img src="
                {{ Auth::user()->profile->avatar ? 
                asset('storage/' . Auth::user()->profile->avatar) :
                'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" 
                class="rounded-circle mb-3" 
                width="120" height="120" 
                alt="Avatar">
        </a>
        <h3 class="d-block mb-2">{{ Auth::user()->name }}</h3>
        <small class="d-block text-muted mb-1">
            {{ Auth::user()->email }}
        </small>
        <small class="d-block mb-1">
            {{ Auth::user()->profile->first_name ?? '' }} 
            {{ Auth::user()->profile->last_name ?? '' }}
        </small>

        <ul class="list-group list-group-flush my-2">
            <li class="list-group-item px-2 py-2 border-0">
                <a href="{{ route('orders.index') }}" class="text-decoration-none d-flex align-items-center gap-2">
                    <i class="bi bi-bag-check"></i> <span>Orders</span>
                </a>
            </li>
            <li class="list-group-item px-2 py-2 border-0">
                <a href="{{ route('wishlist.index') }}" class="text-decoration-none d-flex align-items-center gap-2">
                    <i class="bi bi-heart"></i> <span>Wishlist</span>
                </a>
            </li>
            <li class="list-group-item px-2 py-2 border-0">
                <a href="{{ route('cart.index') }}" class="text-decoration-none d-flex align-items-center gap-2">
                    <i class="bi bi-cart"></i> <span>Cart</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="card-footer text-center d-flex gap-2 justify-content-center">
        <a href="{{ route('user.edit') }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil-square"></i> Edit Profile
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-box-arrow-left"></i> Sign out
            </button>
        </form>
    </div>
</div>