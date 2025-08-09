<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100 p-1">
    <div class="d-flex align-items-center justify-content-between w-100">
        <a class="navbar-brand d-none d-lg-flex px-4" href="{{ route('home') }}">
            <span class="fs-2 text-primary">Loomi</span>
        </a>
    
        <form action="{{ route('products.index') }}" method="GET" 
            class="w-100 align-self-center" role="search">
            <input 
                type="search" 
                name="search" 
                class="form-control form-control-dark text-bg-dark" 
                placeholder="Search Products"
                aria-label="search"
                value="{{ request('search') }}">

            @foreach(request()->except('search') as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </form>

        <a href="{{ route('cart.index') }}" class="text-primary fs-4 px-3">
            <i class="bi bi-cart"></i>
        </a>
    </div>
</nav>