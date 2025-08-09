@extends('components.default')

@section('title', 'Select your products')

@section('nav')
@include('components.header')
@endsection

@section('sidebar')
@include('includes.filter')
@endsection

@section('mainbar')
@if ($products->isEmpty())
    @if ($products->currentPage() > 1)
        <script>
            window.location.href = "{!! $products->url($products->lastPage()) !!}";
        </script>
    @else
        <h2 class="lead fs-4">No product was found.</h2>
    @endif
@else
    <h2 class="lead fs-4 py-2">We select what suit you best.</h2>
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
<h2 class="lead text-start display-6 mt-5 mb-3">You may also like.</h2>
@empty($featuredProducts)
    <p class="lead fs-6">Missing products.</p>
@endempty

<div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 row-cols-xxl-8 g-3">
    @foreach ($featuredProducts as $product)
        @include('components.card', ['product' => $product])
    @endforeach
</div>
@endsection


@section('footer')
@include('includes.footer')
@endsection