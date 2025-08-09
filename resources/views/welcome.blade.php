@extends('components.default')

@section('title', 'Welcome to Loomi')

@section('nav')
@include('components.header')
@endsection

@section('content')
<div class="row flex-md-row-reverse align-items-center g-5 py-5">
    <div class="col-md-6 col-lg-6">
        <div id="bestsellerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="10000">
            <div class="carousel-inner rounded shadow-sm">
                @foreach ($featuredProducts as $product)
                    <a href="{{ route('products.show', ['product' => $product]) }}"
                        class="carousel-item text-decoration-none {{ $loop->first ? 'active' : '' }}"
                        style="height: 400px;">
                        @if($product->primaryImage)
                            <img src="{{ asset('storage/products' . $product->primaryImage->image_path) }}"
                                alt="{{ $product->title }}"
                                class="w-100 h-100 img-fluid text-break"
                                style="object-fit: contain;">
                        @else
                            <img src="/default-placeholder.jpg" alt="No image available">
                        @endif
                    </a>
                @endforeach

                @empty($featuredProducts)
                    <div class="carousel-item active">
                        <img src="./assets/images/placeholder.webp"
                            alt="No product available"
                            class="w-100 h-100 img-fluid text-break"
                            style="height: 200px; object-fit: contain;">
                    </div>
                @endempty
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#bestsellerCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#bestsellerCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <div class="col-md-6 col-lg-6">
        <h1 class="display-4 fw-bold text-primary lh-1 mb-3">
            Welcome to Loomi
        </h1>
        <p class="lead">
            Discover the ultimate online shopping destination where everything you need is just a click away. From gadgets and fashion to everyday essentials, Loomi brings you quality products, and a seamless shopping experience — all in one place.
        </p>
        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
            <a href="{{ route('products.index') }}" type="button" class="btn btn-primary text-white btn-lg px-4 me-md-2">
                <i class="bi bi-bag"></i> Start Buying
            </a>
            <a href="{{ route('wishlist.index') }}" type="button" class="btn btn-outline-secondary btn-lg px-4">
                <i class="bi bi-suit-heart"></i> Wishlist
            </a>
        </div>
    </div>
</div>

<h2 class="lead text-start display-6 mt-5 mb-3">Categories</h2>
@empty($categories)
    <p class="lead fs-6">Missing categories.</p>
@endempty

<div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 row-cols-xxl-8 g-2 justify-content-center">
    @foreach($categories as $category)
        <div class="col">
            <a href="{{ route('products.index', array_merge(request()->all(), ['category' => $category->category])) }}"
                class="card border text-decoration-none hover-shadow">
                <div class="card-body">
                    <p class="text-center bold">{{ $category->category }}</p>
                </div>
            </a>
        </div>
    @endforeach
</div>

<h2 class="lead text-start display-6 mt-5 mb-3">Latest Fashion</h2>
@empty($latestProducts)
    <p class="lead fs-6">Missing latest products.</p>
@endempty

<div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 row-cols-xxl-8 g-3">
    @foreach ($latestProducts as $product)
        @include('components.card', ['product' => $product])
    @endforeach
</div>

<div class="p-4 p-md-5 mt-5 mb-3 rounded bg-secondary"> 
    <div class="col-lg-6 px-0"> 
        <h1 class="display-4 fst-italic text-white fw-bold">
            Discover Products Made Just for You
        </h1> 
        <p class="lead my-3">
            From everyday essentials to unique finds, our curated selection brings quality, style, and affordability together in one place. Shop confidently knowing every item is handpicked to meet your lifestyle needs.
        </p> 
    </div> 
</div>
<div class="row mb-2"> 
    <div class="col-md-6"> 
        <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative"> 
            <div class="col p-4 d-flex flex-column position-static"> 
                <strong class="d-inline-block mb-2 text-success-emphasis">Men’s Wear</strong> 
                <h3 class="mb-1 fw-bold">Elevate Your Style</h3> 
                <div class="mb-2 text-body-secondary">New Arrivals</div> 
                <p class="card-text mb-2">
                    Discover premium jackets, shirts, and streetwear essentials crafted for everyday comfort and bold expression.
                </p> 
                <a href="{{ route('products.index') }}" class="icon-link gap-1 icon-link-hover stretched-link">
                    Shop Now
                    <i class="bi bi-arrow-right"></i>
                </a> 
            </div> 
            <div class="col-5 d-none d-lg-block"> 
                <img src="https://nobero.com/cdn/shop/files/mocha-bisque_369e451c-23a8-4ee6-8bed-a852aceeddfe.jpg?v=1735811881" 
                    class="w-100 h-100"
                    style="min-height: 300px; max-height: 300px; object-fit: cover;"
                    alt="Ad 2">
            </div> 
        </div> 
    </div> 
    <div class="col-md-6"> 
        <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative"> 
            <div class="col p-4 d-flex flex-column position-static"> 
                <strong class="d-inline-block mb-2 text-success-emphasis">Women’s Collection</strong> 
                <h3 class="mb-1 fw-bold">Brands Meets Comfort</h3> 
                <div class="mb-2 text-body-secondary">Limited Edition</div> 
                <p class="card-text mb-2">
                    Step into elegance with dresses, tops, and loungewear made to move with you — wherever you go.
                </p> 
                <a href="{{ route('products.index', ) }}" class="icon-link gap-1 icon-link-hover stretched-link">
                    Explore Collection
                    <i class="bi bi-arrow-right"></i>
                </a> 
            </div> 
            <div class="col-5 d-none d-lg-block"> 
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQKq01JxNXXqtPIRrQPdKsZddW_H3qqeVFdBw&s" 
                    class="w-100 h-100"
                    style="min-height: 300px; max-height: 300px; object-fit: cover;"
                    alt="Ad 2">
            </div> 
        </div> 
    </div> 
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