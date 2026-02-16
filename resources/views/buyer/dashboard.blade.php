@extends('layout.master-mini')

@push('style')
    <style>
        .dashboard-stats-card {
            border: none !important;
            border-radius: 12px !important;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%) !important;
            color: white !important;
            box-shadow: 0 4px 15px rgba(26, 95, 63, 0.2) !important;
            transition: all 0.3s ease !important;
            overflow: hidden !important;
            position: relative !important;
        }

        .dashboard-stats-card::before {
            content: '' !important;
            position: absolute !important;
            top: -50% !important;
            right: -50% !important;
            width: 200% !important;
            height: 200% !important;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%) !important;
            pointer-events: none !important;
        }

        .dashboard-stats-card:hover {
            transform: translateY(-5px) !important;
            box-shadow: 0 8px 25px rgba(26, 95, 63, 0.3) !important;
        }

        .dashboard-stats-card.card-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2) !important;
        }

        .dashboard-stats-card.card-warning {
            background: linear-gradient(135deg, var(--secondary) 0%, #b8962f 100%) !important;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.2) !important;
        }

        .dashboard-stats-card.card-info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2) !important;
        }

        .stats-icon {
            font-size: 2.5rem !important;
            opacity: 0.9 !important;
        }

        .stats-value {
            font-size: 2rem !important;
            font-weight: 700 !important;
            margin: 8px 0 !important;
        }

        .stats-label {
            font-size: 0.95rem !important;
            opacity: 0.95 !important;
            font-weight: 500 !important;
        }

        .chart-card {
            border: none !important;
            border-radius: 12px !important;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08) !important;
            transition: all 0.3s ease !important;
        }

        .chart-card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12) !important;
        }

        .chart-card .card-body {
            padding: 1.5rem !important;
            min-height: 250px !important;
        }

        .chart-container {
            position: relative !important;
            height: 250px !important;
            max-height: 250px !important;
        }

        .chart-container canvas {
            max-height: 250px !important;
        }

        .chart-title {
            font-size: 1.1rem !important;
            font-weight: 600 !important;
            color: var(--primary) !important;
            margin-bottom: 1rem !important;
        }

        .table-modern {
            border-collapse: separate !important;
            border-spacing: 0 !important;
        }

        .table-modern thead th {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%) !important;
            color: white !important;
            border: none !important;
            font-weight: 600 !important;
            padding: 12px 15px !important;
            font-size: 0.9rem !important;
        }

        .table-modern thead th:first-child {
            border-top-right-radius: 8px !important;
        }

        .table-modern thead th:last-child {
            border-top-left-radius: 8px !important;
        }

        .table-modern tbody tr {
            transition: all 0.2s ease !important;
        }

        .table-modern tbody tr:hover {
            background-color: rgba(26, 95, 63, 0.05) !important;
            transform: scale(1.01) !important;
        }

        .table-modern tbody td {
            padding: 12px 15px !important;
            border-bottom: 1px solid #f0f0f0 !important;
            vertical-align: middle !important;
        }

        .badge-type {
            padding: 6px 12px !important;
            border-radius: 20px !important;
            font-size: 0.85rem !important;
            font-weight: 600 !important;
        }

        .badge-purchase {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: white !important;
        }

        .badge-sale {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            color: white !important;
        }

        .badge-status {
            padding: 6px 12px !important;
            border-radius: 20px !important;
            font-size: 0.85rem !important;
            font-weight: 600 !important;
        }

        .badge-completed {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: white !important;
        }

        .badge-pending {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
            color: white !important;
        }

        .badge-cancelled {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%) !important;
            color: white !important;
        }

        .date-time-cell {
            line-height: 1.4 !important;
        }

        .date-line {
            font-weight: 600 !important;
            color: var(--primary) !important;
        }

        .time-line {
            font-size: 0.85rem !important;
            color: #6b7280 !important;
        }

        .profile-card {
            border: none !important;
            border-radius: 12px !important;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08) !important;
            text-align: center !important;
            transition: all 0.3s ease !important;
        }

        .profile-card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12) !important;
        }

        .profile-avatar {
            width: 80px !important;
            height: 80px !important;
            border-radius: 50% !important;
            border: 4px solid var(--primary) !important;
            box-shadow: 0 4px 12px rgba(26, 95, 63, 0.2) !important;
        }
    </style>
@endpush

@section('content')
    <div class="content-wrapper container">
        <div class="section-header">
            <div class="card section-card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ __('لوحة المشتري') }}</h3>
                        <p class="text-muted mb-0">{{ __('عرض مقتنياتك ومتابعة العمليات الأخيرة') }}</p>
                    </div>
                    <div>
                        <a href="{{ route('marketplace.offers.index') }}"
                            class="btn btn-outline-primary">{{ __('استكشف العروض') }}</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card dashboard-stats-card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stats-label">{{ __('إجمالي الأسهم') }}</div>
                            <div class="stats-value">{{ number_format($stats['total_shares']) }}</div>
                        </div>
                        <div class="stats-icon">
                            <i class="mdi mdi-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card dashboard-stats-card card-success h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stats-label">{{ __('عدد العروض') }}</div>
                            <div class="stats-value">{{ $stats['total_offers'] }}</div>
                        </div>
                        <div class="stats-icon">
                            <i class="mdi mdi-briefcase-outline"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card dashboard-stats-card card-warning h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stats-label">{{ __('إجمالي الاستثمار') }}</div>
                            <div class="stats-value">{{ number_format($stats['total_invested'], 0) }}</div>
                        </div>
                        <div class="stats-icon">
                            <i class="mdi mdi-cash-multiple"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card dashboard-stats-card card-info h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stats-label">{{ __('العمليات المكتملة') }}</div>
                            <div class="stats-value">{{ $stats['completed_operations'] }}</div>
                        </div>
                        <div class="stats-icon">
                            <i class="mdi mdi-check-circle-outline"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Profile Card --}}
            <div class="col-lg-3 col-md-4 mb-3">
                <div class="card profile-card h-100">
                    <div class="card-body">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/images/sahmi.jpeg') }}"
                            alt="avatar" class="profile-avatar mb-3">
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="text-muted mb-3" style="font-size: 0.9rem;">{{ $user->email }}</p>
                        <a href="{{ route('buyer.profile') }}"
                            class="btn btn-sm btn-primary">{{ __('تعديل الملف الشخصي') }}</a>
                    </div>
                </div>
            </div>

            {{-- Charts --}}
            <div class="col-lg-5 col-md-8 mb-3">
                <div class="card chart-card h-100">
                    <div class="card-body">
                        <h5 class="chart-title">{{ __('توزيع العمليات حسب النوع') }}</h5>
                        <div class="chart-container" id="typeChartContainer">
                            <canvas id="operationsTypeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 mb-3">
                <div class="card chart-card h-100">
                    <div class="card-body">
                        <h5 class="chart-title">{{ __('حالة العمليات') }}</h5>
                        <div class="chart-container" id="statusChartContainer">
                            <canvas id="operationsStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Holdings Table --}}
        <div class="row">
            <div class="col-12 mb-3">
                <div class="card chart-card">
                    <div class="card-body">
                        <h5 class="chart-title">{{ __('مقتنيات الأسهم') }}</h5>
                        @if ($holdings->isEmpty())
                            <div class="alert alert-info mb-0">
                                <i class="mdi mdi-information-outline me-2"></i>{{ __('لا توجد مقتنيات حالياً') }}
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-modern mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('العرض') }}</th>
                                            <th>{{ __('الأسهم المملوكة') }}</th>
                                            <th>{{ __('سعر الشراء') }}</th>
                                            <th>{{ __('آخر عملية') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($holdings as $h)
                                            <tr>
                                                <td><strong>{{ $h->title }}</strong></td>
                                                <td><span class="badge badge-primary">{{ $h->shares_owned }}</span></td>
                                                <td>{{ number_format($h->avg_price_per_share ?? $h->price_per_share, 2) }}
                                                    {{ $h->currency }}</td>
                                                <td class="date-time-cell">
                                                    @if ($h->last_transaction_at)
                                                        <div class="date-line">
                                                            {{ \Carbon\Carbon::parse($h->last_transaction_at)->format('Y-m-d') }}
                                                        </div>
                                                        <div class="time-line">
                                                            {{ \Carbon\Carbon::parse($h->last_transaction_at)->format('H:i') }}
                                                        </div>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Operations Table --}}
        <div class="row">
            <div class="col-12 mb-3">
                <div class="card chart-card">
                    <div class="card-body">
                        <h5 class="chart-title">{{ __('العمليات الأخيرة') }}</h5>
                        @if ($operations->isEmpty())
                            <div class="alert alert-info mb-0">
                                <i class="mdi mdi-information-outline me-2"></i>{{ __('لا توجد عمليات') }}
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-modern mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('العرض') }}</th>
                                            <th>{{ __('النوع') }}</th>
                                            <th>{{ __('الأسهم') }}</th>
                                            <th>{{ __('المبلغ') }}</th>
                                            <th>{{ __('الحالة') }}</th>
                                            <th>{{ __('التاريخ') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($operations as $op)
                                            <tr>
                                                <td>{{ $op->id }}</td>
                                                <td><strong>{{ $op->title }}</strong></td>
                                                <td>
                                                    <span
                                                        class="badge-type badge-{{ $op->type }}">{{ __('operations.type.' . $op->type) }}</span>
                                                </td>
                                                <td><span class="badge badge-primary">{{ $op->shares_count }}</span></td>
                                                <td>{{ number_format($op->amount_total, 2) }} {{ $op->currency }}</td>
                                                <td>
                                                    <span
                                                        class="badge-status badge-{{ $op->status }}">{{ __('operations.status.' . $op->status) }}</span>
                                                </td>
                                                <td class="date-time-cell">
                                                    @if ($op->created_at)
                                                        <div class="date-line">
                                                            {{ \Carbon\Carbon::parse($op->created_at)->format('Y-m-d') }}
                                                        </div>
                                                        <div class="time-line">
                                                            {{ \Carbon\Carbon::parse($op->created_at)->format('H:i') }}
                                                        </div>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $operations->links('pagination.marketplace') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Operations Type Chart
            const typeContainer = document.getElementById('typeChartContainer');
            const typeChartEl = document.getElementById('operationsTypeChart');

            if (typeChartEl && typeContainer) {
                const typeData = @json($operationsByType ?? []);

                if (typeData && Object.keys(typeData).length > 0) {
                    const typeLabels = Object.keys(typeData).map(key => {
                        const translations = {
                            'purchase': '{{ __('operations.type.purchase') }}',
                            'sale': '{{ __('operations.type.sale') }}',
                            'transfer': '{{ __('operations.type.transfer') }}'
                        };
                        return translations[key] || key;
                    });
                    const typeValues = Object.values(typeData);

                    new Chart(typeChartEl.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: typeLabels,
                            datasets: [{
                                data: typeValues,
                                backgroundColor: [
                                    'rgba(26, 95, 63, 0.8)',
                                    'rgba(239, 68, 68, 0.8)',
                                    'rgba(59, 130, 246, 0.8)'
                                ],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        font: {
                                            family: 'Cairo, sans-serif',
                                            size: 12
                                        },
                                        padding: 15
                                    }
                                }
                            }
                        }
                    });
                } else {
                    typeContainer.innerHTML =
                        '<div class="alert alert-info mb-0 d-flex align-items-center justify-content-center" style="height: 200px;"><i class="mdi mdi-information-outline me-2"></i>لا توجد بيانات لعرضها</div>';
                }
            }

            // Operations Status Chart
            const statusContainer = document.getElementById('statusChartContainer');
            const statusChartEl = document.getElementById('operationsStatusChart');

            if (statusChartEl && statusContainer) {
                const statusData = @json($operationsByStatus ?? []);

                if (statusData && Object.keys(statusData).length > 0) {
                    const statusLabels = Object.keys(statusData).map(key => {
                        const translations = {
                            'completed': '{{ __('operations.status.completed') }}',
                            'pending': '{{ __('operations.status.pending') }}',
                            'cancelled': '{{ __('operations.status.cancelled') }}',
                            'failed': '{{ __('operations.status.failed') }}'
                        };
                        return translations[key] || key;
                    });
                    const statusValues = Object.values(statusData);

                    new Chart(statusChartEl.getContext('2d'), {
                        type: 'pie',
                        data: {
                            labels: statusLabels,
                            datasets: [{
                                data: statusValues,
                                backgroundColor: [
                                    'rgba(16, 185, 129, 0.8)',
                                    'rgba(245, 158, 11, 0.8)',
                                    'rgba(107, 114, 128, 0.8)',
                                    'rgba(239, 68, 68, 0.8)'
                                ],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        font: {
                                            family: 'Cairo, sans-serif',
                                            size: 12
                                        },
                                        padding: 15
                                    }
                                }
                            }
                        }
                    });
                } else {
                    statusContainer.innerHTML =
                        '<div class="alert alert-info mb-0 d-flex align-items-center justify-content-center" style="height: 200px;"><i class="mdi mdi-information-outline me-2"></i>لا توجد بيانات لعرضها</div>';
                }
            }
        });
    </script>
@endpush
