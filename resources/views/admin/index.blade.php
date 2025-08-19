@extends('admin.components.default')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid py-4">
    <h1 class="h4 fw-bold mb-4" data-aos="fade-right" data-aos-duration="700">Dashboard</h1>

    <!-- Top Stats -->
    <div class="row g-3 mb-4">
        @php
            $stats = [
                ['icon' => 'bi-box-seam', 'label' => 'Products', 'value' => $productsCount ?? 0, 'color' => 'primary'],
                ['icon' => 'bi-receipt', 'label' => 'Orders', 'value' => $ordersCount ?? 0, 'color' => 'success'],
                ['icon' => 'bi-people', 'label' => 'Users', 'value' => $usersCount ?? 0, 'color' => 'info'],
                ['icon' => 'bi-graph-up', 'label' => 'Revenue', 'value' => $revenue ?? 0 , 'color' => 'warning'],
            ];
        @endphp

        @foreach($stats as $i => $stat)
            <div class="col-6 col-md-3" data-aos="zoom-in" data-aos-delay="{{ $i * 150 }}" data-aos-duration="700">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <i class="bi {{ $stat['icon'] }} fs-1 text-{{ $stat['color'] }}"></i>
                        <h6 class="mt-2 text-muted">{{ $stat['label'] }}</h6>
                        <p class="fs-5 fw-bold mb-0 counter" 
                            data-target="{{ $stat['value'] }}" 
                            @if($stat['label'] === 'Revenue') data-revenue="true" @endif>
                            0
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Charts & Ratings -->
    <div class="row g-3 mb-4">
        <!-- Sales Trend -->
        <div class="col-lg-8" data-aos="zoom-in" data-aos-duration="800">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold small text-muted">
                    Sales Trend
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <!-- Recent Ratings -->
        <div class="col-lg-4" data-aos="zoom-in" data-aos-duration="800">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold small text-muted">
                    Recent Ratings
                </div>
                <div class="card-body small">
                    @forelse($topRatings as $rating)
                        <div class="mb-3 pb-2 border-bottom" data-aos="fade-up" data-aos-delay="100">
                            <strong class="d-block">{{ ucfirst($rating['product_title']) }}</strong>
                            <span class="text-warning d-block mb-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $rating['stars'] ? '-fill' : '' }}"></i>
                                @endfor
                            </span>
                            <span class="text-muted">by {{ $rating['user_name'] }}</span>
                        </div>
                    @empty
                        <p class="text-muted">No ratings yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Categories & Orders -->
    <div class="row g-3">
        <!-- Categories -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold small text-muted">
                    Categories
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('category.store') }}" class="d-flex mb-3">
                        @csrf
                        <input type="text" name="name" class="form-control me-2" placeholder="New Category" required>
                        <button class="btn btn-sm btn-primary">Add</button>
                    </form>

                    <ul class="list-group small">
                        @foreach($categories as $category)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $category->name }}
                                <form method="POST" action="{{ route('category.destroy', $category) }}">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold small text-muted">
                    Recent Orders
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td class="text-success">₱ {{ number_format($order->total_amount, 2) }}</td>
                                    <td><span class="badge bg-info">{{ ucfirst($order->status->name) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted text-center py-3">No recent orders.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


<!-- Chart.js + AOS -->
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />

<script>
    // Init Chart.js
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($salesDates ?? []) !!},
            datasets: [{
                label: 'Sales',
                data: {!! json_encode($salesData ?? []) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true }
            }
        }
    });

    // Init AOS
    AOS.init({
        once: true, // animate only once
        easing: 'ease-in-out',
        offset: 60
    });
</script>
<script>
    // Counter animation
    const counters = document.querySelectorAll('.counter');
    const speed = 100; // lower is faster

    const animateCounters = () => {
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const isRevenue = counter.dataset.revenue === "true"; // check if it's revenue
            let count = 0;

            const updateCount = () => {
                const increment = Math.ceil(target / speed);

                if (count < target) {
                    count += increment;
                    if (count > target) count = target;

                    counter.innerText = isRevenue 
                        ? '₱ ' + count.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                        : count.toLocaleString();

                    setTimeout(updateCount, 20);
                } else {
                    counter.innerText = isRevenue
                        ? '₱ ' + target.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                        : target.toLocaleString();
                }
            };

            updateCount();
        });
    };

    // Run animation when DOM ready
    document.addEventListener('DOMContentLoaded', () => {
        AOS.init({
            once: true,
            easing: 'ease-in-out',
            offset: 60
        });
        animateCounters();
    });
</script>
@endsection
