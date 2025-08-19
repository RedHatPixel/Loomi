@extends('components.default')

@section('title', 'Select your products')

@section('nav')
@include('components.header')
@endsection

@section('sidebar')
@include('includes.filter')
@endsection

@section('mainbar')
{{-- 🔥 Hero Banner --}}
<div class="mb-4" data-aos="fade-down">
    <div class="p-4 text-white rounded shadow-sm" 
            style="background: linear-gradient(90deg, #0d6efd, #6610f2);">
        <h2 class="display-6 fw-bold">Big Sale This Week 🎉</h2>
        <p class="lead">Try your lock from our <span class="fw-bold">Lucky</span> selected items.</p>
        <a href="{{ route('products.random') }}" class="btn btn-light btn-sm fw-bold">
            Feeling Lucky
        </a>
    </div>
</div>

{{-- 🛒 Product Listing --}}
@if ($products->isEmpty())
    @if ($products->currentPage() > 1)
        <script>
            window.location.href = "{!! $products->url($products->lastPage()) !!}";
        </script>
    @else
        <h2 class="lead fs-4">No product was found.</h2>
    @endif
@else
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="lead fs-4">We select what suit you best.</h2>
        <form method="GET" class="d-flex">
            @foreach(request()->except('sort') as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach

            <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="relevance">Sort By</option>
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest</option>
                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="top_sales" {{ request('sort') == 'top_sales' ? 'selected' : '' }}>Most Popular</option>
                <option value="high_ratings" {{ request('sort') == 'high_ratings' ? 'selected' : '' }}>High Ratings</option>
            </select>
        </form>
    </div>

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xxl-5 g-3">
        @foreach ($products as $product)
                @include('components.card', ['product' => $product])
        @endforeach
    </div>
@endif

@if ($products->hasPages() && $products->count())
    <div class="py-4">
        {{ $products->links() }}
    </div>
@endif
@endsection

@section('content')
{{-- ⭐ Featured Products --}}
<h2 class="lead text-start display-6 mt-5 mb-3" data-aos="fade-right">You may also like 👍</h2>
<div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">
    @foreach ($featuredProducts as $product)
        @include('components.card', ['product' => $product])
    @endforeach
</div>

{{-- 🎯 Special Ads / Promotions --}}
<div class="my-5">
    <h2 class="lead fs-4 mb-3" data-aos="fade-right">Special Deals & Ads</h2>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
        
        {{-- Ad 1 --}}
        <div class="col" data-aos="flip-left" data-aos-delay="100">
            <div class="card shadow-sm border-0 h-100 hover-scale text-center text-white"
                    style="background: linear-gradient(135deg, #ff7e5f, #feb47b);">
                <div class="card-body p-4">
                    <h5 class="fw-bold">🌞 Summer Sale</h5>
                    <p class="mb-2">Up to 40% off selected products</p>
                </div>
            </div>
        </div>

        {{-- Ad 2 --}}
        <div class="col" data-aos="flip-left" data-aos-delay="200">
            <div class="card shadow-sm border-0 h-100 hover-scale text-center text-white"
                style="background: linear-gradient(135deg, #36d1dc, #5b86e5);">
                <div class="card-body p-4">
                    <h5 class="fw-bold">✨ New Arrivals</h5>
                    <p class="mb-2">Fresh styles just dropped</p>
                </div>
            </div>
        </div>

        {{-- Ad 3 --}}
        <div class="col" data-aos="flip-left" data-aos-delay="300">
            <div class="card shadow-sm border-0 h-100 hover-scale text-center text-white"
                    style="background: linear-gradient(135deg, #ff512f, #dd2476);">
                <div class="card-body p-4">
                    <h5 class="fw-bold">🔥 Clearance</h5>
                    <p class="mb-2">Last chance deals</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 🔥 Trending Section --}}
<h2 class="lead text-start display-6 mt-5 mb-3" data-aos="fade-right">Trending Now 🔥</h2>
<div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">
    @foreach ($trendingProducts as $product)
            @include('components.card', ['product' => $product])
    @endforeach
</div>
@endsection

@section('footer')
@include('includes.footer')
@endsection