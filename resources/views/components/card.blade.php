<a href="{{ route('products.show', ['product' => $product]) }}" class="col text-decoration-none text-reset ">
    <div class="card shadow-sm border hover-shadow">
        @if($product->primaryImage)
            <img src="{{ asset('storage/products' . $product->primaryImage->image_path) }}"
                alt="{{ $product->title }}"
                class="card-img-top text-break border-light bg-light w-100" 
                style="height: 200px; object-fit: contain;">
        @else
            <img src="/default-placeholder.jpg" alt="No image available">
        @endif

        <div class="card-body d-flex flex-column">
            <h5 class="card-title text-break fs-6 text-truncate text-capitalize text-ellipsis-2">
                {{ $product->title }}
            </h5>
            <p class="card-text text-break fs-6 text-truncate text-success text-ellipsis-2">
                ₱ {{ number_format($product->price, 2) ?? '0' }}
            </p>
        </div>
        <div class="mt-auto p-2">
            <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group gap-1">
                    <form method="POST" action="{{ route('cart.store') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-cart"></i>
                        </button>
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                    </form>
                    @empty ($product->userWishlist)
                        <form method="POST" action="{{ route('wishlist.store') }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-heart"></i>
                            </button>
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                        </form>
                    @else
                        <form method="POST" action="{{ route('wishlist.destroy', $product->userWishlist) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-heart-fill"></i>
                            </button>
                        </form>
                    @endif
                </div>
                <span class="lead fs-6">{{ $product->quantity }}</span>
            </div>
        </div>
    </div>
</a>