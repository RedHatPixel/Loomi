<tr>
    <td>
        <a href="{{ route('products.show', $product) }}" 
        class="d-flex align-items-center gap-4 text-decoration-none text-reset"
        style="width: 280px;">
            @if($product->primaryImage)
                <img src="{{ asset('storage/products' . $product->primaryImage->image_path) }}" 
                        alt="{{ $product->title }}" 
                        class="rounded-circle"
                        style="width: 50px; height: 50px; object-fit: contain;">
            @else
                <img src="https://via.placeholder.com/60" 
                        alt="No Image" 
                        class="rounded-circle">
            @endif
            <span class="fw-semibold text-break fs-6 text-truncate text-capitalize text-ellipsis-2">
                {{ $product->title }}
            </span>
        </a>
    </td>
    <td>
        <div class="text-break fs-6 fw-bold text-truncate text-success text-ellipsis-2" style="width: 100px;">
            ₱ {{ number_format($product->price, 2) ?? '0' }}
        </div>
    </td>
    <td>
        <div class="d-flex gap-2" style="width: 100px;">
            <form method="POST" action="{{ route('wishlist.destroy', $wishlist) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i> Remove
                </button>
            </form>
        </div>
    </td>
</tr>