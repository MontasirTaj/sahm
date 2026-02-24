@extends('layout.master-mini')

@section('content')
    @push('style')
        <style>
            /* Section header */
            .section-card {
                border-radius: 20px;
                border: 1px solid rgba(26, 95, 63, 0.1);
                box-shadow: 0 8px 24px rgba(26, 95, 63, 0.08);
                margin-bottom: 2rem;
                background: linear-gradient(135deg, rgba(26, 95, 63, 0.03) 0%, #ffffff 100%);
                border-left: 5px solid var(--primary);
            }

            /* Details card container */
            .details-card {
                border-radius: 20px;
                border: 1px solid rgba(26, 95, 63, 0.08);
                box-shadow: 0 10px 40px rgba(26, 95, 63, 0.08);
                overflow: hidden;
                background: linear-gradient(135deg, rgba(26, 95, 63, 0.02) 0%, #fff 100%);
                transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
                padding: 2rem;
                border-left: 5px solid var(--secondary);
                position: relative;
            }

            .details-card::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, rgba(212, 175, 55, 0.03) 0%, transparent 100%);
                opacity: 0;
                transition: opacity 0.5s ease;
                pointer-events: none;
            }

            .details-card:hover::after {
                opacity: 1;
            }

            .details-card:hover {
                box-shadow: 0 25px 70px rgba(26, 95, 63, 0.15);
                transform: translateY(-6px);
                border-left-color: var(--primary);
            }

            .details-card h3 {
                font-weight: 800;
                color: var(--primary);
                margin-bottom: 1rem;
                font-size: 1.75rem;
                letter-spacing: 0.5px;
            }

            .details-card .text-muted {
                color: #4b5563 !important;
                line-height: 1.8;
                font-size: 1rem;
            }

            /* Enhanced buy card */
            .buy-card {
                border-radius: 20px;
                border: 1px solid rgba(26, 95, 63, 0.1);
                box-shadow: 0 10px 40px rgba(26, 95, 63, 0.1);
                transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
                background: linear-gradient(135deg, #ffffff 0%, rgba(247, 250, 252, 0.8) 100%);
                position: relative;
                overflow: hidden;
            }

            .buy-card::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 6px;
                background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 50%, var(--secondary) 100%);
                transform: scaleX(0);
                transform-origin: left;
                transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .buy-card:hover::before {
                transform: scaleX(1);
            }

            .buy-card:hover {
                box-shadow: 0 25px 70px rgba(26, 95, 63, 0.18);
                transform: translateY(-10px);
                border-color: rgba(26, 95, 63, 0.25);
            }

            .buy-card .card-body {
                padding: 2rem;
            }

            .buy-card .card-title {
                font-weight: 800;
                color: var(--primary);
                font-size: 1.65rem;
                margin-bottom: 1.5rem;
                letter-spacing: 0.5px;
            }

            .buy-card .price-info {
                background: linear-gradient(135deg, rgba(26, 95, 63, 0.05) 0%, rgba(45, 122, 86, 0.05) 100%);
                padding: 1rem;
                border-radius: 12px;
                margin-bottom: 1.5rem;
                border-left: 4px solid var(--primary);
            }

            .buy-card .price-info p {
                margin-bottom: 0.5rem;
                font-size: 1rem;
            }

            .buy-card .price-info strong {
                color: var(--primary);
                font-size: 1.1rem;
            }

            .buy-card .form-group {
                margin-bottom: 1.5rem;
            }

            .buy-card .form-group label {
                font-weight: 600;
                color: var(--primary-dark);
                margin-bottom: 0.5rem;
                display: block;
            }

            .buy-card .form-control {
                border-radius: 12px;
                border: 2px solid rgba(26, 95, 63, 0.1);
                padding: 0.75rem 1rem;
                transition: all 0.3s ease;
            }

            .buy-card .form-control:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 4px rgba(26, 95, 63, 0.1);
                outline: none;
            }

            .buy-card .btn-primary {
                width: 100%;
                padding: 1rem;
                border-radius: 12px;
                font-weight: 600;
                font-size: 1.1rem;
                background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
                border: none;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .buy-card .btn-primary::before {
                content: "";
                position: absolute;
                top: 50%;
                left: 50%;
                width: 0;
                height: 0;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.3);
                transform: translate(-50%, -50%);
                transition: width 0.6s, height 0.6s;
            }

            .buy-card .btn-primary:hover::before {
                width: 300px;
                height: 300px;
            }

            .buy-card .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(26, 95, 63, 0.3);
            }

            /* Info list styling */
            .info-list {
                list-style: none;
                padding: 0;
                margin: 1.5rem 0;
            }

            .info-list li {
                padding: 0.75rem 0;
                border-bottom: 1px solid rgba(26, 95, 63, 0.08);
                display: flex;
                align-items: center;
                transition: all 0.3s ease;
            }

            .info-list li:last-child {
                border-bottom: none;
            }

            .info-list li:hover {
                padding-left: 8px;
                color: var(--primary);
            }

            .info-list li i {
                margin-left: 8px;
                color: var(--primary);
                font-size: 1.2rem;
            }

            /* Carousel enhancements */
            .show-carousel {
                border-radius: 20px;
                overflow: hidden;
                box-shadow: 0 15px 50px rgba(26, 95, 63, 0.15);
                margin-bottom: 2rem;
                max-width: 100%;
                transition: all 0.4s ease;
            }

            .show-carousel:hover {
                box-shadow: 0 20px 65px rgba(26, 95, 63, 0.22);
            }

            .show-carousel .carousel-inner img {
                border-radius: 20px;
                transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
                object-fit: cover;
                max-height: 420px;
                width: 100%;
            }

            .show-carousel:hover .carousel-inner img {
                transform: scale(1.05);
            }

            .carousel-control-prev,
            .carousel-control-next {
                width: 55px;
                height: 55px;
                background: rgba(26, 95, 63, 0.92);
                border-radius: 50%;
                top: 50%;
                transform: translateY(-50%);
                opacity: 0.85;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            }

            .carousel-control-prev {
                right: auto;
                left: 20px;
            }

            .carousel-control-next {
                left: auto;
                right: 20px;
            }

            .carousel-control-prev:hover,
            .carousel-control-next:hover {
                opacity: 1;
                transform: translateY(-50%) scale(1.15);
                background: var(--primary);
                box-shadow: 0 6px 25px rgba(26, 95, 63, 0.4);
            }

            .carousel-control-prev-icon,
            .carousel-control-next-icon {
                width: 28px;
                height: 28px;
                filter: drop-shadow(0 3px 6px rgba(0, 0, 0, .4));
                transition: all 0.3s ease;
            }

            .carousel-control-prev:hover .carousel-control-prev-icon,
            .carousel-control-next:hover .carousel-control-next-icon {
                filter: drop-shadow(0 4px 8px rgba(0, 0, 0, .5));
            }

            .visually-hidden {
                display: none;
            }

            .carousel-indicators button {
                width: 12px !important;
                height: 12px !important;
                border-radius: 50% !important;
                margin: 0 6px !important;
                background: rgba(26, 95, 63, 0.5) !important;
                border: 0 !important;
                transition: all 0.3s ease;
            }

            .carousel-indicators button.active {
                background: var(--primary) !important;
                transform: scale(1.3);
            }

            /* Placeholder styling */
            .placeholder-logo {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 360px;
                background: linear-gradient(135deg, #f7f9fc 0%, #e8f0f7 100%);
                border-radius: 20px;
                transition: all 0.3s ease;
            }

            .placeholder-logo:hover {
                transform: scale(1.02);
            }

            .placeholder-logo img {
                height: 80px;
                opacity: .85;
                transition: all 0.3s ease;
            }

            .placeholder-logo:hover img {
                transform: scale(1.1);
                opacity: 1;
            }

            /* PDF Section Styling */
            .btn-outline-danger {
                border: 2px solid #dc3545;
                color: #dc3545;
                background: white;
                transition: all 0.3s ease;
            }

            .btn-outline-danger:hover {
                background: #dc3545;
                color: white;
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(220, 53, 69, 0.3);
            }

            .btn-danger {
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
                border: none;
                transition: all 0.3s ease;
            }

            .btn-danger:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(220, 53, 69, 0.4);
            }

            .btn-lg {
                padding: 12px 24px;
                font-size: 1.05rem;
                font-weight: 600;
                border-radius: 12px;
            }
        </style>
    @endpush

    <div class="content-wrapper container">
        <div class="section-header">
            <div class="card section-card">
                <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                    <div class="mb-2 mb-md-0">
                        <h3 class="mb-1" style="font-weight: 800; font-size: 1.85rem; letter-spacing: 0.5px;">
                            {{ $offer->title }}</h3>
                        <p class="text-muted mb-0" style="font-size: 1.05rem;">{{ __('تفاصيل العرض ومعلومات الشراء') }}</p>
                    </div>
                    <div>
                        <a href="{{ route('marketplace.offers.index') }}" class="btn btn-outline-primary">
                            <i class="mdi mdi-arrow-right me-2"></i>
                            {{ __('العودة للعروض') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @php($media = is_array($offer->media) ? $offer->media : (is_string($offer->media) ? (json_decode($offer->media, true) ?: []) : []))
        @php($carouselId = 'offerShowCarousel_' . $offer->id)
        <div class="row mb-4">
            <div class="col-lg-10 col-xl-9 mx-auto">
                @if (!empty($media))
                    <div id="{{ $carouselId }}" class="carousel slide show-carousel">
                        <div class="carousel-inner">
                            @foreach ($media as $idx => $img)
                                <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $img) }}" alt="img" class="d-block w-100"
                                        style="max-height: 420px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                        @if (count($media) > 1)
                            <div class="carousel-indicators" style="bottom: 15px;">
                                @foreach ($media as $idx => $img)
                                    <button type="button" data-carousel-to="{{ $idx }}"
                                        class="{{ $idx === 0 ? 'active' : '' }}"
                                        aria-label="Slide {{ $idx + 1 }}"></button>
                                @endforeach
                            </div>
                        @endif
                        @if (count($media) > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#{{ $carouselId }}"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#{{ $carouselId }}"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            </button>
                        @endif
                    </div>
                @elseif ($offer->cover_image)
                    <div class="show-carousel">
                        <img src="{{ asset($offer->cover_image) }}" alt="cover" class="img-fluid"
                            style="border-radius: 20px; width: 100%;">
                    </div>
                @else
                    <div class="placeholder-logo">
                        <img src="{{ asset('assets/images/logo-w.png') }}" alt="logo">
                    </div>
                @endif
            </div>
        </div>

        <div class="row align-items-start">
            <div class="col-md-7 mb-4">
                <div class="details-card fade-in">
                    <h3>
                        <i class="mdi mdi-home-city-outline me-2"></i>
                        {{ $offer->title }}
                    </h3>
                    <p class="text-muted">{{ $offer->description }}</p>
                    <ul class="info-list">
                        <li>
                            <i class="mdi mdi-earth"></i>
                            <strong>{{ __('الدولة') }}:</strong> السعودية
                        </li>
                        <li>
                            <i class="mdi mdi-city"></i>
                            <strong>{{ __('المدينة') }}:</strong> {{ $offer->city }}
                        </li>
                        <li>
                            <i class="mdi mdi-map-marker"></i>
                            <strong>{{ __('العنوان') }}:</strong> {{ $offer->address }}
                        </li>
                    </ul>
                </div>

                <!-- Real Estate Review PDF -->
                @if ($offer->approval_status === 'real_estate_approved' && $offer->realEstateCheckpoints->isNotEmpty())
                    <div class="details-card fade-in mt-4">
                        <h3>
                            <i class="mdi mdi-file-pdf-box text-danger me-2"></i>
                            تقرير المراجعة العقارية
                        </h3>
                        <p class="text-muted mb-3">
                            تقرير شامل يحتوي على نقاط المراجعة العقارية المعتمدة لهذا العرض
                        </p>

                        <div class="d-flex gap-3 flex-wrap">
                            <a href="{{ route('marketplace.offers.pdf', $offer->id) }}" target="_blank"
                                class="btn btn-outline-danger btn-lg">
                                <i class="mdi mdi-file-eye me-2"></i>
                                فتح التقرير
                            </a>
                            <a href="{{ route('marketplace.offers.pdf', $offer->id) }}?download=1"
                                class="btn btn-danger btn-lg">
                                <i class="mdi mdi-download me-2"></i>
                                تحميل التقرير
                            </a>
                        </div>

                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="mdi mdi-information-outline me-1"></i>
                                يحتوي التقرير على {{ $offer->realEstateCheckpoints->count() }} نقطة من نقاط المراجعة
                            </small>
                        </div>
                    </div>
                @endif
            </div>

            @php($isLoggedIn = \Illuminate\Support\Facades\Auth::guard('web')->check())
            @php($currentUser = \Illuminate\Support\Facades\Auth::guard('web')->user())
            <div class="col-md-5 mb-4">
                <div class="card buy-card fade-in-delay-1">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="mdi mdi-cart-outline me-2"></i>
                            {{ __('شراء أسهم') }}
                        </h5>
                        <div class="price-info">
                            <p>
                                <i class="mdi mdi-cash me-2"></i>
                                <strong>{{ __('السعر/سهم') }}:</strong> {{ number_format($offer->price_per_share, 2) }}
                                {{ $offer->currency }}
                            </p>
                            <p>
                                <i class="mdi mdi-chart-pie me-2"></i>
                                <strong>{{ __('المتاح') }}:</strong> {{ $offer->available_shares }} /
                                {{ $offer->total_shares }}
                            </p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $e)
                                        <li>{{ $e }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div id="buy-error" class="alert alert-warning d-none"></div>
                        <form id="buy-form" method="POST"
                            action="{{ route('marketplace.offers.buy', ['offer' => $offer->id]) }}">
                            @csrf
                            @if ($isLoggedIn)
                                {{-- لا نعرض حقول بيانات المشتري عند تسجيل الدخول؛ نمررها مخفية إن توفرت --}}
                                @if ($currentUser)
                                    <input type="hidden" name="full_name" value="{{ $currentUser->name ?? '' }}">
                                    @if (!empty($currentUser->email))
                                        <input type="hidden" name="email" value="{{ $currentUser->email }}">
                                    @endif
                                    @if (!empty($currentUser->phone))
                                        <input type="hidden" name="phone" value="{{ $currentUser->phone }}">
                                    @endif
                                @endif
                            @else
                                <div id="buyer-info" style="display:none;">
                                    <div class="form-group">
                                        <label>{{ __('الاسم الكامل') }}</label>
                                        <input name="full_name" class="form-control" autocomplete="name">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('البريد الإلكتروني') }}</label>
                                        <input name="email" type="email" class="form-control" autocomplete="email">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('الجوال') }}</label>
                                        <input name="phone" class="form-control" autocomplete="tel">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('الهوية الوطنية (اختياري)') }}</label>
                                        <input name="national_id" class="form-control">
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label>{{ __('عدد الأسهم') }}</label>
                                <input name="shares" type="number" min="1"
                                    max="{{ (int) $offer->available_shares }}" class="form-control" required>
                            </div>
                            <button type="button" id="buy-btn"
                                class="btn btn-primary">{{ __('تنفيذ الشراء') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script>
        // Buy button logic with pre-check availability
        document.addEventListener('DOMContentLoaded', function() {
            (function() {
                var buyBtn = document.getElementById('buy-btn');
                var form = document.getElementById('buy-form');
                var errorBox = document.getElementById('buy-error');
                if (!buyBtn || !form) return;
                var isLoggedIn = {{ $isLoggedIn ? 'true' : 'false' }};
                buyBtn.addEventListener('click', function() {
                    var sharesInput = form.querySelector('input[name="shares"]');
                    var requested = parseInt(sharesInput.value, 10);
                    if (!requested || requested < 1) {
                        if (errorBox) {
                            errorBox.textContent = '{{ __('أدخل عدد أسهم صحيح') }}';
                            errorBox.classList.remove('d-none');
                        }
                        return;
                    }
                    var checkUrl =
                        '{{ route('marketplace.offers.availability', ['offer' => $offer->id]) }}' +
                        '?shares=' + encodeURIComponent(requested);
                    fetch(checkUrl, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    }).then(function(r) {
                        return r.json();
                    }).then(function(res) {
                        if (!res.ok) {
                            if (errorBox) {
                                errorBox.textContent = res.message ||
                                    '{{ __('الكمية غير متاحة حالياً') }}';
                                errorBox.classList.remove('d-none');
                            }
                            return;
                        }
                        if (!isLoggedIn) {
                            var loginUrl =
                                '{{ route('marketplace.login', ['intended' => request()->fullUrl()]) }}';
                            window.location.href = loginUrl;
                            return;
                        }
                        if (errorBox) {
                            errorBox.classList.add('d-none');
                        }
                        form.submit();
                    }).catch(function() {
                        form.submit();
                    });
                });
            })();
        });
    </script>
@endpush
