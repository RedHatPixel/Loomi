<tr>
    <td>
        <a href="{{ route('products.show', $product) }}" 
        class="d-flex align-items-center gap-4 text-decoration-none text-reset"
        style="width: 300px;">
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
            ₱ {{ number_format(($product->price * $cart->quantity), 2) ?? '0' }}
        </div>
    </td>
    <td>
        <div class="text-break fs-6 fw-bold text-truncate text-ellipsis-2" 
            style="width: 100px;" id="quantity-content{{ $cart->id }}">
            {{ $cart->quantity }}
        </div>

        <div class="btn-group" id="quantity-form{{ $cart->id }}" style="display: none; width: 120px;">
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
    <td>
        <div class="d-flex gap-2" style="width: 250px;">
            <button class="btn btn-sm btn-outline-success editButton"
                    quantity-form="#quantity-form{{ $cart->id }}"
                    update-form="#update-form{{ $cart->id }}"
                    quantity-content="#quantity-content{{ $cart->id }}">
                <i class="bi bi-phone"></i> Edit
            </button>
            <form method="POST" action="{{ route('cart.update', $cart) }}" 
                id="update-form{{ $cart->id }}" style="display: none">
                @csrf
                @method('PUT')
                <input type="hidden" name="quantity" class="hiddenInput{{ $cart->id }}">
                <button type="submit" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-bag"></i> Update
                </button>
            </form>
            <form method="POST" action="{{ route('checkout.store') }}">
                @csrf
                <input type="hidden" name="items[0][product_id]" value="{{ $cart->product->id }}">
                <input type="hidden" name="items[0][quantity]" class="hiddenInput{{ $cart->id }}">
                <button type="submit" class="btn btn-sm btn-outline-info">
                    <i class="bi bi-bag"></i> Buy
                </button>
            </form>
            <form method="POST" action="{{ route('cart.destroy', $cart) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i> Remove
                </button>
            </form>
        </div>
    </td>
</tr>