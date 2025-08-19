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
        ₱{{ number_format($product->price * $cart->quantity, 2) }}
    </td>
    <td class="text-muted text-center" style="min-width: 150px;">
        <div class="text-break fs-6 fw-bold text-truncate text-ellipsis-2" 
            style="width: 100px;" id="quantity-content{{ $cart->id }}">
            {{ $cart->quantity }}
        </div>

        <div class="btn-group" id="quantity-form{{ $cart->id }}" style="display: none; width: 130px;">
            <button type="button" class="btn btn-sm btn-outline-dark buttonLeft">
                <i class="bi bi-dash"></i>
            </button>
            <input type="number" name="quantity"
                    class="form-control text-center rounded-0 border-dark quantityInput" 
                    value="{{ $cart->quantity }}" min="1" max="{{ $product->quantity }}" 
                    style="width: 70px;"
                    data-hidden-selector=".hiddenInput{{ $cart->id }}">
            <button type="button" class="btn btn-sm btn-outline-dark buttonRight">
                <i class="bi bi-plus"></i>
            </button>
        </div>
    </td>
    <td class="text-center" style="min-width: 150px;">
        <button class="btn btn-sm btn-warning editButton"
                quantity-form="#quantity-form{{ $cart->id }}"
                update-form="#update-form{{ $cart->id }}"
                quantity-content="#quantity-content{{ $cart->id }}">
            <i class="bi bi-pencil-square"></i>
        </button>
        <form method="POST" action="{{ route('cart.update', $cart) }}"
            id="update-form{{ $cart->id }}" style="display: none">
            @csrf
            @method('PUT')
            <input type="hidden" name="quantity" class="hiddenInput{{ $cart->id }}">
            <button type="submit" class="btn btn-sm btn-warning">
                <i class="bi bi-bag"></i>
            </button>
        </form>
        <form method="POST" action="{{ route('checkout.store') }}" class="d-inline">
            @csrf
            <input type="hidden" name="items[0][product_id]" value="{{ $cart->product->id }}">
            <input type="hidden" name="items[0][quantity]" class="hiddenInput{{ $cart->id }}">
            <input type="hidden" name="items[0][cart_id]" value="{{ $cart->id }}">
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="bi bi-bag"></i>
            </button>
        </form>
        <form method="POST" action="{{ route('cart.destroy', $cart) }}" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">
                <i class="bi bi-trash"></i>
            </button>
        </form>
    </td>
</tr>