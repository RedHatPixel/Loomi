@extends('admin.components.default')

@section('title', 'Order #'.$order->id)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 fw-bold mb-0">Order #{{ $order->id }}</h1>
        <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    {{-- ===================== ORDER SUMMARY ===================== --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <p class="text-muted mb-1">User</p>
                    <p class="fw-semibold my-0">{{ $order->user->name }}</p>
                    <p class="text-muted my-0">{{ $order->user->email }}</p>
                </div>
                <div class="col-md-3">
                    <p class="mb-1 text-muted">Status</p>
                    <span class="badge bg-info rounded-pill px-3 py-2">{{ ucfirst($order->status->name) }}</span>
                </div>
                <div class="col-md-3">
                    <p class="mb-1 text-muted">Total Amount</p>
                    <p class="fw-bold text-success">₱{{ number_format($order->total_amount, 2) }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1 text-muted">Placed At</p>
                    <p>{{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1 text-muted">Last Updated</p>
                    <p>{{ $order->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== ORDER ITEMS ===================== --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 fw-semibold">
            <i class="bi bi-basket me-2 text-success"></i> Items
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th class="text-end">Price</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td style="min-width: 250px;">{{ $item->product->title }}</td>
                                <td style="min-width: 120px;" class="text-end text-success">
                                    ₱ {{ number_format($item->price_at_sale, 2) }}
                                </td>
                                <td style="min-width: 100px;" class="text-center">{{ $item->quantity }}</td>
                                <td style="min-width: 120px;" class="text-end fw-semibold text-success">
                                    ₱ {{ number_format($item->quantity * $item->price_at_sale, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">No items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===================== ACTIONS ===================== --}}
    <div class="d-flex justify-content-end">
        <form action="{{ route('orders.destroy', $order) }}" method="POST" class="ms-2">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">
                <i class="bi bi-trash"></i> Delete
            </button>
        </form>
    </div>
</div>
@endsection
