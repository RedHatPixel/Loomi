<a href="{{ route('products.show', ['product' => $product]) }}" 
    class="col text-decoration-none text-reset">

    <div class="card h-100 border-0 rounded-3 shadow-sm product-card">
        {{-- Product Image --}}
        <div class="position-relative">
            @if($product->primaryImage)
                <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                    alt="{{ $product->title }}"
                    class="card-img-top p-3 bg-light rounded-top"
                    style="height: 220px; object-fit: contain;">
            @else
                <img src="{{ asset('images/placeholder.png') }}" 
                    alt="No image available"
                    class="card-img-top p-3 bg-light rounded-top"
                    style="height: 220px; object-fit: contain;">
            @endif

            {{-- Wishlist Button (Top Right) --}}
            <div class="position-absolute top-0 end-0 m-2">
                @empty($product->yourWishlist)
                    <form method="POST" action="{{ route('wishlist.store') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="btn btn-light btn-sm rounded-circle shadow-sm">
                            <i class="bi bi-heart"></i>
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('wishlist.destroy', $product->yourWishlist) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm rounded-circle shadow-sm">
                            <i class="bi bi-heart-fill text-white"></i>
                        </button>
                    </form>
                @endempty
            </div>
        </div>

        {{-- Card Body --}}
        <div class="card-body d-flex flex-column">
            <h6 class="card-title text-truncate fw-semibold text-capitalize">
                {{ $product->title }}
            </h6>
            <p class="card-text text-success fw-bold mb-2">
                ₱ {{ number_format($product->price, 2) }}
            </p>

            {{-- Stock --}}
            <p class="text-muted small mb-3">Available: {{ $product->quantity }}</p>

            {{-- Add to Cart --}}
            <div class="mt-auto">
                <form method="POST" action="{{ route('cart.store') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-sm btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                        <small><i class="bi bi-cart"></i> Add to Cart</small>
                    </button>
                </form>
            </div>
        </div>
    </div>
</a>
