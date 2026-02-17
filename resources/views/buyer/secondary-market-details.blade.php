@extends('layout.master-mini')

@push('style')
    <style>
        :root {
            --secondary-primary: #06b6d4;
            --secondary-dark: #0891b2;
            --secondary-light: #22d3ee;
        }

        .stock-header {
            background: linear-gradient(135deg, var(--secondary-primary) 0%, var(--secondary-dark) 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 24px rgba(6, 182, 212, 0.3);
        }

        .price-trend-up {
            color: #10b981;
        }

        .price-trend-down {
            color: #ef4444;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--secondary-primary);
        }

        .stat-label {
            font-size: 0.9rem;
            color: #6b7280;
            margin-top: 0.5rem;
        }

        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .offers-table {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }

        .offer-row {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            transition: all 0.3s ease;
        }

        .offer-row:hover {
            background: #cffafe;
        }

        .buy-btn {
            background: linear-gradient(135deg, var(--secondary-primary) 0%, var(--secondary-dark) 100%);
            border: none;
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .buy-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
        }
    </style>
@endpush

@section('content')
    <div class="content-wrapper container">
        {{-- Header --}}
        <div class="stock-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h2 class="mb-2">{{ $offer->title }}</h2>
                    <p class="mb-0"><i class="mdi mdi-map-marker"></i> {{ $offer->city }}</p>
                </div>
                <a href="{{ route('buyer.secondary-market.index') }}" class="btn btn-light">
                    <i class="mdi mdi-arrow-right me-2"></i>{{ __('العودة للسوق') }}
                </a>
            </div>

            <div class="row mt-4">
                <div class="col-md-3">
                    <h5>{{ __('السعر الأصلي') }}</h5>
                    <h3>{{ number_format($stats['original_price'], 2) }} {{ $offers->first()->currency }}</h3>
                </div>
                <div class="col-md-3">
                    <h5>{{ __('النطاق السعري') }}</h5>
                    <h3>{{ number_format($stats['min_price'], 2) }} - {{ number_format($stats['max_price'], 2) }}</h3>
                </div>
                <div class="col-md-3">
                    <h5>{{ __('التغير') }}</h5>
                    <h3 class="{{ $stats['price_change'] >= 0 ? 'price-trend-up' : 'price-trend-down' }}">
                        {{ $stats['price_change'] >= 0 ? '+' : '' }}{{ number_format($stats['price_change_percent'], 2) }}%
                        <i class="mdi mdi-{{ $stats['price_change'] >= 0 ? 'trending-up' : 'trending-down' }}"></i>
                    </h3>
                </div>
                <div class="col-md-3">
                    <h5>{{ __('إجمالي الأسهم') }}</h5>
                    <h3>{{ $stats['total_shares_available'] }}</h3>
                </div>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-value">{{ $stats['total_offers'] }}</div>
                    <div class="stat-label">{{ __('عدد العروض') }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-value">{{ number_format($stats['avg_price'], 2) }}</div>
                    <div class="stat-label">{{ __('متوسط السعر') }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-value">{{ $stats['total_shares_available'] }}</div>
                    <div class="stat-label">{{ __('الأسهم المتاحة') }}</div>
                </div>
            </div>
        </div>

        {{-- Price History Chart --}}
        <div class="chart-container">
            <h4 class="mb-4">
                <i class="mdi mdi-chart-line text-info"></i>
                {{ __('تاريخ الأسعار') }}
            </h4>
            @if ($priceHistory->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="mdi mdi-information-outline"></i>
                    {{ __('لا يوجد بيانات تاريخية كافية لعرض المخطط') }}
                </div>
            @else
                <div style="position: relative; height: 300px;">
                    <canvas id="priceChart"></canvas>
                </div>
            @endif
        </div>

        {{-- Available Offers Table --}}
        <div class="offers-table">
            <h4 class="mb-4">
                <i class="mdi mdi-format-list-bulleted text-info"></i>
                {{ __('العروض المتاحة') }}
            </h4>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('البائع') }}</th>
                            <th>{{ __('عدد الأسهم') }}</th>
                            <th>{{ __('سعر السهم') }}</th>
                            <th>{{ __('القيمة الإجمالية') }}</th>
                            <th>{{ __('الوصف') }}</th>
                            <th>{{ __('إجراء') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($offers as $offer)
                            <tr class="offer-row">
                                <td>
                                    <i class="mdi mdi-account-circle text-info"></i>
                                    {{ $offer->seller->name ?? __('مستثمر') }}
                                </td>
                                <td>
                                    <span class="badge text-dark"
                                        style="background-color: #a5f3fc;">{{ $offer->shares_count }}</span>
                                </td>
                                <td>
                                    <strong>{{ number_format($offer->price_per_share, 2) }}</strong>
                                    {{ $offer->currency }}
                                </td>
                                <td>
                                    <strong>{{ number_format($offer->shares_count * $offer->price_per_share, 2) }}</strong>
                                    {{ $offer->currency }}
                                </td>
                                <td>
                                    {{ $offer->description ? Str::limit($offer->description, 50) : '-' }}
                                </td>
                                <td>
                                    @auth('web')
                                        <button type="button" class="buy-btn" data-toggle="modal"
                                            data-target="#buyModal{{ $offer->id }}" data-offer-id="{{ $offer->id }}"
                                            data-shares="{{ $offer->shares_count }}"
                                            data-price="{{ $offer->price_per_share }}" data-currency="{{ $offer->currency }}"
                                            data-seller="{{ $offer->seller->name ?? __('مستثمر') }}">
                                            <i class="mdi mdi-cart"></i> {{ __('شراء') }}
                                        </button>
                                    @else
                                        <a href="{{ route('marketplace.login') }}" class="btn btn-sm btn-outline-primary">
                                            <i class="mdi mdi-login"></i> سجل دخول للشراء
                                        </a>
                                    @endauth
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Buy Confirmation Modals --}}
    @foreach ($offers as $offer)
        <div class="modal fade" id="buyModal{{ $offer->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('buyer.secondary-market.buy') }}" method="POST">
                        @csrf
                        <input type="hidden" name="sale_offer_id" value="{{ $offer->id }}">

                        <div class="modal-header bg-gradient"
                            style="background: linear-gradient(135deg, var(--secondary-primary) 0%, var(--secondary-dark) 100%); color: white;">
                            <h5 class="modal-title">
                                <i class="mdi mdi-cart-check me-2"></i>{{ __('تأكيد الشراء') }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="mb-3 p-3 bg-light rounded">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted"><i class="mdi mdi-account-circle"></i>
                                        {{ __('البائع') }}:</span>
                                    <strong>{{ $offer->seller->name ?? __('مستثمر') }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted"><i class="mdi mdi-chart-box"></i>
                                        {{ __('الأسهم المتاحة') }}:</span>
                                    <strong class="text-primary">{{ $offer->shares_count }} {{ __('سهم') }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted"><i class="mdi mdi-cash"></i> {{ __('سعر السهم') }}:</span>
                                    <strong class="text-success">{{ number_format($offer->price_per_share, 2) }}
                                        {{ $offer->currency }}</strong>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold" for="shares_to_buy_{{ $offer->id }}">
                                    <i class="mdi mdi-cart me-1" style="color: #06b6d4;"></i>
                                    {{ __('عدد الأسهم التي تريد شرائها') }}
                                </label>
                                <input type="number" class="form-control shares-input"
                                    id="shares_to_buy_{{ $offer->id }}" name="shares_count" min="1"
                                    max="{{ $offer->shares_count }}" value="{{ $offer->shares_count }}"
                                    data-offer-id="{{ $offer->id }}" data-price="{{ $offer->price_per_share }}"
                                    required>
                                <small class="text-muted">
                                    {{ __('يمكنك شراء من 1 إلى') }} {{ $offer->shares_count }} {{ __('سهم') }}
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold" for="payment_method_{{ $offer->id }}">
                                    <i class="mdi mdi-credit-card me-1" style="color: #06b6d4;"></i>
                                    {{ __('طريقة الدفع') }}
                                </label>
                                <select class="form-select payment-method-select" id="payment_method_{{ $offer->id }}"
                                    name="payment_method" required data-offer-id="{{ $offer->id }}">
                                    <option value="wallet" selected>
                                        <i class="mdi mdi-wallet"></i> {{ __('الدفع من المحفظة') }}
                                        ({{ __('الرصيد المتاح') }}: {{ number_format($walletBalance ?? 0, 2) }}
                                        {{ __('ريال') }})
                                    </option>
                                    <option value="credit_card" disabled>
                                        <i class="mdi mdi-credit-card"></i> {{ __('بطاقة ائتمانية') }}
                                        ({{ __('غير متاح حالياً') }})
                                    </option>
                                </select>
                                <div id="wallet_status_{{ $offer->id }}" class="mt-2" style="display: none;">
                                    <div class="alert alert-sm mb-0" role="alert">
                                        <i class="mdi mdi-information"></i>
                                        <span class="wallet-status-message"></span>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    {{ __('اختر طريقة الدفع المناسبة لإتمام الشراء') }}
                                </small>
                            </div>

                            <div class="p-3 rounded"
                                style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border: 2px solid #06b6d4;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold fs-5"><i class="mdi mdi-sigma"></i>
                                        {{ __('المبلغ الإجمالي') }}:</span>
                                    <strong id="total_amount_{{ $offer->id }}" class="text-danger fs-4">
                                        {{ number_format($offer->shares_count * $offer->price_per_share, 2) }}
                                        {{ $offer->currency }}
                                    </strong>
                                </div>
                            </div>

                            @if ($offer->description)
                                <div class="alert alert-info mt-3">
                                    <i class="mdi mdi-information"></i> {{ $offer->description }}
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="mdi mdi-close"></i> {{ __('إلغاء') }}
                            </button>
                            <button type="submit" class="btn btn-primary btn-confirm-purchase"
                                style="background: var(--secondary-primary); border-color: var(--secondary-primary);"
                                data-offer-id="{{ $offer->id }}" data-price="{{ $offer->price_per_share }}"
                                data-wallet-balance="{{ $walletBalance ?? 0 }}">
                                <i class="mdi mdi-check-circle"></i> {{ __('تأكيد الشراء') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('custom-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Pass data from PHP to JavaScript
        const priceHistory = @json($priceHistory);

        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOMContentLoaded fired');
            console.log('Chart.js available:', typeof Chart !== 'undefined');
            console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
            console.log('Price History:', priceHistory);

            if (priceHistory && priceHistory.length > 0) {
                const ctx = document.getElementById('priceChart');

                if (!ctx) {
                    console.error('Canvas element not found!');
                    return;
                }

                const chartContext = ctx.getContext('2d');

                const labels = priceHistory.map(item => {
                    const date = new Date(item.created_at);
                    return date.toLocaleDateString('ar-SA', {
                        month: 'short',
                        day: 'numeric'
                    });
                });

                const prices = priceHistory.map(item => parseFloat(item.price_per_share));

                console.log('Chart Labels:', labels);
                console.log('Chart Prices:', prices);

                // Calculate moving average
                const movingAverage = [];
                const period = Math.min(5, prices.length);
                for (let i = 0; i < prices.length; i++) {
                    if (i < period - 1) {
                        movingAverage.push(null);
                    } else {
                        const sum = prices.slice(i - period + 1, i + 1).reduce((a, b) => a + b, 0);
                        movingAverage.push(sum / period);
                    }
                }

                try {
                    const chart = new Chart(chartContext, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                    label: '{{ __('السعر') }}',
                                    data: prices,
                                    borderColor: '#06b6d4',
                                    backgroundColor: 'rgba(6, 182, 212, 0.1)',
                                    borderWidth: 3,
                                    tension: 0.4,
                                    fill: true,
                                    pointRadius: 5,
                                    pointHoverRadius: 7,
                                    pointBackgroundColor: '#06b6d4',
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2
                                },
                                {
                                    label: '{{ __('المتوسط المتحرك') }}',
                                    data: movingAverage,
                                    borderColor: '#0891b2',
                                    borderWidth: 2,
                                    borderDash: [5, 5],
                                    tension: 0.4,
                                    fill: false,
                                    pointRadius: 0
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    padding: 12,
                                    titleFont: {
                                        size: 14,
                                        weight: 'bold'
                                    },
                                    bodyFont: {
                                        size: 13
                                    },
                                    callbacks: {
                                        label: function(context) {
                                            return context.dataset.label + ': ' +
                                                context.parsed.y.toFixed(2) +
                                                ' {{ $offers->first()->currency }}';
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: false,
                                    ticks: {
                                        callback: function(value) {
                                            return value.toFixed(2);
                                        }
                                    },
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });

                    console.log('Chart created successfully!', chart);
                } catch (error) {
                    console.error('Error creating chart:', error);
                }
            } else {
                console.log('No price history data available');
            }
        });

        // Calculate total amount dynamically when shares count changes
        $(document).ready(function() {
            $('.shares-input').on('input', function() {
                const offerId = $(this).data('offer-id');
                const pricePerShare = parseFloat($(this).data('price'));
                const sharesCount = parseInt($(this).val()) || 0;
                const totalAmount = sharesCount * pricePerShare;
                const currency = '{{ $offers->first()->currency ?? '' }}';

                const totalAmountEl = $('#total_amount_' + offerId);
                totalAmountEl.text(
                    totalAmount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' ' + currency
                );

                // Check if wallet has sufficient balance
                const paymentMethod = $('#payment_method_' + offerId).val();
                const confirmBtn = $('button[data-offer-id="' + offerId + '"]');
                const walletBalance = parseFloat(confirmBtn.data('wallet-balance')) || 0;

                if (paymentMethod === 'wallet') {
                    if (totalAmount > walletBalance) {
                        totalAmountEl.removeClass('text-danger text-success').addClass('text-danger');
                        totalAmountEl.parent().removeClass('border-success').css('border-color', '#dc2626');

                        // Add warning icon
                        if (!totalAmountEl.find('.mdi-alert').length) {
                            totalAmountEl.prepend('<i class="mdi mdi-alert-circle me-1"></i>');
                        }
                    } else {
                        totalAmountEl.removeClass('text-danger text-success').addClass('text-success');
                        totalAmountEl.parent().css('border-color', '#06b6d4');
                        totalAmountEl.find('.mdi-alert-circle').remove();
                    }
                }
            });

            // Update check when payment method changes
            $('select[name="payment_method"]').on('change', function() {
                const form = $(this).closest('form');
                form.find('.shares-input').trigger('input');
            });

            // Check wallet balance before purchase
            $('form').on('submit', function(e) {
                const form = $(this);
                const confirmBtn = form.find('.btn-confirm-purchase');

                if (confirmBtn.length === 0) {
                    return true; // Not a purchase form
                }

                const paymentMethod = form.find('select[name="payment_method"]').val();

                if (paymentMethod === 'wallet') {
                    const offerId = confirmBtn.data('offer-id');
                    const walletBalance = parseFloat(confirmBtn.data('wallet-balance')) || 0;
                    const sharesCount = parseInt(form.find('input[name="shares_count"]').val()) || 0;
                    const pricePerShare = parseFloat(confirmBtn.data('price')) || 0;
                    const totalAmount = sharesCount * pricePerShare;

                    if (totalAmount > walletBalance) {
                        e.preventDefault();

                        const currency = '{{ $offers->first()->currency ?? 'ريال' }}';
                        const shortage = totalAmount - walletBalance;

                        alert('⚠️ رصيد المحفظة غير كافٍ\n\n' +
                            'المبلغ المطلوب: ' + totalAmount.toFixed(2) + ' ' + currency + '\n' +
                            'الرصيد المتاح: ' + walletBalance.toFixed(2) + ' ' + currency + '\n' +
                            'العجز: ' + shortage.toFixed(2) + ' ' + currency + '\n\n' +
                            'يرجى إيداع المبلغ المطلوب في محفظتك أولاً أو تقليل عدد الأسهم.');

                        return false;
                    }
                }
            });
        });

        // Bootstrap 4 modals work automatically with data-toggle="modal"
        // No additional JavaScript needed for modal triggers
    </script>
@endpush
