<div class="d-flex justify-content-center" data-aos="fade-right">
    <div class="card shadow-lg border-0 rounded-4" style="max-width: 400px; width: 100%;">
        <div class="card-body text-center p-4">
            <a href="{{ route('user.index') }}">
                <img src="
                    {{ Auth::user()->profile && Auth::user()->profile->avatar !== null ? 
                        asset('storage/' . Auth::user()->profile->avatar) : 
                        'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" 
                    class="rounded-circle mb-3 shadow-sm border border-2 border-light"
                    width="120" height="120" 
                    alt="Avatar">
            </a>

            <h4 class="fw-bold mb-1">{{ Auth::user()->name }}</h4>
            <p class="text-muted mb-1 small">{{ Auth::user()->email }}</p>
            <p class="mb-3 small">
                {{ Auth::user()->profile->first_name ?? '' }} {{ Auth::user()->profile->last_name ?? '' }}
            </p>

            <ul class="list-group list-group-flush text-start">
                <li class="list-group-item px-3 py-2 border-0">
                    <a href="{{ route('orders.index') }}" class="text-decoration-none text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-bag-check text-primary"></i> <span>Orders</span>
                    </a>
                </li>
                <li class="list-group-item px-3 py-2 border-0">
                    <a href="{{ route('wishlist.index') }}" class="text-decoration-none text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-heart text-danger"></i> <span>Wishlist</span>
                    </a>
                </li>
                <li class="list-group-item px-3 py-2 border-0">
                    <a href="{{ route('cart.index') }}" class="text-decoration-none text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-cart text-success"></i> <span>Cart</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-footer bg-light text-center d-flex flex-wrap gap-2 justify-content-center py-3">
            <a href="{{ route('user.edit') }}" class="btn btn-sm btn-outline-primary px-3">
                <i class="bi bi-pencil-square"></i> Edit Profile
            </a>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger px-3">
                    <i class="bi bi-box-arrow-left"></i> Sign Out
                </button>
            </form>
        </div>
    </div>
</div>
