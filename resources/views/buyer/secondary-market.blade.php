@extends('layout.master-mini')

@push('style')
    <style>
        :root {
            --secondary-primary: #06b6d4;
            --secondary-dark: #0891b2;
            --secondary-light: #22d3ee;
        }

        .secondary-market-header {
            background: linear-gradient(135deg, var(--secondary-primary) 0%, var(--secondary-dark) 100%);
            color: white;
            padding: 3rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: 0 12px 40px rgba(6, 182, 212, 0.4);
        }

        .filters-sidebar {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 20px;
        }

        .filter-group {
            margin-bottom: 1.5rem;
        }

        .filter-group label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .filter-group i {
            margin-left: 8px;
            color: var(--secondary-primary);
        }

        .stock-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            transition: all 0.4s ease;
            border: 2px solid transparent;
            cursor: pointer;
        }

        .stock-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 32px rgba(6, 182, 212, 0.25);
            border-color: var(--secondary-primary);
        }

        .stock-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            position: relative;
        }

        .stock-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(6, 182, 212, 0.95);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.4);
        }

        .price-range {
            background: linear-gradient(135deg, #cffafe 0%, #a5f3fc 100%);
            padding: 1.5rem;
            border-radius: 12px;
            margin: 1rem 0;
        }

        .price-label {
            font-size: 0.85rem;
            color: #164e63;
            font-weight: 600;
        }

        .price-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary-dark);
        }

        .stock-info {
            padding: 1.5rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .info-value {
            font-weight: 600;
            color: #111827;
        }

        .view-details-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--secondary-primary) 0%, var(--secondary-dark) 100%);
            border: none;
            color: white;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .view-details-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
        }

        .filter-btn {
            width: 100%;
            background: var(--secondary-primary);
            border: none;
            color: white;
            padding: 10px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .filter-btn:hover {
            background: var(--secondary-dark);
        }

        .reset-btn {
            width: 100%;
            background: #f3f4f6;
            border: none;
            color: #374151;
            padding: 10px;
            border-radius: 8px;
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 15px;
        }

        .empty-state i {
            font-size: 5rem;
            color: #a5f3fc;
            margin-bottom: 1.5rem;
        }

        .pagination {
            justify-content: center;
            margin-top: 2rem;
        }

        .pagination .page-link {
            color: var(--secondary-dark);
            border-color: #a5f3fc;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--secondary-primary);
            border-color: var(--secondary-primary);
        }
    </style>
@endpush

@section('content')
    <div class="content-wrapper container-fluid">
        {{-- Header --}}
        <div class="secondary-market-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">
                        <i class="mdi mdi-chart-box-outline me-2"></i>
                        {{ __('السوق الثانوي') }}
                    </h1>
                    <p class="mb-0" style="font-size: 1.1rem; opacity: 0.95;">
                        {{ __('منصة تداول الأسهم بين المستثمرين - اكتشف الفرص الاستثمارية') }}
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('buyer.dashboard') }}" class="btn btn-light btn-lg">
                        <i class="mdi mdi-wallet me-2"></i>{{ __('محفظتي') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="mdi mdi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="mdi mdi-alert-circle me-2"></i>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Main Content --}}
        <div class="row">
            {{-- Filters Sidebar --}}
            <div class="col-lg-3 mb-4">
                <div class="filters-sidebar">
                    <h5 class="mb-4">
                        <i class="mdi mdi-filter-variant text-info"></i>
                        {{ __('تصفية العروض') }}
                    </h5>

                    <form method="GET" action="{{ route('buyer.secondary-market.index') }}" id="filterForm">
                        {{-- City Filter --}}
                        <div class="filter-group">
                            <label>
                                <i class="mdi mdi-map-marker"></i>
                                {{ __('المدينة') }}
                            </label>
                            <select class="form-select" name="city">
                                <option value="">{{ __('جميع المدن') }}</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city }}"
                                        {{ ($filters['city'] ?? '') === $city ? 'selected' : '' }}>
                                        {{ $city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Price Range --}}
                        <div class="filter-group">
                            <label>
                                <i class="mdi mdi-cash"></i>
                                {{ __(' نطاق السعر (للسهم)') }}
                            </label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" class="form-control" name="min_price"
                                        placeholder="{{ __('من') }}" value="{{ $filters['min_price'] ?? '' }}"
                                        step="0.01" min="0">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" name="max_price"
                                        placeholder="{{ __('إلى') }}" value="{{ $filters['max_price'] ?? '' }}"
                                        step="0.01" min="0">
                                </div>
                            </div>
                        </div>

                        {{-- Shares Count --}}
                        <div class="filter-group">
                            <label>
                                <i class="mdi mdi-counter"></i>
                                {{ __('الحد الأدنى للأسهم') }}
                            </label>
                            <input type="number" class="form-control" name="min_shares"
                                placeholder="{{ __('عدد الأسهم') }}" value="{{ $filters['min_shares'] ?? '' }}"
                                min="1">
                        </div>

                        {{-- Buttons --}}
                        <div class="filter-group">
                            <button type="submit" class="filter-btn mb-2">
                                <i class="mdi mdi-magnify me-2"></i>{{ __('تطبيق الفلاتر') }}
                            </button>
                            <a href="{{ route('buyer.secondary-market.index') }}"
                                class="reset-btn d-block text-center text-decoration-none">
                                <i class="mdi mdi-refresh me-2"></i>{{ __('إعادة تعيين') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Stock Cards --}}
            <div class="col-lg-9">
                @if ($groupedOffers->count() > 0)
                    <div class="row">
                        @foreach ($groupedOffers as $grouped)
                            <div class="col-md-6 col-xl-4 mb-4">
                                <div class="stock-card"
                                    onclick="window.location.href='{{ route('buyer.secondary-market.show', $grouped['original_offer_id']) }}'">
                                    <div class="position-relative">
                                        @if ($grouped['cover_image'])
                                            <img src="{{ asset('storage/' . $grouped['cover_image']) }}"
                                                class="stock-image" alt="{{ $grouped['offer_title'] }}">
                                        @else
                                            <div
                                                class="stock-image bg-light d-flex align-items-center justify-content-center">
                                                <i class="mdi mdi-image-off text-muted" style="font-size: 3rem;"></i>
                                            </div>
                                        @endif
                                        <span class="stock-badge">
                                            {{ $grouped['offers_count'] }} {{ __('عروض') }}
                                        </span>
                                    </div>

                                    <div class="stock-info">
                                        <h5 class="mb-3">{{ $grouped['offer_title'] }}</h5>

                                        {{-- Price Range --}}
                                        <div class="price-range">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="price-label">{{ __('أقل سعر') }}</div>
                                                    <div class="price-value">{{ number_format($grouped['min_price'], 2) }}
                                                    </div>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <div class="price-label">{{ __('أعلى سعر') }}</div>
                                                    <div class="price-value">{{ number_format($grouped['max_price'], 2) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Info Rows --}}
                                        <div class="info-row">
                                            <span class="info-label">{{ __('المدينة') }}</span>
                                            <span class="info-value">
                                                <i class="mdi mdi-map-marker text-info"></i>
                                                {{ $grouped['offer_city'] }}
                                            </span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">{{ __('إجمالي الأسهم') }}</span>
                                            <span class="info-value">
                                                <i class="mdi mdi-chart-box text-info"></i>
                                                {{ $grouped['total_shares'] }} {{ __('سهم') }}
                                            </span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">{{ __('متوسط السعر') }}</span>
                                            <span class="info-value">
                                                {{ number_format($grouped['avg_price'], 2) }} {{ $grouped['currency'] }}
                                            </span>
                                        </div>

                                        {{-- Action Button --}}
                                        <button type="button" class="view-details-btn mt-3">
                                            <i class="mdi mdi-eye me-2"></i>{{ __('عرض التفاصيل والمخطط') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-4">
                        {{ $groupedOffers->appends($filters)->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <i class="mdi mdi-chart-box-outline"></i>
                        <h4>{{ __('لا توجد عروض متاحة حالياً') }}</h4>
                        <p class="text-muted">{{ __('تحقق مرة أخرى لاحقاً أو قم بتعديل الفلاتر') }}</p>
                        <a href="{{ route('buyer.dashboard') }}" class="btn btn-warning mt-3">
                            <i class="mdi mdi-arrow-right me-2"></i>{{ __('العودة إلى المحفظة') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script>
        // Nothing specific needed - onclick handlers are inline
    </script>
@endpush
