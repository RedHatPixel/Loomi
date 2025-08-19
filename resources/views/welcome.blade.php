@extends('components.default')

@section('title', 'Welcome to Loomi')

@section('nav')
@include('components.header')
@endsection

@section('content')
{{-- Hero Section --}}
<div class="row flex-lg-row-reverse align-items-center g-5">
    <div class="col-12 col-sm-8 col-lg-6" data-aos="flip-left" data-aos-duration="700">
        <div id="bestsellerCarousel" class="carousel slide rounded-4 shadow-sm overflow-hidden" data-bs-ride="carousel" data-bs-interval="7000">
            <div class="carousel-inner">
                @foreach ($featuredProducts as $product)
                    <a href="{{ route('products.show', ['product' => $product]) }}"
                        class="carousel-item text-decoration-none {{ $loop->first ? 'active' : '' }}"
                        style="height: 400px;">
                        @if($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                alt="{{ $product->title }}"
                                class="w-100 h-100"
                                style="object-fit: cover;">
                        @else
                            <img src="/default-placeholder.jpg" alt="No image available" class="w-100 h-100" style="object-fit: cover;">
                        @endif
                        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded-3 p-2">
                            <h5 class="fw-bold text-white">{{ $product->title }}</h5>
                        </div>
                    </a>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#bestsellerCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#bestsellerCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
    <div class="col-lg-6" data-aos="fade-right" data-aos-duration="700" data-aos-once="true">
        <h1 class="display-4 fw-bold text-primary lh-1 mb-3">Welcome to Loomi</h1>
        <p class="lead text-muted">Discover the ultimate online shopping destination where everything you need is just a click away. From gadgets and fashion to everyday essentials, Loomi brings you quality products and a seamless shopping experience.</p>
        <div class="d-grid gap-3 d-md-flex justify-content-md-start">
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg px-4 rounded-pill shadow-sm" data-aos="zoom-in" data-aos-delay="200">
                <i class="bi bi-bag"></i> Start Buying
            </a>
            <a href="{{ route('wishlist.index') }}" class="btn btn-outline-dark btn-lg px-4 rounded-pill" data-aos="zoom-in" data-aos-delay="300">
                <i class="bi bi-suit-heart"></i> Wishlist
            </a>
        </div>
    </div>
</div>

{{-- Categories --}}
<h2 class="display-6 fw-semibold mt-5 mb-4" data-aos="fade-up">Shop by Category</h2>
<div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3">
    @foreach($categories as $category)
        <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
            <a href="{{ route('products.index', array_merge(request()->all(), ['category' => $category->name])) }}"
                class="card text-decoration-none border-0 shadow-sm rounded-4 h-100 category-card hover-zoom">
                <div class="card-body d-flex align-items-center justify-content-center">
                    <p class="fw-semibold text-dark text-center m-0">{{ $category->name }}</p>
                </div>
            </a>
        </div>
    @endforeach
</div>

{{-- Latest Products --}}
<h2 class="display-6 fw-semibold mt-5 mb-4" data-aos="fade-right">Latest Fashion</h2>
<div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">
    @foreach ($latestProducts as $product)
        @include('components.card', ['product' => $product])
    @endforeach
</div>

{{-- Highlight Section --}}
<div class="p-5 mt-5 mb-4 bg-primary text-white rounded-4 shadow-sm" data-aos="fade-up" data-aos-duration="800">
    <h1 class="display-5 fw-bold">Discover Products Made Just for You</h1>
    <p class="lead">From everyday essentials to unique finds, our curated selection brings quality, style, and affordability together. Shop confidently knowing every item is handpicked to meet your lifestyle needs.</p>
</div>

{{-- Ads Section --}}
<div class="row mb-2"> 
    <div class="col-md-6" data-aos="fade-right" data-aos-duration="700"> 
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

    <div class="col-md-6" data-aos="fade-left" data-aos-duration="700"> 
        <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative"> 
            <div class="col p-4 d-flex flex-column position-static"> 
                <strong class="d-inline-block mb-2 text-success-emphasis">Women’s Collection</strong> 
                <h3 class="mb-1 fw-bold">Brands Meets Comfort</h3> 
                <div class="mb-2 text-body-secondary">Limited Edition</div> 
                <p class="card-text mb-2">
                    Step into elegance with dresses, tops, and loungewear made to move with you — wherever you go.
                </p> 
                <a href="{{ route('products.index') }}" class="icon-link gap-1 icon-link-hover stretched-link">
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

{{-- Lowest Deals --}}
<h2 class="display-6 fw-semibold mt-5 mb-4" data-aos="fade-right">Lowest Deals</h2>
<div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">
    @foreach ($lowestProducts as $product)
        @include('components.card', ['product' => $product])
    @endforeach
</div>
@endsection

@section('footer')
@include('includes.footer')
@endsection

{{-- Hover Zoom CSS --}}
@push('styles')
<style>
.hover-zoom {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-zoom:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
</style>
@endpush
