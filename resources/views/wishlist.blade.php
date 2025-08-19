@extends('components.default')

@section('title', 'Wishlist Products')

@section('nav')
@include('includes.profile')
@endsection

@section('sidebar')
@include('includes.user')
@endsection

@section('mainbar')
<h2 class="lead text-start display-6 mt-4">My Wishlist</h2>

@if($wishlists->isEmpty())
    <div class="alert alert-info mt-3">
        <i class="bi bi-heartbreak"></i> Your wishlist is empty.
    </div>
@else
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
        <p class="text-muted fst-italic mb-0">
            You have {{ $wishlists->count() }} item(s) in your wishlist
        </p>
        <form method="POST" action="{{ route('wishlist.clear') }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">
                <i class="bi bi-trash"></i> Clear All
            </button>
        </form>
    </div>

    <div class="table-responsive border rounded shadow-sm mt-3">
        <table class="table table-striped table-hover align-middle mb-0">
            <thead class="table-primary text-center">
                <tr>
                    <th scope="col">Image</th>
                    <th scope="col">Product</th>
                    <th scope="col">Price</th>
                    <th scope="col">Added On</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($wishlists as $wishlist)
                    @php $product = $wishlist->product; @endphp
                    @include('components.wishlist', ['product' => $product, 'wishlist' => $wishlist])
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
    style="background: linear-gradient(90deg, #0d6efd, #6610f2);"  data-aos="flip-right">
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

{{-- 💡 Wishlist Tips / Things to Know --}}
<div class="mt-5 p-4 rounded border border-body shadow-sm bg-light">
    <h3 class="fw-bold mb-3">💡 Things to Know About Your Wishlist</h3>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">
            <strong>Stock Availability:</strong> Products in your wishlist may go out of stock, so check them regularly.
        </li>
        <li class="list-group-item">
            <strong>Price Updates:</strong> Prices can change. Keep an eye on your wishlist for new deals.
        </li>
        <li class="list-group-item">
            <strong>Add to cart:</strong> If you're ready you can add your wishlist from the cart. See from your action.
        </li>
        <li class="list-group-item">
            <strong>Removing Items:</strong> Easily remove items by clicking the heart icon or use "Clear All" to start fresh.
        </li>
        <li class="list-group-item">
            <strong>Items:</strong> You can infinitely add any amount of your wishlist, it only gets harder to navigate.
        </li>
    </ul>
</div>
@endsection

@section('footer')
@include('includes.footer')
@endsection