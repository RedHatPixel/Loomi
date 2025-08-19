@extends('components.default')

@section('title', $product->title)

@section('nav')
    @include('components.header')
@endsection

@section('content')
<div class="p-4 rounded border border-body shadow-sm bg-white">

    {{-- PRODUCT MAIN INFO --}}
    <div class="row g-4">
        {{-- LEFT: Product Images --}}
        <div class="col-lg-6 col-md-12">
            <div class="border rounded shadow-sm p-3 bg-light text-center">
                <img id="mainImage"
                    src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                    alt="{{ $product->title }}"
                    class="img-fluid rounded"
                    style="height: 400px; object-fit: contain;">
            </div>

            {{-- Thumbnail Carousel --}}
            <div id="thumbCarousel" class="carousel slide mt-3" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($product->images->chunk(4) as $chunkIndex => $chunk)
                        <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                            <div class="d-flex justify-content-center gap-2">
                                @foreach ($chunk as $image)
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                        class="rounded border product-image"
                                        style="width: 20%; height: auto; aspect-ratio: 1/1; 
                                            object-fit: cover; cursor: pointer;">
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($product->images->count() >= 3)
                    <button class="carousel-control-prev" type="button" data-bs-target="#thumbCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#thumbCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                @endif
            </div>
        </div>

        {{-- RIGHT: Product Details --}}
        <div class="col-lg-6 col-md-12">
            {{-- Title & Wishlist --}}
            <div class="d-flex justify-content-between align-items-start">
                <h2 class="fw-bold text-capitalize">{{ $product->title ?? 'No Product Name' }}</h2>
                <div>
                    @empty ($product->yourWishlist)
                        <form method="POST" action="{{ route('wishlist.store') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-heart"></i>
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('wishlist.destroy', $product->yourWishlist) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-heart-fill"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Rating, Sales, Stock --}}
            <div class="d-flex flex-wrap gap-3 small text-muted mt-2">
                <span class="d-flex align-items-center">
                    <span class="me-1 fw-bold">
                        {{ $avg = rtrim(rtrim(number_format($product->ratings_avg_stars, 2), '0'), '.') }}
                    </span>
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="bi {{ $avg >= $i ? 'bi-star-fill' : 'bi-star' }} text-warning"></i>
                    @endfor
                </span>
                <span>| {{ $product->ratings->count() }} ratings</span>
                <span>| {{ $product->sales_sum_quantity ?? '0' }} sold</span>
                <span>| {{ $product->quantity ?? '0' }} in stock</span>
            </div>

            {{-- Price --}}
            <div class="bg-light rounded mt-3 p-3">
                <h3 class="fw-bold text-primary mb-0">
                    ₱ {{ number_format($product->price, 2) }}
                </h3>
            </div>

            {{-- Description --}}
            <p class="mt-3 text-muted">{{ $product->description ?? '' }}</p>

            {{-- Categories --}}
            <div class="my-3">
                @foreach ($product->categories as $category)
                    <span class="badge bg-danger-subtle text-danger fw-normal">{{ $category->name }}</span>
                @endforeach
            </div>

            {{-- Quantity & Actions --}}
            @if($product->quantity > 0)
                <div class="mt-4">
                    {{-- Quantity Selector --}}
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-dark buttonLeft"><i class="bi bi-dash"></i></button>
                            <input type="number" name="quantity"
                                class="form-control text-center border-dark quantityInput rounded-0"
                                value="1" min="1" max="{{ $product->quantity }}"
                                style="width: 80px;"
                                data-hidden-selector=".hiddenInput">
                            <button type="button" class="btn btn-outline-dark buttonRight"><i class="bi bi-plus"></i></button>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex gap-2 flex-wrap">
                        <form method="POST" action="{{ route('cart.store') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" class="hiddenInput">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-cart me-1"></i> Add to Cart
                            </button>
                        </form>

                        <form method="POST" action="{{ route('checkout.store') }}">
                            @csrf
                            <input type="hidden" name="items[0][product_id]" value="{{ $product->id }}">
                            <input type="hidden" name="items[0][quantity]" class="hiddenInput">
                            <button type="submit" class="btn btn-outline-primary px-4">
                                <i class="bi bi-bag me-1"></i> Buy Now
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- RATINGS SECTION --}}
<div class="mt-5 p-4 rounded border border-body shadow-sm bg-white">
    <h3 class="fw-bold mb-3">Product Ratings & Reviews</h3>

    {{-- Overall Rating --}}
    <div class="alert alert-light border d-flex align-items-center p-4">
        <div class="me-4 text-center">
            <h1 class="display-4 fw-bold text-warning">{{ $avg }}</h1>
            <p class="text-muted">out of 5</p>
        </div>
        <div>
            @for ($i = 1; $i <= 5; $i++)
                <i class="bi {{ $avg >= $i ? 'bi-star-fill' : 'bi-star' }} text-warning fs-3"></i>
            @endfor
            <p class="text-muted mt-2">{{ $product->ratings->count() }} total ratings</p>
        </div>
    </div>

    {{-- Leave a Rating --}}
    @can('create', [\App\Models\ProductRating::class, $product])
        <div class="mt-4 p-4 border rounded bg-light">
            <h5 class="fw-bold text-primary">Leave a Review</h5>
            <form method="POST" action="{{ route('rate', $product) }}">
                @csrf
                <div class="mb-3">
                    <div class="star-rating d-flex flex-row-reverse justify-content-end">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="stars" value="{{ $i }}" required />
                            <label for="star{{ $i }}"><i class="bi bi-star-fill"></i></label>
                        @endfor
                    </div>
                </div>
                <textarea name="comment" class="form-control mb-3" rows="3" placeholder="Write your review..."></textarea>
                <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i> Submit</button>
            </form>
        </div>
    @endcan

    {{-- Ratings List --}}
    <div class="mt-4">
        @forelse ($ratings as $rating)
            <div class="border-bottom py-3">
                <div class="d-flex justify-content-between">
                    <strong>{{ $rating->user->name ?? 'User' }}</strong>
                    <div class="text-warning">
                        @for ($i = 0; $i < $rating->stars; $i++)
                            <i class="bi bi-star-fill"></i>
                        @endfor
                    </div>
                </div>
                <p class="mt-2">{{ $rating->comment ?? 'No Comment' }}</p>
            </div>
        @empty
            <p class="text-muted">No ratings yet.</p>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-3">{{ $ratings->links() }}</div>
</div>
@endsection

@section('footer')
    @include('includes.footer')
@endsection