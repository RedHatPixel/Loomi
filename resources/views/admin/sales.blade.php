@extends('admin.components.default')
@section('title', 'Manage Sales')

@section('content')
<div class="container-fluid py-4 w-100">
    <h1 class="h3 mb-3">Sales</h1>

    {{-- 🔎 Search + Filters --}}
    <div class="row g-2 mb-3 align-items-center">
        <form method="GET" action="{{ route('admin.sales') }}" class="col-md-10">
            <div class="row g-2">
                <div class="col-12 col-md-4">
                    <input type="text" name="search" class="form-control"
                        placeholder="Search by product or username"
                        value="{{ request('search') }}">
                </div>

                <div class="col-6 col-md-3">
                    <input type="date" name="date_from" class="form-control"
                        value="{{ request('date_from') }}">
                </div>

                <div class="col-6 col-md-3">
                    <input type="date" name="date_to" class="form-control"
                        value="{{ request('date_to') }}">
                </div>

                <div class="col-6 col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>

        <form method="GET" action="{{ route('admin.sales') }}" class="col-6 col-md-2">
            <button type="submit" class="btn btn-outline-secondary w-100">Reset</button>
        </form>
    </div>

    {{-- 📊 Sales Table --}}
    <div class="table-responsive">
        <table class="table table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Buyer</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Sold At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                <tr>
                    <td>{{ $sale->id }}</td>
                    <td style="min-width: 250px;" class="fw-semibold">{{ $sale->product->title }}</td>
                    <td style="min-width: 250px;">{{ $sale->buyer->name }}</td>
                    <td style="min-width: 100px;">{{ $sale->quantity }}</td>
                    <td style="min-width: 120px;" class="text-success">
                        ₱ {{ number_format($sale->price_at_sale, 2) }}
                    </td>
                    <td style="min-width: 200px;">{{ $sale->sold_at->format('M d, Y h:i A') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No sales found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- 📌 Pagination --}}
    <div class="mt-3">
        {{ $sales->links() }}
    </div>
</div>
@endsection
