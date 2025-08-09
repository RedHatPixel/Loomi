@extends('components.default')

@section('title', 'Cart Products')

@section('nav')
@include('includes.profile')
@endsection

@section('content')
<div style="min-height: 60vh">
    <h2 class="lead text-start display-6 mt-4">My Cart</h2>

    @if($carts->isEmpty())
        <p class="lead fs-6">Your cart is empty. Shop now to order.</p>
    @else
        <div class="d-flex align-items-center gap-2 mt-2">
            <form method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-info">
                    <i class="bi bi-bag"></i> Buy All
                </button>
            </form>
            <form method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i> Remove All
                </button>
            </form>
        </div>
        <div class="table-responsive border border-primary rounded shadow-sm my-3">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-primary border-primary fst-italic">
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody class="border-primary">
                    @foreach($carts as $cart)
                        @php $product = $cart->product; @endphp
                        @include('components.cart', ['product' => $product, 'cart' => $cart])
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<h2 class="lead text-start display-6 mt-5 mb-3">You may also like.</h2>
@empty($featuredProducts)
    <p class="lead fs-6">Missing products.</p>
@endempty

<div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 row-cols-xxl-8 g-3">
    @foreach ($featuredProducts as $product)
        @include('components.card', ['product' => $product])
    @endforeach
</div>

<h2 class="lead text-start display-6 mt-5 mb-3">Lowest Deal</h2>
@empty($lowestProducts)
    <p class="lead fs-6">Missing cheap products.</p>
@endempty

<div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 row-cols-xxl-8 g-3">
    @foreach ($lowestProducts as $product)
        @include('components.card', ['product' => $product])
    @endforeach
</div>
@endsection

@section('footer')
@include('includes.footer')
@endsection