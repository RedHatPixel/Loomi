@extends('admin.components.default')
@section('title', 'Manage Products')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Products</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Product
        </a>
    </div>

    {{-- 🔎 Search + Filters --}}
    <div class="row g-2 mb-3 align-items-center">
        <form method="GET" action="{{ route('admin.products') }}" class="col-md-10">
            <div class="row g-2">
                {{-- Search by title --}}
                <div class="col-12 col-md-4">
                    <input type="text" name="search" class="form-control"
                        placeholder="Search by product name..."
                        value="{{ request('search') }}">
                </div>

                {{-- Owner filter --}}
                <div class="col-6 col-md-3">
                    <select name="owner" class="form-select">
                        <option value="">All Owners</option>
                        @foreach($admins as $owner)
                            <option value="{{ $owner->id }}" 
                                {{ request('owner') == $owner->id ? 'selected' : '' }}>
                                {{ $owner->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Stock filter --}}
                <div class="col-6 col-md-3">
                    <select name="stock" class="form-select">
                        <option value="">All Stock</option>
                        <option value="in" {{ request('stock') == 'in' ? 'selected' : '' }}>In Stock</option>
                        <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>

                {{-- Filter button --}}
                <div class="col-6 col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>

        {{-- Reset button --}}
        <form method="GET" action="{{ route('admin.products') }}" class="col-6 col-md-2">
            <button type="submit" class="btn btn-outline-secondary w-100">Reset</button>
        </form>
    </div>

    {{-- 📋 Products Table --}}
    <div class="table-responsive">
        <table class="table table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Owner</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id ?? '0' }}</td>
                        <td style="min-width: 250px;" class="fw-semibold">{{ $product->title ?? 'No Title' }}</td>
                        <td style="min-width: 120px;" class="text-success">
                            ₱ {{ number_format($product->price ?? 0, 2) }}
                        </td>
                        <td style="min-width: 100px;">
                            {{ $product->quantity > 0 ? $product->quantity : 'Out of Stock' }}
                        </td>
                        <td style="min-width: 120px;">{{ $product->creator->name ?? 'Unknown' }}</td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                @csrf 
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" 
                                    onclick="return confirm('Are you sure you want to delete this?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- 📑 Pagination --}}
    @if ($products->hasPages() && $products->count())
        <div class="py-4">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
