@extends('Layout.app')
@section('title', 'Dashboard')
@include('Components.NavBar.navbar')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="dashboard-title">
                    <i class="fas fa-chart-line me-2"></i>Business Dashboard
                </h2>
                <p class="text-muted">Monitor your business performance and key metrics</p>
            </div>
        </div>

        <!-- Today's Overview Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card border">
                                    <div class="card-body">
                                        <h5 class="card-title text-muted mb-3">Today's Revenue</h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h2 class="mb-0">₱{{ number_format($todayRevenue, 2) }}</h2>
                                                <p class="text-muted mb-0">{{ $todayCount }} orders</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <span class="badge bg-primary rounded-pill">Today</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border">
                                    <div class="card-body">
                                        <h5 class="card-title text-muted mb-3">This Week's Revenue</h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h2 class="mb-0">₱{{ number_format($weeklyRevenue, 2) }}</h2>
                                                <p class="text-muted mb-0">{{ $weeklyCount }} orders</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <span class="badge bg-success rounded-pill">This Week</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border">
                                    <div class="card-body">
                                        <h5 class="card-title text-muted mb-3">Total Orders</h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h2 class="mb-0">{{ $totalTransactions }}</h2>
                                                <p class="text-muted mb-0">{{ $completedCount }} completed</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <span class="badge bg-info rounded-pill">Total</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border">
                                    <div class="card-body">
                                        <h5 class="card-title text-muted mb-3">Completion Rate</h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h2 class="mb-0">
                                                    {{ $totalTransactions > 0 ? number_format(($completedCount / $totalTransactions) * 100, 1) : 0 }}%
                                                </h2>
                                                <p class="text-muted mb-0">{{ $completedCount }} completed</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <span class="badge bg-warning rounded-pill">Rate</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Statistics Cards -->
        <div class="row mb-4">
            <!-- Total Revenue Card -->
            <div class="col-md-3 mb-3">
                <div class="stat-card revenue">
                    <div class="stat-card-content">
                        <div class="stat-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-details">
                            <h6>Total Revenue</h6>
                            <h3>₱{{ number_format($totalSold, 2) }}</h3>
                            <div class="stat-meta">
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-shopping-cart"></i> From {{ $completedCount }} orders
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Statistics -->
            <div class="col-md-3 mb-3">
                <div class="stat-card transactions">
                    <div class="stat-card-content">
                        <div class="stat-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="stat-details">
                            <h6>Transactions</h6>
                            <h3>{{ $totalTransactions }}</h3>
                            <div class="stat-meta">
                                <span class="badge bg-success me-1">{{ $completedCount }} completed</span>
                                <span class="badge bg-danger">{{ $cancelledCount }} cancelled</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery Statistics -->
            <div class="col-md-3 mb-3">
                <div class="stat-card delivery">
                    <div class="stat-card-content">
                        <div class="stat-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="stat-details">
                            <h6>Delivery Stats</h6>
                            <h3>{{ $deliveryCount }}</h3>
                            <div class="stat-meta">
                                <span class="badge bg-info me-1">
                                    <i class="fas fa-truck"></i> Deliveries
                                </span>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-store"></i> {{ $pickupCount }} Pickups
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Base -->
            <div class="col-md-3 mb-3">
                <div class="stat-card users">
                    <div class="stat-card-content">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-details">
                            <h6>Customer Base</h6>
                            <h3>{{ $totalUsers }}</h3>
                            <div class="stat-meta">
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-user-check"></i> Registered users
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Statistics Section -->
        <div class="row mb-4">
            <!-- Revenue Breakdown -->
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Revenue Breakdown</h4>
                        <div class="mb-4">
                            <h5 class="text-muted mb-3">Beverage Sales</h5>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-primary" role="progressbar"
                                    style="width: {{ $totalSold > 0 ? (($totalSold - $merchantFeeTotal) / $totalSold) * 100 : 0 }}%">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span class="text-muted">₱{{ number_format($totalSold - $merchantFeeTotal, 2) }}</span>
                                <span
                                    class="text-muted">{{ $totalSold > 0 ? number_format((($totalSold - $merchantFeeTotal) / $totalSold) * 100, 1) : 0 }}%</span>
                            </div>
                        </div>
                        <div>
                            <h5 class="text-muted mb-3">Delivery Fees</h5>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $totalSold > 0 ? ($merchantFeeTotal / $totalSold) * 100 : 0 }}%">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span class="text-muted">₱{{ number_format($merchantFeeTotal, 2) }}</span>
                                <span
                                    class="text-muted">{{ $totalSold > 0 ? number_format(($merchantFeeTotal / $totalSold) * 100, 1) : 0 }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Trends -->
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Transaction Trends</h4>
                        <div class="mb-4">
                            <h5 class="text-muted mb-3">Delivery Rate</h5>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-warning" role="progressbar"
                                    style="width: {{ $completedCount > 0 ? ($deliveryCount / $completedCount) * 100 : 0 }}%">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span class="text-muted">{{ $deliveryCount }} deliveries</span>
                                <span
                                    class="text-muted">{{ $completedCount > 0 ? number_format(($deliveryCount / $completedCount) * 100, 1) : 0 }}%</span>
                            </div>
                        </div>
                        <div>
                            <h5 class="text-muted mb-3">Pickup Rate</h5>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-info" role="progressbar"
                                    style="width: {{ $completedCount > 0 ? ($pickupCount / $completedCount) * 100 : 0 }}%">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span class="text-muted">{{ $pickupCount }} pickups</span>
                                <span
                                    class="text-muted">{{ $completedCount > 0 ? number_format(($pickupCount / $completedCount) * 100, 1) : 0 }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .dashboard-title {
            color: #2d1810;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-card-content {
            padding: 1.5rem;
            display: flex;
            align-items: flex-start;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .revenue .stat-icon {
            background: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .transactions .stat-icon {
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .delivery .stat-icon {
            background: rgba(13, 202, 240, 0.1);
            color: #0dcaf0;
        }

        .users .stat-icon {
            background: rgba(102, 16, 242, 0.1);
            color: #6610f2;
        }

        .stat-icon i {
            font-size: 1.5rem;
        }

        .stat-details {
            flex-grow: 1;
        }

        .stat-details h6 {
            color: #6c757d;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .stat-details h3 {
            color: #2d1810;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-meta {
            margin-top: 0.5rem;
        }

        .stat-meta .badge {
            font-weight: 500;
            padding: 0.5em 0.75em;
        }

        .card-header {
            padding: 1rem 1.5rem;
        }

        .revenue-stats,
        .trend-stats,
        .popular-products {
            padding: 0.5rem;
        }

        .progress {
            background-color: #f8f9fa;
            border-radius: 10px;
        }

        .progress-bar {
            border-radius: 10px;
        }

        .revenue-item,
        .trend-item,
        .product-item {
            padding: 0.5rem;
        }

        .daily-stat {
            padding: 1rem;
            border-right: 1px solid #dee2e6;
        }

        .daily-stat:last-child {
            border-right: none;
        }

        .daily-stat h6 {
            color: #6c757d;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .daily-stat h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .daily-stat small {
            font-size: 0.75rem;
        }

        @media (max-width: 768px) {
            .daily-stat {
                border-right: none;
                border-bottom: 1px solid #dee2e6;
                margin-bottom: 1rem;
            }

            .daily-stat:last-child {
                border-bottom: none;
                margin-bottom: 0;
            }
        }
    </style>
@endsection
