@extends('components.default')

@section('title', 'Cart Products')

@section('nav')
@include('includes.profile')
@endsection

@section('sidebar')
@include('includes.user')
@endsection

@section('mainbar')
<h2 class="lead text-start display-6 mt-4">My Cart</h2>

@if($carts->isEmpty())
    <div class="alert alert-info mt-3">
        <i class="bi bi-cart"></i> Your cart is empty. Shop now to order.
    </div>
@else
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
        <p class="text-muted fst-italic mb-0">
            You have {{ $carts->count() }} item(s) in your cart
        </p>
        <div class="d-flex gap-2 ">
            <form method="POST" action="{{ route('checkout.store') }}">
                @csrf
                @foreach ($carts as $i => $cart)
                    <input type="hidden" name="items[{{ $i }}][product_id]" value="{{ $cart->product->id }}">
                    <input type="hidden" name="items[{{ $i }}][quantity]" value="{{ $cart->quantity }}">
                    <input type="hidden" name="items[{{ $i }}][cart_id]" value="{{ $cart->id }}">
                @endforeach
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bi bi-bag"></i> Buy All
                </button>
            </form>
            <form method="POST" action="{{ route('cart.clear') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="bi bi-trash"></i> Remove All
                </button>
            </form>
        </div>
    </div>

    <div class="table-responsive border rounded shadow-sm mt-3">
        <table class="table table-striped table-hover align-middle mb-0">
            <thead class="table-primary text-center">
                <tr>
                    <th scope="col">Image</th>
                    <th scope="col">Product</th>
                    <th scope="col">Total Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($carts as $cart)
                    @php $product = $cart->product; @endphp
                    @include('components.cart', ['product' => $product, 'cart' => $cart])
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection

@section('content')
<h2 class="lead text-start display-6 mt-5 mb-3" data-aos="fade-right">You may also like 👍</h2>
<div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">
    @foreach ($featuredProducts as $product)
        @include('components.card', ['product' => $product])
    @endforeach
</div>

{{-- 🔥 Featured Product Banner --}}
@php
    $highlightProduct = $featuredProducts->first(); // pick one featured product for banner
@endphp
@if($highlightProduct)
<div class="my-5 p-4 rounded shadow-sm text-white" 
    style="background: linear-gradient(90deg, #0dfd5d, #10f2a7);" data-aos="flip-right">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
        <div class="text-center text-md-start">
            <h3 class="fw-bold display-6">{{ ucfirst($highlightProduct->title) }}</h3>
            <p class="lead mb-2">
                Only ₱ {{ number_format($highlightProduct->price, 2) }} 
                – grab it while it lasts!
            </p>
            <a href="{{ route('products.show', $highlightProduct) }}" 
                class="btn btn-light btn-sm fw-bold">
                View Product
            </a>
        </div>
        <div class="text-center">
            <img src="{{ asset('storage/' . $highlightProduct->primaryImage->image_path) }}" 
                alt="{{ $highlightProduct->title }}" 
                class="img-fluid rounded" style="max-height: 150px; object-fit: contain;">
        </div>
    </div>
</div>
@endif

<h2 class="lead text-start display-6 mt-5 mb-3" data-aos="fade-right">Lowest Deal 👌</h2>
<div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">
    @foreach ($lowestProducts as $product)
        @include('components.card', ['product' => $product])
    @endforeach
</div>

{{-- 💡 Things to Know About Your Cart --}}
<div class="mt-5 p-4 rounded border border-body shadow-sm bg-light">
    <h3 class="fw-bold mb-3">💡 Things to Know About Your Cart</h3>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">
            <strong>Stock Availability:</strong> Items in your cart may go out of stock. Check your cart before proceeding to checkout.
        </li>
        <li class="list-group-item">
            <strong>Price Updates:</strong> Prices may change. Ensure you review your total before checking out.
        </li>
        <li class="list-group-item">
            <strong>Multiple Quantities:</strong> You can update quantities in your cart before placing an order.
        </li>
        <li class="list-group-item">
            <strong>Removing Items:</strong> Remove items individually using the delete button, or use "Remove All" to clear your cart.
        </li>
        <li class="list-group-item">
            <strong>Checkout:</strong> You can buy all items at once or select individual products to checkout.
        </li>
    </ul>
</div>
@endsection

@section('footer')
@include('includes.footer')
@endsection