<tr>
    <td class="text-center" style="width: 80px;">
        <img src="{{ $product->primaryImage 
            ? asset('storage/' . $product->primaryImage->image_path) 
            : asset('assets/images/placeholder.webp') }}" 
            alt="{{ $product->title }}" 
            class="img-fluid rounded" style="height: 60px; object-fit: contain;">
    </td>
    <td style="min-width: 300px;">
        <strong>{{ ucfirst($product->title) }}</strong><br>
        <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
    </td>
    <td class="fw-semibold text-center text-success"  style="min-width: 150px;">
        ₱{{ number_format($product->price, 2) }}
    </td>
    <td class="text-muted text-center" style="min-width: 150px;">
        {{ $wishlist->created_at->format('M d, Y') }}
    </td>
    <td class="text-center" style="min-width: 150px;">
        <form action="{{ route('wishlist.destroy', $wishlist) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" title="Remove">
                <i class="bi bi-heartbreak"></i>
            </button>
        </form>
        <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-info" title="View">
            <i class="bi bi-search"></i>
        </a>
        <form action="{{ route('cart.store') }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="btn btn-sm btn-success" title="Move to Cart">
                <i class="bi bi-cart-plus"></i>
            </button>
        </form>
    </td>
</tr>