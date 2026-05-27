<div>
    <div class="dashboard-container">
        <!-- Header Section -->
        <div class="page-header mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h2 class="page-title">
                        <i class="bi bi-speedometer2 me-2"></i>
                        Dashboard
                    </h2>
                    <p class="page-subtitle mb-0">Welcome back, {{ $partner->company_name ?? $partner->name ?? 'Partner' }}!</p>
                </div>
                
                <!-- Date Range Filter -->
                <div class="date-range-selector mt-2 mt-sm-0">
                    <div class="btn-group flex-wrap" role="group">
                        <button type="button" 
                            class="btn btn-sm {{ $currentDateRange == 'today' ? 'btn-primary' : 'btn-outline-primary' }}"
                            wire:click="setDateRange('today')">
                            Today
                        </button>
                        <button type="button" 
                            class="btn btn-sm {{ $currentDateRange == 'this_week' ? 'btn-primary' : 'btn-outline-primary' }}"
                            wire:click="setDateRange('this_week')">
                            This Week
                        </button>
                        <button type="button" 
                            class="btn btn-sm {{ $currentDateRange == 'this_month' ? 'btn-primary' : 'btn-outline-primary' }}"
                            wire:click="setDateRange('this_month')">
                            This Month
                        </button>
                        <button type="button" 
                            class="btn btn-sm {{ $currentDateRange == 'this_year' ? 'btn-primary' : 'btn-outline-primary' }}"
                            wire:click="setDateRange('this_year')">
                            This Year
                        </button>
                        <button type="button" 
                            class="btn btn-sm {{ $currentDateRange == 'all_time' ? 'btn-primary' : 'btn-outline-primary' }}"
                            wire:click="setDateRange('all_time')">
                            All Time
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Metrics Cards -->
        <div class="stats-grid mb-4">
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-value">{{ number_format($totalParcels) }}</span>
                    <span class="stat-label">Total Parcels</span>
                </div>
            </div>

            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-value">{{ number_format($completedParcels) }}</span>
                    <span class="stat-label">Delivered</span>
                </div>
                <div class="stat-trend positive">
                    {{ $deliverySuccessRate }}% success
                </div>
            </div>

            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-value">{{ number_format($activeParcels) }}</span>
                    <span class="stat-label">In Progress</span>
                </div>
            </div>

            <div class="stat-card info">
                <div class="stat-icon">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-value">KES {{ number_format($totalEarnings, 2) }}</span>
                    <span class="stat-label">Total Earnings</span>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card dashboard-card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-pie-chart me-2"></i>
                            Parcel Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart" height="200"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card dashboard-card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-graph-up me-2"></i>
                            Earnings (Last 7 Days)
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="earningsChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings Breakdown & Payment Status -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card dashboard-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-calculator me-2"></i>
                            Earnings Breakdown
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($earningsByType as $type => $amount)
                        <div class="breakdown-item mb-3">
                            <div class="breakdown-header">
                                <span class="breakdown-label">
                                    <i class="bi {{ $this->getTypeIcon($type) }} me-2"></i>
                                    {{ ucfirst($type) }}
                                </span>
                                <span class="breakdown-amount">KES {{ number_format($amount, 2) }}</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $totalEarnings > 0 ? ($amount / $totalEarnings) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                        
                        <div class="breakdown-item mt-4 pt-3 border-top">
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-center">
                                        <span class="text-muted d-block small">Pending</span>
                                        <span class="fw-bold text-warning">KES {{ number_format($pendingEarnings, 2) }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <span class="text-muted d-block small">Completed</span>
                                        <span class="fw-bold text-success">KES {{ number_format($completedEarnings, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card dashboard-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-credit-card me-2"></i>
                            Payment Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="payment-stats">
                            @foreach($parcelsByPaymentStatus as $status => $count)
                            @if($count > 0)
                            <div class="payment-item">
                                <div class="payment-info">
                                    <span class="payment-label">{{ ucfirst($status) }}</span>
                                    <span class="payment-count">{{ number_format($count) }} parcels</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" style="width: {{ $totalParcels > 0 ? ($count / $totalParcels) * 100 : 0 }}%; background: {{ $this->getPaymentColor($status) }}"></div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
            <div class="col-md-6">
                <div class="card dashboard-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clock-history me-2"></i>
                            Recent Parcels
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @forelse($recentParcels as $parcel)
                        <div class="recent-item">
                            <div class="recent-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="recent-content">
                                <div class="recent-title">
                                    <a href="{{ route('partners.parcels.view', $parcel->id) }}" class="fw-bold">
                                        {{ $parcel->parcel_id }}
                                    </a>
                                    <span class="status-badge" style="background: {{ $this->getStatusColor($parcel->current_status) }}20; color: {{ $this->getStatusColor($parcel->current_status) }}">
                                        {{ ucfirst(str_replace('_', ' ', $parcel->current_status)) }}
                                    </span>
                                </div>
                                <div class="recent-details">
                                    <span><i class="bi bi-cash"></i> KES {{ number_format($parcel->total_amount, 2) }}</span>
                                </div>
                                <div class="recent-time">
                                    {{ $parcel->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="empty-state p-4">
                            <p class="text-muted text-center mb-0">No recent parcels</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card dashboard-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-wallet2 me-2"></i>
                            Recent Payouts
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @forelse($recentPayouts as $payout)
                        <div class="recent-item">
                            <div class="recent-icon">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                            <div class="recent-content">
                                <div class="recent-title">
                                    <span class="fw-bold">{{ ucfirst($payout->type) }} Payout</span>
                                    <span class="status-badge" style="background: {{ $this->getPayoutColor($payout->status) }}20; color: {{ $this->getPayoutColor($payout->status) }}">
                                        {{ ucfirst($payout->status) }}
                                    </span>
                                </div>
                                <div class="recent-details">
                                    <span><i class="bi bi-box"></i> {{ $payout->parcel->parcel_id ?? 'N/A' }}</span>
                                    <span class="ms-3"><i class="bi bi-cash"></i> KES {{ number_format($payout->amount, 2) }}</span>
                                </div>
                                <div class="recent-time">
                                    {{ $payout->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="empty-state p-4">
                            <p class="text-muted text-center mb-0">No recent payouts</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .dashboard-container {
            padding: 1.5rem;
            background: #f3f4f6;
            min-height: 100vh;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #4361ee, #3730a3);
            padding: 2rem;
            border-radius: 1.5rem;
            color: white;
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.2);
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            font-size: 1rem;
            opacity: 0.9;
        }

        /* Date Range Selector */
        .date-range-selector .btn-group .btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 1.25rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 1rem;
        }

        .stat-card.primary .stat-icon {
            background: rgba(67, 97, 238, 0.1);
            color: #4361ee;
        }

        .stat-card.success .stat-icon {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .stat-card.warning .stat-icon {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .stat-card.info .stat-icon {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .stat-content {
            flex: 1;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1f2937;
            line-height: 1.2;
            display: block;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #6b7280;
            display: block;
            margin-top: 0.25rem;
        }

        .stat-trend {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 2rem;
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            position: absolute;
            top: 1rem;
            right: 1rem;
        }

        /* Dashboard Cards */
        .dashboard-card {
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .dashboard-card .card-header {
            background: white;
            border-bottom: 2px solid #f3f4f6;
            padding: 1rem 1.25rem;
            font-weight: 600;
        }

        .dashboard-card .card-title {
            font-size: 1rem;
            color: #1f2937;
            margin-bottom: 0;
        }

        .dashboard-card .card-body {
            padding: 1.25rem;
        }

        /* Breakdown Items */
        .breakdown-item {
            margin-bottom: 1rem;
        }

        .breakdown-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }

        .breakdown-label {
            color: #6b7280;
        }

        .breakdown-amount {
            font-weight: 600;
            color: #1f2937;
        }

        .progress {
            height: 6px;
            background: #f3f4f6;
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-bar {
            background: linear-gradient(90deg, #4361ee, #3730a3);
            border-radius: 3px;
        }

        /* Payment Items */
        .payment-stats {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .payment-item {
            width: 100%;
        }

        .payment-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }

        .payment-label {
            font-weight: 500;
            color: #1f2937;
        }

        .payment-count {
            color: #6b7280;
        }

        /* Recent Activity */
        .recent-item {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            transition: background 0.3s ease;
        }

        .recent-item:hover {
            background: #f9fafb;
        }

        .recent-icon {
            width: 40px;
            height: 40px;
            background: rgba(67, 97, 238, 0.1);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4361ee;
            font-size: 1.2rem;
        }

        .recent-content {
            flex: 1;
        }

        .recent-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .recent-title a {
            color: #1f2937;
            text-decoration: none;
        }

        .recent-title a:hover {
            color: #4361ee;
        }

        .status-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 2rem;
            font-weight: 500;
        }

        .recent-details {
            font-size: 0.8rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .recent-details i {
            margin-right: 0.25rem;
        }

        .recent-time {
            font-size: 0.7rem;
            color: #9ca3af;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .stat-value {
                font-size: 1.2rem;
            }

            .stat-icon {
                width: 2.5rem;
                height: 2.5rem;
                font-size: 1.2rem;
            }

            .page-header {
                padding: 1rem;
            }

            .page-title {
                font-size: 1.3rem;
            }

            .date-range-selector .btn-group {
                flex-wrap: wrap;
            }

            .date-range-selector .btn-group .btn {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }

            .recent-item {
                flex-direction: column;
            }

            .recent-icon {
                display: none;
            }
        }
    </style>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:initialized', () => {
        // Status Chart
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            const statusData = @json($parcelsByStatus);
            const statusLabels = Object.keys(statusData).filter(k => statusData[k] > 0);
            const statusCounts = Object.values(statusData).filter(v => v > 0);
            const statusColors = statusLabels.map(status => {
                const colors = {
                    'pending': '#f59e0b',
                    'assigned': '#8b5cf6',
                    'in_transit': '#3b82f6',
                    'delivered': '#10b981',
                    'cancelled': '#ef4444'
                };
                return colors[status] || '#6b7280';
            });
            
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusLabels.map(l => l.charAt(0).toUpperCase() + l.replace('_', ' ')),
                    datasets: [{
                        data: statusCounts,
                        backgroundColor: statusColors,
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { font: { size: 11 } }
                        }
                    }
                }
            });
        }

        // Earnings Chart
        const earningsCtx = document.getElementById('earningsChart');
        if (earningsCtx) {
            const earningsData = @json($last7DaysEarnings);
            new Chart(earningsCtx, {
                type: 'line',
                data: {
                    labels: earningsData.map(d => d.date),
                    datasets: [{
                        label: 'Earnings (KES)',
                        data: earningsData.map(d => d.earnings),
                        borderColor: '#4361ee',
                        backgroundColor: 'rgba(67, 97, 238, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'KES ' + context.raw.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'KES ' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>