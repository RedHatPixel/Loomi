@extends('admin.components.default')

@section('title', 'Manage Orders')

@section('content')
<div class="container-fluid py-4">
    <h1 class="h3 mb-3">Orders</h1>

    {{-- ===================== PENDING ORDERS ===================== --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-warning text-dark fw-bold">
            <i class="bi bi-archive-fill fs-5 me-1"></i> Pending Orders
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Total</th>
                            <th>Placed At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingOrders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td style="min-width: 250px;">{{ $order->user->name }}</td>
                                <td style="min-width: 120px;" class="text-success">
                                    ₱ {{ number_format($order->total_amount, 2) }}
                                </td>
                                <td style="min-width: 120px;">{{ $order->created_at->format('M d, Y') }}</td>
                                <td style="min-width: 190px;" class="d-flex gap-2">
                                    <form action="{{ route('admin.orders.accept', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-check-circle"></i> Accept
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.orders.deny', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-x-circle"></i> Deny
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">No pending orders.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===================== DENIED ORDERS ===================== --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-danger fw-bold">
            <i class="bi bi-slash-circle-fill fs-5 me-1"></i> Denied Orders
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Total</th>
                            <th>Denied At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deniedOrders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td style="min-width: 250px;">{{ $order->user->name }}</td>
                                <td style="min-width: 150px;" class="text-danger">
                                    ₱ {{ number_format($order->total_amount, 2) }}
                                </td>
                                <td style="min-width: 120px;">{{ $order->updated_at->format('M d, Y') }}</td>
                                <td style="min-width: 220px;" class="d-flex gap-2">
                                    {{-- Reactivate (set back to pending) --}}
                                    <form action="{{ route('orders.restore', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-warning">
                                            <i class="bi bi-arrow-counterclockwise"></i> Reactivate
                                        </button>
                                    </form>

                                    {{-- Delete (permanent remove) --}}
                                    <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">No denied orders.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===================== ACTIVE ORDERS ===================== --}}
    <div class="card shadow-sm">
        <div class="card-header bg-info fw-bold">
            <i class="bi bi-truck-front-fill fs-5 me-1"></i> Active Orders
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table e table-bordered mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeOrders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td style="min-width: 250px;">{{ $order->user->name }}</td>
                                <td style="min-width: 120px;" class="text-success">
                                    ₱ {{ number_format($order->total_amount, 2) }}
                                </td>
                                <td style="min-width: 250px;">
                                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="status_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                            @foreach($activeStatuses as $status)
                                                <option value="{{ $status->id }}" 
                                                    {{ $order->status->id === $status->id ? 'selected' : '' }}>
                                                    {{ ucfirst($status->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td style="min-width: 150px;">{{ $order->updated_at->diffForHumans() }}</td>
                                <td class="d-flex gap-2">
                                    <a href="{{ route('admin.order', $order) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-search"></i>
                                    </a>
                                    <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @if($order->status->name === 'delivered')
                                        <form action="{{ route('sales.store', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-success">
                                                <i class="bi bi-receipt"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">No active orders.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
