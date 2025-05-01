@extends('Layout.app')
@section('title', 'Sales Interface')
@include('Components.NavBar.navbar')

@section('content')
    <div class="container-fluid py-4" style="background-color: #ffffff; min-height: calc(100vh - 56px);">
        <div class="row mb-4 text-center">
            <div class="col-12">
                <h2 class="dashboard-title">
                    <i class="fa-solid fa-money-bill-trend-up me-2"></i>Sales Board
                </h2>
                <p class="text-muted">Track your business performance and sales trends</p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-card-content">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-details">
                            <h6>Today's Sales</h6>
                            <h3>₱{{ number_format($dailySales->sum('total_sales'), 2) }}</h3>
                            <div class="stat-meta">
                                <span class="badge bg-light text-dark me-2">
                                    <i class="fas fa-shopping-bag"></i> {{ $dailySales->sum('transaction_count') ?? 0 }}
                                    orders
                                </span>
                            </div>
                            <div class="delivery-stats mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-motorcycle"></i> {{ $dailySales->sum('delivery_count') ?? 0 }}
                                    deliveries
                                    <i class="fas fa-store ms-2"></i> {{ $dailySales->sum('pickup_count') ?? 0 }} pickups
                                </small>
                            </div>
                            <p class="trend mt-2">
                                @if ($dailyTrend > 0)
                                    <i class="fas fa-arrow-up text-success"></i>
                                    <span class="text-success">+{{ number_format($dailyTrend, 1) }}%</span>
                                @elseif($dailyTrend < 0)
                                    <i class="fas fa-arrow-down text-danger"></i>
                                    <span class="text-danger">{{ number_format($dailyTrend, 1) }}%</span>
                                @else
                                    <i class="fas fa-equals text-warning"></i>
                                    <span class="text-warning">0%</span>
                                @endif
                                <span class="ms-1">vs. yesterday</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-card-content">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-week"></i>
                        </div>
                        <div class="stat-details">
                            <h6>Weekly Sales</h6>
                            <h3>₱{{ number_format($weeklySales->sum('total_sales'), 2) }}</h3>
                            <div class="stat-meta">
                                <span class="badge bg-light text-dark me-2">
                                    <i class="fas fa-shopping-bag"></i> {{ $weeklySales->sum('transaction_count') ?? 0 }}
                                    orders
                                </span>
                            </div>
                            <div class="delivery-stats mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-motorcycle"></i> {{ $weeklySales->sum('delivery_count') ?? 0 }}
                                    deliveries
                                    <i class="fas fa-store ms-2"></i> {{ $weeklySales->sum('pickup_count') ?? 0 }} pickups
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-card-content">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="stat-details">
                            <h6>Monthly Sales</h6>
                            <h3>₱{{ number_format($monthlySales->sum('total_sales'), 2) }}</h3>
                            <div class="stat-meta">
                                <span class="badge bg-light text-dark me-2">
                                    <i class="fas fa-shopping-bag"></i> {{ $monthlySales->sum('transaction_count') ?? 0 }}
                                    orders
                                </span>
                            </div>
                            <div class="delivery-stats mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-motorcycle"></i> {{ $monthlySales->sum('delivery_count') ?? 0 }}
                                    deliveries
                                    <i class="fas fa-store ms-2"></i> {{ $monthlySales->sum('pickup_count') ?? 0 }} pickups
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-card-content">
                        <div class="stat-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="stat-details">
                            <h6>Yearly Sales</h6>
                            <h3>₱{{ number_format($yearlySales->sum('total_sales'), 2) }}</h3>
                            <div class="stat-meta">
                                <span class="badge bg-light text-dark me-2">
                                    <i class="fas fa-shopping-bag"></i> {{ $yearlySales->sum('transaction_count') ?? 0 }}
                                    orders
                                </span>
                            </div>
                            <div class="delivery-stats mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-motorcycle"></i> {{ $yearlySales->sum('delivery_count') ?? 0 }}
                                    deliveries
                                    <i class="fas fa-store ms-2"></i> {{ $yearlySales->sum('pickup_count') ?? 0 }} pickups
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="chart-card">
                    <div class="chart-card-header">
                        <h5><i class="fas fa-chart-line me-2"></i>Daily Sales Trend</h5>
                    </div>
                    <div class="chart-body">
                        <canvas id="dailyChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="chart-card">
                    <div class="chart-card-header">
                        <h5><i class="fas fa-chart-line me-2"></i>Weekly Sales Trend</h5>
                    </div>
                    <div class="chart-body">
                        <canvas id="weeklyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="chart-card">
                    <div class="chart-card-header">
                        <h5><i class="fas fa-chart-line me-2"></i>Monthly Sales Trend</h5>
                    </div>
                    <div class="chart-body">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="chart-card">
                    <div class="chart-card-header">
                        <h5><i class="fas fa-chart-line me-2"></i>Yearly Sales Trend</h5>
                    </div>
                    <div class="chart-body">
                        <canvas id="yearlyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const createChart = (elementId, labels, data, label) => {
            const ctx = document.getElementById(elementId).getContext('2d');
            return new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        borderColor: '#6b4226',
                        backgroundColor: 'rgba(107, 66, 38, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#6b4226',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#6b4226',
                            bodyColor: '#6b4226',
                            borderColor: '#6b4226',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return '₱' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(107, 66, 38, 0.1)',
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toLocaleString();
                                },
                                padding: 10
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                padding: 10
                            }
                        }
                    }
                }
            });
        };

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all charts
            createChart(
                'dailyChart',
                {!! json_encode($dailySales->pluck('date')) !!},
                {!! json_encode($dailySales->pluck('total_sales')) !!},
                'Daily Sales'
            );

            createChart(
                'weeklyChart',
                {!! json_encode($weeklySales->pluck('date')) !!},
                {!! json_encode($weeklySales->pluck('total_sales')) !!},
                'Weekly Sales'
            );

            createChart(
                'monthlyChart',
                {!! json_encode($monthlySales->pluck('date')) !!},
                {!! json_encode($monthlySales->pluck('total_sales')) !!},
                'Monthly Sales'
            );

            createChart(
                'yearlyChart',
                {!! json_encode($yearlySales->pluck('date')) !!},
                {!! json_encode($yearlySales->pluck('total_sales')) !!},
                'Yearly Sales'
            );
        });
    </script>

    <style>
        .dashboard-title {
            color: #6b4226;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(107, 66, 38, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 25px rgba(107, 66, 38, 0.15);
        }

        .stat-card-content {
            padding: 1.5rem;
            display: flex;
            align-items: flex-start;
        }

        .stat-icon {
            background: rgba(107, 66, 38, 0.1);
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .stat-icon i {
            color: #6b4226;
            font-size: 1.5rem;
        }

        .stat-details {
            flex-grow: 1;
        }

        .stat-details h6 {
            color: #6b4226;
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
            margin-bottom: 0.5rem;
        }

        .stat-meta .badge {
            font-weight: 500;
            padding: 0.5em 0.75em;
        }

        .delivery-stats {
            font-size: 0.75rem;
            color: #666;
        }

        .delivery-stats i {
            color: #6b4226;
            margin-right: 0.25rem;
        }

        .trend {
            font-size: 0.75rem;
            margin: 0;
        }

        .trend i {
            margin-right: 0.25rem;
        }

        .chart-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(107, 66, 38, 0.1);
            padding: 1.5rem;
            height: 100%;
            transition: transform 0.3s ease;
        }

        .chart-card:hover {
            transform: translateY(-3px);
        }

        .chart-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .chart-card-header h5 {
            color: #6b4226;
            font-weight: 600;
            margin: 0;
        }

        .chart-body {
            position: relative;
            height: 300px;
        }

        @media (max-width: 768px) {
            .container-fluid {
                padding: 1rem !important;
            }

            .stat-card-content {
                flex-direction: column;
                text-align: center;
            }

            .stat-icon {
                margin: 0 auto 1rem auto;
            }

            .chart-card {
                margin-bottom: 1.5rem !important;
            }
        }
    </style>
@endsection
