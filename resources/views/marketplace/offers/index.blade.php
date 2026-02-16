@extends('layout.master-mini')

@section('content')
    @push('style')
        <style>
            .market-card {
                border-radius: 20px;
                border: 1px solid rgba(26, 95, 63, 0.08);
                background: #ffffff;
                box-shadow: 0 10px 40px rgba(26, 95, 63, 0.08), 0 2px 8px rgba(0, 0, 0, 0.04);
                overflow: hidden;
                font-size: 0.95rem;
                transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
            }

            .market-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 5px;
                background: linear-gradient(90deg, var(--primary), var(--primary-light), var(--secondary));
                opacity: 0;
                transition: opacity 0.4s ease;
            }

            .market-card img {
                border-top-left-radius: 20px;
                border-top-right-radius: 20px;
                object-fit: cover;
                height: 240px;
                width: 100%;
                transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .market-card:hover {
                transform: translateY(-15px);
                box-shadow: 0 25px 70px rgba(26, 95, 63, 0.18), 0 10px 25px rgba(0, 0, 0, 0.1);
                border-color: rgba(26, 95, 63, 0.25);
            }

            .market-card:hover::before {
                opacity: 1;
            }

            .market-card:hover img {
                transform: scale(1.12);
            }

            .market-card .card-img-top,
            .market-card .carousel-inner img {
                border-top-left-radius: 20px;
                border-top-right-radius: 20px;
                transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
                object-fit: cover;
                height: 240px;
            }

            .market-card:hover .card-img-top,
            .market-card:hover .carousel-inner img {
                transform: scale(1.08);
            }

            .market-card .carousel-inner {
                overflow: hidden;
            }

            .market-card .card-body {
                padding: 1.5rem 1.3rem;
                position: relative;
            }

            .market-card .card-title {
                font-weight: 800;
                color: var(--primary);
                font-size: 1.15rem;
                margin-bottom: 0.75rem;
                transition: color 0.3s ease;
                line-height: 1.4;
                letter-spacing: 0.3px;
            }

            .market-card:hover .card-title {
                color: var(--primary-light);
            }

            .market-card .card-text {
                color: #64748B;
                line-height: 1.7;
                font-size: 0.92rem;
            }

            .market-card .btn {
                border-radius: 999px;
                font-weight: 700;
                padding: 0.7rem 1.6rem;
                transition: all 0.3s ease;
                box-shadow: 0 4px 14px rgba(26, 95, 63, 0.2);
                letter-spacing: 0.5px;
            }

            .market-card .btn:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 24px rgba(26, 95, 63, 0.4);
            }

            .market-card .carousel-control-prev-icon,
            .market-card .carousel-control-next-icon {
                filter: drop-shadow(0 3px 6px rgba(0, 0, 0, .3));
                transition: all 0.3s ease;
            }

            .market-card .carousel-control-prev:hover .carousel-control-prev-icon,
            .market-card .carousel-control-next:hover .carousel-control-next-icon {
                transform: scale(1.2);
            }

            .market-card .carousel-control-prev,
            .market-card .carousel-control-next {
                width: 40px;
                height: 40px;
                background: rgba(26, 95, 63, 0.85);
                border-radius: 50%;
                opacity: 0;
                transition: all 0.3s ease;
            }

            .market-card:hover .carousel-control-prev,
            .market-card:hover .carousel-control-next {
                opacity: 0.95;
            }

            .market-card .carousel-control-prev {
                right: auto;
                left: 10px;
            }

            .market-card .carousel-control-next {
                left: auto;
                right: 10px;
            }

            .market-card .carousel-control-prev:hover,
            .market-card .carousel-control-next:hover {
                opacity: 1;
                transform: scale(1.1);
                background: var(--primary);
            }

            .market-card .carousel-control-prev-icon,
            .market-card .carousel-control-next-icon {
                width: 20px;
                height: 20px;
                filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
            }

            .market-card .visually-hidden {
                display: none;
            }

            .market-card .placeholder-logo {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 240px;
                background: linear-gradient(135deg, #f7f9fc 0%, #e8f0f7 100%);
            }

            .market-card .placeholder-logo img {
                height: 64px;
                opacity: .75;
                transition: all 0.3s ease;
            }

            .market-card:hover .placeholder-logo img {
                opacity: 1;
                transform: scale(1.1);
            }

            .market-card .list-unstyled li {
                padding: 0.5rem 0.75rem;
                border-bottom: 1px solid rgba(226, 232, 240, 0.5);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                border-radius: 8px;
                margin-bottom: 0.25rem;
            }

            .market-card .list-unstyled li:last-child {
                border-bottom: none;
                margin-bottom: 0;
            }

            .market-card .list-unstyled li:hover {
                background: rgba(26, 95, 63, 0.04);
                padding-right: 1rem;
                border-bottom-color: rgba(26, 95, 63, 0.15);
            }

            .market-card .list-unstyled li i {
                font-size: 1.1rem;
                margin-left: 0.25rem;
            }

            .market-card .list-unstyled li strong {
                color: var(--primary);
                font-weight: 700;
            }

            .section-card {
                border-radius: 20px;
                border: 1px solid rgba(26, 95, 63, 0.1);
                box-shadow: 0 10px 30px rgba(26, 95, 63, 0.1);
                margin-bottom: 2.5rem;
                background: linear-gradient(135deg, rgba(26, 95, 63, 0.04) 0%, #ffffff 100%);
                border-left: 6px solid var(--primary);
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                overflow: hidden;
            }

            .section-card::before {
                content: '';
                position: absolute;
                top: 0;
                right: 0;
                width: 200px;
                height: 200px;
                background: radial-gradient(circle, rgba(26, 95, 63, 0.08) 0%, transparent 70%);
                border-radius: 50%;
                transform: translate(50%, -50%);
            }

            .section-card:hover {
                box-shadow: 0 15px 40px rgba(26, 95, 63, 0.15);
                transform: translateY(-3px);
                border-left-width: 8px;
            }

            .section-card .card-body {
                padding: 2rem 2.25rem;
                position: relative;
                z-index: 1;
            }

            .section-card h3 {
                font-size: 2rem;
                font-weight: 900;
                color: var(--primary);
                letter-spacing: 0.3px;
                margin-bottom: 0.5rem;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }

            .section-card .text-muted {
                font-size: 1.05rem;
                color: #64748B;
                line-height: 1.6;
                font-weight: 500;
            }

            /* Quick Stats Cards */
            .stats-card {
                border: none;
                border-radius: 16px;
                overflow: hidden;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
            }

            .stats-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: currentColor;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .stats-card:hover::before {
                opacity: 1;
            }

            .stats-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
            }

            .stats-card.card-primary {
                background: linear-gradient(135deg, rgba(26, 95, 63, 0.05) 0%, #ffffff 100%);
                border-left: 5px solid var(--primary);
            }

            .stats-card.card-primary::before {
                color: var(--primary);
            }

            .stats-card.card-success {
                background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, #ffffff 100%);
                border-left: 5px solid #10b981;
            }

            .stats-card.card-success::before {
                color: #10b981;
            }

            .stats-card.card-warning {
                background: linear-gradient(135deg, rgba(212, 175, 55, 0.05) 0%, #ffffff 100%);
                border-left: 5px solid var(--secondary);
            }

            .stats-card.card-warning::before {
                color: var(--secondary);
            }

            .stats-card .card-body {
                padding: 1.75rem 1.5rem;
            }

            .icon-container {
                width: 56px;
                height: 56px;
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.75rem;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                flex-shrink: 0;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .stats-card:hover .icon-container {
                transform: scale(1.1) rotate(5deg);
            }

            .icon-container.bg-primary {
                background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            }

            .icon-container.bg-success {
                background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            }

            .icon-container.bg-warning {
                background: linear-gradient(135deg, var(--secondary) 0%, #f59e0b 100%);
            }

            .stats-text {
                margin-right: 1.25rem;
                flex: 1;
            }

            .stats-text h6 {
                font-size: 0.85rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 0.5rem;
            }

            .stats-text h3 {
                font-size: 2rem;
                font-weight: 800;
                line-height: 1;
                letter-spacing: -0.5px;
            }

            /* Filter Sidebar */
            .filters-sidebar {
                background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
                border-radius: 20px;
                border: 2px solid rgba(26, 95, 63, 0.08);
                box-shadow: 0 6px 20px rgba(26, 95, 63, 0.08);
                padding: 1.5rem 1.5rem 2.5rem 1.5rem;
                position: sticky;
                top: 100px;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .filters-sidebar .sidebar-header {
                margin-bottom: 1.5rem;
                padding-bottom: 1rem;
                border-bottom: 2px solid rgba(26, 95, 63, 0.1);
            }

            .filters-sidebar .sidebar-header h5 {
                color: var(--primary);
                font-weight: 800;
                font-size: 1.25rem;
                margin: 0;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .filters-sidebar .sidebar-header h5 i {
                font-size: 1.4rem;
            }

            .filter-sort-card {
                background: transparent;
                border: none;
                box-shadow: none;
                padding: 0;
                margin-bottom: 0;
            }

            .filters-sidebar .form-label {
                font-weight: 700;
                color: var(--primary);
                font-size: 0.85rem;
                margin-bottom: 0.5rem;
                display: flex;
                align-items: center;
                gap: 0.4rem;
                letter-spacing: 0.3px;
            }

            .filters-sidebar .form-label i {
                font-size: 1rem;
                color: var(--primary-light);
            }

            .filters-sidebar .form-control,
            .filters-sidebar .form-select {
                border-radius: 10px;
                border: 2px solid rgba(26, 95, 63, 0.12);
                padding: 0.65rem 0.9rem;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                font-size: 0.9rem;
                background: white;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
                font-weight: 500;
                width: 100%;
            }

            .filters-sidebar .filter-group {
                margin-bottom: 1.25rem;
            }

            .filters-sidebar .form-control:hover,
            .filters-sidebar .form-select:hover {
                border-color: rgba(26, 95, 63, 0.25);
                box-shadow: 0 3px 10px rgba(26, 95, 63, 0.08);
            }

            .filters-sidebar .form-control:focus,
            .filters-sidebar .form-select:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 0.25rem rgba(26, 95, 63, 0.15), 0 4px 12px rgba(26, 95, 63, 0.1);
                background: white;
            }

            .filters-sidebar .filter-actions {
                margin-top: 1.5rem;
                padding-top: 1.5rem;
                border-top: 2px solid rgba(26, 95, 63, 0.1);
            }

            .filters-sidebar .btn-primary {
                width: 100%;
                padding: 0.75rem;
                border-radius: 10px;
                font-weight: 700;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
                border: none;
                box-shadow: 0 4px 12px rgba(26, 95, 63, 0.3);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                letter-spacing: 0.3px;
                margin-bottom: 0.75rem;
            }

            .filters-sidebar .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(26, 95, 63, 0.4);
                background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
            }

            .filters-sidebar .btn-primary:active {
                transform: translateY(0);
                box-shadow: 0 2px 8px rgba(26, 95, 63, 0.3);
            }

            .filters-sidebar .btn-outline-secondary {
                width: 100%;
                padding: 0.65rem;
                border-radius: 10px;
                border: 2px solid var(--primary);
                font-weight: 700;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                background: white;
                color: var(--primary);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .filters-sidebar .btn-outline-secondary:hover {
                background: var(--primary);
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(26, 95, 63, 0.3);
                color: white;
            }

            .filters-sidebar .btn i {
                font-size: 1.1rem;
            }

            @media (max-width: 991px) {
                .filters-sidebar {
                    position: relative;
                    top: 0;
                    max-height: none;
                    margin-bottom: 2rem;
                }
            }

            /* Active Filters Badges */
            .badge {
                padding: 0.6rem 1rem;
                border-radius: 10px;
                font-weight: 700;
                font-size: 0.875rem;
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                letter-spacing: 0.3px;
            }

            .badge:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            }

            .badge a {
                font-size: 1.3rem;
                line-height: 1;
                opacity: 0.85;
                transition: all 0.2s ease;
                font-weight: 700;
                margin-right: 0.2rem;
            }

            .badge a:hover {
                opacity: 1;
                transform: scale(1.2);
            }

            /* Results Count */
            .results-count {
                background: linear-gradient(135deg, rgba(26, 95, 63, 0.05) 0%, rgba(26, 95, 63, 0.02) 100%);
                border-radius: 12px;
                padding: 0.85rem 1.25rem;
                border: 2px solid rgba(26, 95, 63, 0.1);
                font-weight: 600;
                color: var(--primary);
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                box-shadow: 0 2px 8px rgba(26, 95, 63, 0.08);
                transition: all 0.3s ease;
            }

            .results-count:hover {
                box-shadow: 0 4px 12px rgba(26, 95, 63, 0.12);
                transform: translateY(-2px);
            }

            .results-count i {
                font-size: 1.1rem;
                color: var(--primary-light);
            }

            /* Offer Badge (قريب من النفاد) */
            .offer-badge {
                position: absolute;
                top: 16px;
                right: 16px;
                background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
                color: white;
                padding: 0.5rem 1rem;
                border-radius: 999px;
                font-size: 0.8rem;
                font-weight: 800;
                box-shadow: 0 6px 20px rgba(239, 68, 68, 0.5);
                z-index: 3;
                animation: pulse 2s infinite;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                border: 2px solid white;
            }

            @keyframes pulse {

                0%,
                100% {
                    transform: scale(1);
                    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.5);
                }

                50% {
                    transform: scale(1.05);
                    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.7);
                }
            }

            /* Fade-in Animation for Cards */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .fade-in-delay-0 {
                animation: fadeInUp 0.6s ease-out;
            }

            .fade-in-delay-1 {
                animation: fadeInUp 0.6s ease-out 0.1s both;
            }

            .fade-in-delay-2 {
                animation: fadeInUp 0.6s ease-out 0.2s both;
            }

            .fade-in-delay-3 {
                animation: fadeInUp 0.6s ease-out 0.3s both;
            }

            top: 12px;
            right: 12px;
            background: linear-gradient(135deg, var(--secondary), var(--secondary-light));
            color: var(--primary-dark);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4);
            z-index: 2;
            animation: pulse 2s infinite;
            }
        </style>
    @endpush
    <div class="content-wrapper container">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <!-- Quick Stats -->
        <div class="row g-4 mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="card stats-card card-primary">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon-container bg-primary">
                            <i class="mdi mdi-office-building text-white"></i>
                        </div>
                        <div class="stats-text">
                            <h6 class="text-muted">{{ __('إجمالي العروض') }}</h6>
                            <h3 class="text-primary mb-0">{{ $stats['total_offers'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card stats-card card-success">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon-container bg-success">
                            <i class="mdi mdi-map-marker text-white"></i>
                        </div>
                        <div class="stats-text">
                            <h6 class="text-muted">{{ __('عدد المدن') }}</h6>
                            <h3 class="mb-0" style="color: #10b981;">{{ $stats['total_cities'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card stats-card card-warning">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon-container bg-warning">
                            <i class="mdi mdi-cash text-white"></i>
                        </div>
                        <div class="stats-text">
                            <h6 class="text-muted">{{ __('متوسط سعر السهم') }}</h6>
                            <h3 class="mb-0" style="color: var(--secondary);">
                                {{ $stats['avg_price'] ? number_format($stats['avg_price'], 2) : '---' }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content with Sidebar -->
        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="filters-sidebar">
                    <div class="sidebar-header">
                        <h5>
                            <i class="mdi mdi-filter-variant"></i>
                            {{ __('تصفية العروض') }}
                        </h5>
                    </div>

                    <form method="GET" action="{{ route('marketplace.offers.index') }}" id="filterForm">
                        <!-- City Filter -->
                        <div class="filter-group">
                            <label for="cityFilter" class="form-label">
                                <i class="mdi mdi-map-marker"></i>
                                {{ __('المدينة') }}
                            </label>
                            <select class="form-select" id="cityFilter" name="city">
                                <option value="">{{ __('جميع المدن') }}</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>
                                        {{ $city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Availability Filter -->
                        <div class="filter-group">
                            <label for="availability" class="form-label">
                                <i class="mdi mdi-chart-pie"></i>
                                {{ __('التوفر') }}
                            </label>
                            <select class="form-select" id="availability" name="availability">
                                <option value="">{{ __('الكل') }}</option>
                                <option value="high" {{ request('availability') === 'high' ? 'selected' : '' }}>
                                    {{ __('متوفر بكثرة') }}
                                </option>
                                <option value="low" {{ request('availability') === 'low' ? 'selected' : '' }}>
                                    {{ __('قريب من النفاد') }}
                                </option>
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div class="filter-group">
                            <label for="minPrice" class="form-label">
                                <i class="mdi mdi-currency-usd"></i>
                                {{ __('السعر من') }}
                            </label>
                            <input type="number" class="form-control" id="minPrice" name="min_price"
                                value="{{ request('min_price') }}" placeholder="0" min="0" step="0.01">
                        </div>

                        <div class="filter-group">
                            <label for="maxPrice" class="form-label">
                                <i class="mdi mdi-currency-usd"></i>
                                {{ __('السعر إلى') }}
                            </label>
                            <input type="number" class="form-control" id="maxPrice" name="max_price"
                                value="{{ request('max_price') }}" placeholder="∞" min="0" step="0.01">
                        </div>

                        <!-- Sort By -->
                        <div class="filter-group">
                            <label for="sortBy" class="form-label">
                                <i class="mdi mdi-sort"></i>
                                {{ __('الترتيب حسب') }}
                            </label>
                            <select class="form-select" id="sortBy" name="sort">
                                <option value="newest"
                                    {{ request('sort') === 'newest' || !request('sort') ? 'selected' : '' }}>
                                    {{ __('الأحدث أولاً') }}
                                </option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                                    {{ __('الأقدم أولاً') }}
                                </option>
                                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                                    {{ __('السعر: من الأقل للأعلى') }}
                                </option>
                                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
                                    {{ __('السعر: من الأعلى للأقل') }}
                                </option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="filter-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-filter"></i>
                                {{ __('تطبيق الفلاتر') }}
                            </button>
                            <a href="{{ route('marketplace.offers.index') }}" class="btn btn-outline-secondary">
                                <i class="mdi mdi-refresh"></i>
                                {{ __('إعادة تعيين') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Offers Content -->
            <div class="col-lg-9">
                <!-- Active Filters Display -->
                @if (request()->hasAny(['city', 'min_price', 'max_price', 'sort', 'availability']))
                    <div class="mb-3 d-flex align-items-center gap-2 flex-wrap">
                        <span class="text-muted small">
                            <i class="mdi mdi-filter-outline"></i>
                            {{ __('الفلاتر النشطة:') }}
                        </span>
                        @if (request('city'))
                            <span class="badge bg-primary">
                                <i class="mdi mdi-map-marker"></i>
                                {{ request('city') }}
                                <a href="{{ route('marketplace.offers.index', request()->except('city')) }}"
                                    class="text-white ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif
                        @if (request('availability'))
                            <span class="badge bg-warning text-dark">
                                <i class="mdi mdi-chart-pie"></i>
                                {{ request('availability') === 'high' ? __('متوفر بكثرة') : __('قريب من النفاد') }}
                                <a href="{{ route('marketplace.offers.index', request()->except('availability')) }}"
                                    class="text-dark ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif
                        @if (request('min_price'))
                            <span class="badge bg-success">
                                {{ __('من') }} {{ number_format(request('min_price'), 2) }}
                                <a href="{{ route('marketplace.offers.index', request()->except('min_price')) }}"
                                    class="text-white ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif
                        @if (request('max_price'))
                            <span class="badge bg-success">
                                {{ __('إلى') }} {{ number_format(request('max_price'), 2) }}
                                <a href="{{ route('marketplace.offers.index', request()->except('max_price')) }}"
                                    class="text-white ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif
                        @if (request('sort') && request('sort') !== 'newest')
                            <span class="badge bg-info">
                                <i class="mdi mdi-sort"></i>
                                @switch(request('sort'))
                                    @case('oldest')
                                        {{ __('الأقدم أولاً') }}
                                    @break

                                    @case('price_asc')
                                        {{ __('السعر: الأقل أولاً') }}
                                    @break

                                    @case('price_desc')
                                        {{ __('السعر: الأعلى أولاً') }}
                                    @break
                                @endswitch
                                <a href="{{ route('marketplace.offers.index', request()->except('sort')) }}"
                                    class="text-white ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif
                    </div>
                @endif

                <!-- Results Count -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="results-count">
                        <i class="mdi mdi-view-list"></i>
                        <span>
                            {{ __('عرض') }} <strong>{{ $offers->firstItem() ?? 0 }}</strong> {{ __('إلى') }}
                            <strong>{{ $offers->lastItem() ?? 0 }}</strong> {{ __('من') }}
                            <strong>{{ $offers->total() }}</strong> {{ __('نتيجة') }}
                        </span>
                    </div>
                </div>

                <!-- Offers Grid -->
                <div class="row">
                    @forelse($offers as $idx => $offer)
                        @php($media = is_array($offer->media) ? $offer->media : (is_string($offer->media) ? (json_decode($offer->media, true) ?: []) : []))
                        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-4 fade-in-delay-{{ min($idx % 4, 3) }}">
                            <div class="card h-100 market-card">
                                @if ($offer->available_shares > 0 && $offer->available_shares < 10)
                                    <span class="offer-badge">{{ __('قريب من النفاد!') }}</span>
                                @endif
                                @if (!empty($media))
                                    @php($carouselId = 'offerCarousel_' . $offer->id)
                                    <div id="{{ $carouselId }}" class="carousel slide" data-bs-ride="carousel"
                                        data-bs-interval="6000">
                                        <div class="carousel-inner img-zoom-container" style="max-height: 240px;">
                                            @foreach ($media as $imgIdx => $img)
                                                <div class="carousel-item {{ $imgIdx === 0 ? 'active' : '' }}">
                                                    <img class="d-block w-100 img-zoom"
                                                        src="{{ asset('storage/' . $img) }}" alt="img"
                                                        style="object-fit: cover; height: 240px;">
                                                </div>
                                            @endforeach
                                        </div>
                                        @if (count($media) > 1)
                                            <div class="carousel-indicators" style="bottom: 8px;">
                                                @foreach ($media as $imgIdx => $img)
                                                    <button type="button" data-carousel-to="{{ $imgIdx }}"
                                                        class="{{ $imgIdx === 0 ? 'active' : '' }}"
                                                        aria-label="Slide {{ $imgIdx + 1 }}"
                                                        style="width:10px;height:10px;border-radius:50%;margin:0 4px;border:0;background:#fff;box-shadow: 0 2px 6px rgba(0,0,0,0.3);"></button>
                                                @endforeach
                                            </div>
                                        @endif
                                        @if (count($media) > 1)
                                            <button class="carousel-control-prev" type="button"
                                                data-bs-target="#{{ $carouselId }}" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            </button>
                                            <button class="carousel-control-next" type="button"
                                                data-bs-target="#{{ $carouselId }}" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            </button>
                                        @endif
                                    </div>
                                @elseif ($offer->cover_image)
                                    <div class="img-zoom-container">
                                        <img class="card-img-top img-zoom" src="{{ asset($offer->cover_image) }}"
                                            alt="cover" style="object-fit: cover; max-height: 240px;">
                                    </div>
                                @else
                                    <div class="placeholder-logo">
                                        <img src="{{ asset('assets/images/logo-w.png') }}" alt="logo">
                                    </div>
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $offer->title }}</h5>
                                    <p class="card-text text-muted">
                                        {{ \Illuminate\Support\Str::limit($offer->description, 100) }}
                                    </p>
                                    <ul class="list-unstyled small text-muted mb-3">
                                        <li>
                                            <i class="mdi mdi-map-marker text-danger me-1"></i>
                                            <strong>{{ __('المدينة') }}:</strong> {{ $offer->city }}
                                        </li>
                                        <li>
                                            <i class="mdi mdi-cash text-success me-1"></i>
                                            <strong>{{ __('السعر/سهم') }}:</strong>
                                            {{ number_format($offer->price_per_share, 2) }} {{ $offer->currency }}
                                        </li>
                                        <li>
                                            <i class="mdi mdi-chart-pie text-primary me-1"></i>
                                            <strong>{{ __('متاح') }}:</strong> {{ $offer->available_shares }} /
                                            {{ $offer->total_shares }}
                                        </li>
                                    </ul>
                                    <a href="{{ route('marketplace.offers.show', ['offer' => $offer->id]) }}"
                                        class="btn btn-primary mt-auto">
                                        <i class="mdi mdi-eye me-1"></i>
                                        {{ __('عرض التفاصيل') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5"
                                style="background: linear-gradient(135deg, rgba(26, 95, 63, 0.03) 0%, #ffffff 100%); border-radius: 20px; border: 2px dashed rgba(26, 95, 63, 0.2); padding: 4rem 2rem;">
                                <div class="mb-4"
                                    style="width: 100px; height: 100px; margin: 0 auto; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(26, 95, 63, 0.25);">
                                    <i class="mdi mdi-magnify" style="font-size: 3.5rem; color: white;"></i>
                                </div>
                                <h4 class="mb-3" style="color: var(--primary); font-weight: 800;">
                                    {{ __('لا توجد عروض متاحة') }}
                                </h4>
                                @if (request()->hasAny(['city', 'min_price', 'max_price', 'availability']))
                                    <p class="mb-4" style="color: #64748B; font-size: 1.1rem;">
                                        {{ __('لم يتم العثور على عروض تطابق معايير البحث الخاصة بك') }}
                                    </p>
                                    <a href="{{ route('marketplace.offers.index') }}" class="btn btn-primary btn-lg"
                                        style="padding: 0.9rem 2.5rem; border-radius: 999px; font-weight: 700; box-shadow: 0 6px 20px rgba(26, 95, 63, 0.3);">
                                        <i class="mdi mdi-refresh me-2"></i>
                                        {{ __('عرض جميع العروض') }}
                                    </a>
                                @else
                                    <p class="mb-0" style="color: #64748B; font-size: 1.1rem;">
                                        {{ __('سيتم إضافة عروض جديدة قريباً') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $offers->links('pagination.marketplace') }}
                </div>
            </div>
            <!-- End Offers Content -->
        </div>
        <!-- End Main Content with Sidebar -->
    </div>
@endsection
