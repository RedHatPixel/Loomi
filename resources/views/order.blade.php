@extends('components.default')

@section('title', "My Orders")

@section('nav')
@include('includes.profile')
@endsection

@section('sidebar')
@include('includes.user')
@endsection

@section('mainbar')
<h2 class="lead text-start display-6 mt-4">My Orders</h2>

{{-- ACTIVE ORDERS --}}
@if($active->isEmpty())
    <div class="alert alert-info mt-3">
        <i class="bi bi-bag-dash"></i> You have no active orders.
    </div>
@else
    <h4 class="mt-4 mb-3">Active Orders</h4>
    @foreach($active as $order)
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="card-title mb-0">Order #{{ $order->id }}</h5>
                    <span class="badge 
                        bg-{{ $order->status->name === 'pending' ? 'warning' : 
                            ($order->status->name === 'processing' ? 'info' : 'primary') }}">
                        {{ ucfirst($order->status->name) }}
                    </span>
                </div>

                {{-- Products in Order --}}
                <div class="table-responsive mb-3">
                    <table class="table table-borderless table-hover mb-0">
                        <thead>
                            <tr class="text-muted">
                                <th>Image</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                @include('components.order', ['item' => $item])
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Order Details --}}
                <div class="row d-flex flex-wrap justify-content-between mt-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Name:</strong> {{ $order->name ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Contact:</strong> {{ $order->contact_number ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Address:</strong> {{ $order->address ?? 'N/A' }}</p>
                        @if($order->notes)
                            <p class="mb-1"><strong>Notes:</strong> {{ $order->notes }}</p>
                        @endif
                    </div>
                    <div class="col-md-6 text-end">
                        <p class="mb-1 text-success">
                            <strong>Total Amount:</strong> ₱ {{ number_format($order->total_amount, 2) }}
                        </p>
                        <p><strong>Ordered on:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                        @if($order->status->name === 'pending')
                            <form action="{{ route('orders.cancel', $order) }}" method="POST" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-x-circle"></i> Cancel Order
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

{{-- ORDER HISTORY --}}
@if($history->isNotEmpty())
    <h4 class="mt-5 mb-3">Order History</h4>
    @foreach($history as $order)
        <div class="card border-secondary shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="card-title mb-0">Order #{{ $order->id }}</h5>
                    <span class="badge 
                        bg-{{ $order->status->name === 'received' ? 'success' : 'danger' }}">
                        {{ ucfirst($order->status->name) }}
                    </span>
                </div>

                {{-- Products in Order --}}
                <div class="table-responsive mb-3">
                    <table class="table table-borderless table-hover mb-0">
                        <thead>
                            <tr class="text-muted">
                                <th>Image</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                @include('components.order', ['item' => $item])
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row d-flex flex-wrap justify-content-between mt-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Name:</strong> {{ $order->name ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Contact:</strong> {{ $order->contact_number ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Address:</strong> {{ $order->address ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <p class="mb-1"><strong>Total Amount:</strong> ₱ {{ number_format($order->total_amount, 2) }}</p>
                        <p class="mb-0"><strong>Ordered on:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                        <div class="d-flex gap-2 justify-content-end">
                            <form action="{{ route('orders.destroy', $order) }}" method="POST" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                            @if($order->status->name !== 'denied')
                                <form action="{{ route('orders.restore', $order) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-arrow-repeat"></i> ReOrder
                                    </button>
                                </form>
                            @endif
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

@endsection
