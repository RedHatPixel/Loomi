@extends('components.default')

@section('title', $product->title)

@section('nav')
@include('components.header')
@endsection

@section('content')
<div class="p-2 rounded border border-body-secondary shadow-sm">
    <div class="row"> 
        <div class="col-lg-6 col-md-12 p-3">
            <div class="mb-3 text-center">
                <img id="mainImage" 
                    src="{{ asset('storage/products' . $product->primaryImage->image_path) }}"
                    alt="{{ $product->title }}" 
                    class="img-fluid border rounded" 
                    style="height: 400px; object-fit: contain;">
            </div>

            <div id="thumbCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner d-flex" style="height: 100px;">
                    @foreach ($product->images->chunk(3) as $chunkIndex => $chunk)
                        <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                            <div class="d-flex justify-content-center gap-2">
                                @foreach ($chunk as $image)
                                    <img src="{{ asset('storage/products' . $image->image_path) }}"
                                        class="product-image"
                                        style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;">
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($product->images->count() >= 4)
                    <button class="carousel-control-prev" type="button" data-bs-target="#thumbCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#thumbCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                @endif
            </div>
        </div>

        <div class="col-lg-6 col-md-12 p-3">
            <div class="d-flex justify-content-between">
                <h3 class="fw-bold fst-italic text-capitalize">{{ $product->title ?? 'No Product Name' }}</h3>
                    @empty ($product->userWishlist)
                        <form method="POST" action="{{ route('wishlist.store') }}">
                            @csrf
                            <button type="submit" class="btn text-danger">
                                <i class="bi bi-heart"></i>
                            </button>
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                        </form>
                    @else
                        <form method="POST" action="{{ route('wishlist.destroy', $product->userWishlist) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn text-danger">
                                <i class="bi bi-heart-fill"></i>
                            </button>
                        </form>
                    @endif
            </div>

            <div class="lead fs-6 d-flex flex-wrap gap-3 py-2">
                <span class="d-flex gap-1 align-items-center">
                    <span class="me-1">
                        {{ $avg = rtrim(rtrim(number_format($product->ratings_avg_stars, 2), '0'), '.') }}
                    </span>
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($avg >= $i)
                            <small><i class="bi bi-star-fill text-warning"></i></small>
                        @else
                            <small><i class="bi bi-star text-warning"></i></small>
                        @endif
                    @endfor
                </span>
                <span class="border-start ps-3">
                    {{ $product->ratings->count() ?? '0' }} ratings
                </span>
                <span class="border-start ps-3">
                    {{ $product->sales_sum_quantity ?? '0' }} sales
                </span>
                <span class="border-start ps-3">
                    {{ $product->quantity ?? '0' }} stock
                </span>
            </div>

            <h3 class="fw-bold text-primary d-block bg-body-secondary rounded p-3">
                ₱ {{ number_format($product->price, 2) ?? '0' }}
            </h3>
            <p class="lead fs-5 mt-3">
                {{ $product->creator->name ?? 'Loomi' }}
            </p>
            <p class="lead fs-6 mt-3">
                {{ $product->description ?? '' }}
            </p>

            <div class="my-3">
                @foreach ($product->categories as $category)
                    <span class="badge bg-danger p-2 m-1">{{ $category->category }}</span>
                @endforeach
            </div>

            <div class="btn-group mt-4">
                <button type="button" class="btn btn-outline-dark buttonLeft">
                    <i class="bi bi-dash"></i>
                </button>
                <input type="number" name="quantity"
                        class="form-control text-center rounded-0 border-dark quantityInput" 
                        value="1" min="1" max="{{ $product->quantity }}" 
                        style="width: 100px;"
                        data-hidden-selector=".hiddenInput">
                <button type="button" class="btn btn-outline-dark buttonRight">
                    <i class="bi bi-plus"></i>
                </button>
            </div>

            <div class="d-flex flex-wrap gap-3 my-3">
                <form method="POST" action="{{ route('cart.store') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" class="hiddenInput">
                    <button type="submit" class="btn btn-primary text-white rounded-0">
                        <i class="bi bi-cart me-1"></i> Add to Cart
                    </button>
                </form>

                <form method="POST" action="{{ route('checkout.store') }}">
                    @csrf
                    <input type="hidden" name="items[0][product_id]" value="{{ $product->id }}">
                    <input type="hidden" name="items[0][quantity]" class="hiddenInput">
                    <button type="submit" class="btn btn-outline-primary rounded-0">
                        <i class="bi bi-bag me-1"></i> Buy Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="mt-5 mb-3 p-3 rounded border border-body-secondary shadow-sm">
    <h3 class="fs-3 fw-bold mb-3 text-primary">Product Ratings</h3>
    <div class="alert alert-warning p-3 d-flex flex-wrap">
        <span class="text-danger fs-5 me-3">
            <span class="fs-2">{{ $avg }}</span> out of 5 
        </span>
        <span>
            @for ($i = 1; $i <= 5; $i++)
                @if ($avg >= $i)
                    <i class="bi bi-star-fill text-warning fs-3"></i>
                @else
                    <i class="bi bi-star text-warning fs-3"></i>
                @endif
            @endfor
        </span>
    </div>

    @forelse ($ratings as $rating)
        <div class="my-4">
            <div class="d-sm-flex flex-wrap align-items-center">
                <strong class="fs-5 me-2">{{ $rating->user->name ?? 'User' }}</strong> 
                <div class="text-warning">
                    @for ($i = 0; $i < $rating->stars; $i++) 
                        <i class="bi bi-star-fill text-warning"></i>
                    @endfor
                </div>
            </div>
            <p class="mt-2 ms-md-4">{{ $rating->comment ?? 'No Comment' }}</p>
        </div>
    @empty
        <p class="text-muted">No ratings yet.</p>
    @endforelse
    <div> {{ $ratings->links() }} </div>
</div>
@endsection

@section('footer')
    @include('includes.footer')
@endsection