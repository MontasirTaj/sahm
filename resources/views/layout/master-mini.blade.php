<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <title>سهمي - منصة الاستثمار العقاري</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="_token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('assets/images/sahmi.jpeg') }}">

    <!-- plugin css -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/@mdi/font/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}">
    <!-- end plugin css -->

    <!-- plugin css -->
    @stack('plugin-styles')
    <!-- end plugin css -->

    <!-- common css -->
    @if (app()->getLocale() === 'ar')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Tajawal:wght@400;500;700&display=swap"
            rel="stylesheet">
    @endif
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sahmi.css') }}">
    @if (app()->getLocale() === 'ar')
        <link rel="stylesheet" href="{{ asset('css/rtl.css') }}">
    @endif
    <!-- end common css -->

    <!-- marketing layout styles (navbar, hero, footer, sections) -->
    <style>
        :root {
            --di-primary: var(--primary);
        }

        .di-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .di-navbar .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
        }

        .di-brand {
            display: flex;
            align-items: center;
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.5rem;
            gap: 12px;
        }

        .di-brand img {
            height: 42px;
            width: 42px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            object-fit: cover;
        }

        .di-brand span {
            font-family: 'Cairo', 'Tajawal', sans-serif;
            font-weight: 900;
            letter-spacing: 1.5px;
            background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .di-brand span::after {
            content: 'سهمي';
            position: absolute;
            left: 0;
            top: 0;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .di-brand:hover span::after {
            opacity: 0.3;
        }

        .di-nav-links {
            display: flex;
            align-items: center;
        }

        .di-nav-links a {
            color: #fff;
            margin: 0 10px;
            text-decoration: none;
            opacity: .95;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .di-nav-links a:hover {
            opacity: 1;
            transform: translateY(-1px);
        }

        .di-nav-links .btn {
            margin-inline-start: 8px;
            padding: 0.5rem 1.2rem;
            font-size: 0.9rem;
            border-radius: 8px;
        }

        .di-nav-links .btn-light {
            background: #ffffff;
            border-color: #ffffff;
            color: var(--primary);
            font-weight: 600;
        }

        .di-nav-links .btn-light:hover {
            background: var(--secondary);
            border-color: var(--secondary);
            color: #ffffff;
        }

        .di-nav-links .btn-outline-light {
            background: transparent;
            border-color: #ffffff;
            color: #ffffff;
            border-width: 2px;
        }

        .di-nav-links .btn-outline-light:hover {
            background: #ffffff;
            border-color: #ffffff;
            color: var(--primary);
        }

        .di-nav-links .mx-2 {
            opacity: 0.4;
            margin: 0 8px;
        }

        .di-nav-toggle {
            display: none;
            background: transparent;
            border: none;
            color: #fff;
            font-size: 24px;
            cursor: pointer;
            padding: 8px;
        }

        .di-nav-toggle:hover {
            color: var(--secondary);
        }

        /* Mobile responsive */
        @media (max-width: 991px) {
            .di-nav-toggle {
                display: inline-block;
            }

            .di-nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--primary);
                padding: 10px 0;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
                flex-direction: column;
            }

            .di-nav-links.show {
                display: flex;
            }

            .di-nav-links a,
            .di-nav-links .btn {
                display: block;
                padding: 12px 20px;
                margin: 4px 20px;
                width: calc(100% - 40px);
                text-align: start;
            }

            .di-nav-links .btn-light {
                background: #ffffff;
                color: var(--primary);
            }

            .di-nav-links .btn-outline-light {
                background: transparent;
                color: #ffffff;
                border-color: #ffffff;
            }

            .di-nav-links .mx-2 {
                display: none;
            }

            .di-nav-links form {
                width: 100%;
                padding: 0 20px;
            }

            .di-nav-links form button {
                width: calc(100% - 40px);
                margin: 4px 0;
            }
        }

        .di-section-alt {
            background: #f7f9fc;
        }

        .di-section-gradient {
            background: linear-gradient(135deg, #0f2544 0%, #143a66 60%, #1b4f88 100%);
            color: #fff;
        }

        .di-section-contrast {
            background: #fff;
        }

        /* Ensure page content appears below fixed navbar */
        .page-body-wrapper.full-page-wrapper .content-wrapper {
            padding-top: 84px;
        }

        /* Footer styling shared */
        .di-footer {
            padding-top: 3rem;
            padding-bottom: 2.5rem;
        }

        #contact {
            background-color: var(--primary);
            box-shadow: 0 -18px 40px rgba(0, 0, 0, 0.4);
        }

        #contact h6 {
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        #contact a {
            text-decoration: none;
        }
    </style>

    @stack('style')
</head>

<body data-base-url="{{ url('/') }}" class="{{ app()->getLocale() === 'ar' ? 'rtl' : '' }}">

    <div class="container-scroller" id="app">
        <!-- Shared marketing navbar -->
        <nav class="di-navbar">
            <div class="container">
                <a href="{{ route('landing') }}" class="di-brand">
                    <img src="{{ asset('assets/images/sahmi.jpeg') }}" alt="سهمي">
                    <span>سهمي</span>
                </a>
                <button class="di-nav-toggle" type="button" aria-label="Toggle navigation"><span
                        class="mdi mdi-menu"></span></button>
                <div class="di-nav-links" id="diNavLinks">
                    @php($loggedIn = Illuminate\Support\Facades\Auth::guard('web')->check())
                    <a href="{{ route('marketplace.offers.index') }}">{{ __('العروض') }}</a>
                    <a href="{{ route('buyer.secondary-market.index') }}">{{ __('السوق الثانوي') }}</a>
                    <a href="{{ route('guide') }}"><i class="mdi mdi-book-open-variant"></i> {{ __('الدليل الشامل') }}</a>
                    @if (!$loggedIn)
                        <a href="{{ route('landing') }}#plans">{{ __('Choose Your Plan') }}</a>
                        <a href="{{ route('static.about') }}">{{ __('app.about_title') }}</a>
                        <a href="{{ route('static.faq') }}">{{ __('app.faq_title') }}</a>
                        <a href="{{ route('landing') }}#contact">{{ __('app.contact') }}</a>
                    @endif
                    <span class="mx-2">|</span>
                    @php($currentLocale = app()->getLocale())
                    @php($locales = Mcamara\LaravelLocalization\Facades\LaravelLocalization::getSupportedLocales())
                    @foreach ($locales as $code => $props)
                        @if ($code !== $currentLocale)
                            <a href="{{ Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL($code, null, [], true) }}"
                                title="{{ $props['native'] }}">
                                <i class="mdi mdi-web"></i> {{ strtoupper($code) }}
                            </a>
                        @endif
                    @endforeach
                    <span class="mx-2">|</span>
                    @if ($loggedIn)
                        @php($user = Illuminate\Support\Facades\Auth::guard('web')->user())
                        @php($buyer = \App\Models\Central\Buyer::on('central')->where('user_id', $user->id)->first())
                        <span style="color: white; margin-inline-end: 10px; font-weight: 500;">
                            <i class="mdi mdi-account-circle"></i> {{ $buyer ? $buyer->name : $user->name }}
                        </span>

                        <!-- Notifications Bell -->
                        @if ($buyer)
                            <div class="dropdown" style="display: inline-block; margin-inline-start: 8px;">
                                <button class="btn btn-sm btn-outline-light position-relative" type="button"
                                    id="notificationBell" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="mdi mdi-bell"></i>
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                        id="notificationCount" style="display: none; font-size: 0.7rem;">
                                        0
                                    </span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationBell"
                                    id="notificationDropdown"
                                    style="width: 350px; max-height: 400px; overflow-y: auto; direction: rtl;">
                                    <h6 class="dropdown-header"><i class="mdi mdi-bell"></i> التنبيهات</h6>
                                    <div id="notificationList">
                                        <div class="dropdown-item text-center text-muted">
                                            <small>لا توجد تنبيهات جديدة</small>
                                        </div>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-center text-primary"
                                        href="{{ route('buyer.notifications.index') }}">
                                        <small><i class="mdi mdi-eye"></i> عرض جميع التنبيهات</small>
                                    </a>
                                </div>
                            </div>
                        @endif

                        <a href="{{ route('buyer.dashboard') }}" class="btn btn-sm btn-light">
                            <i class="mdi mdi-view-dashboard"></i> {{ __('لوحة التحكم') }}
                        </a>
                        <a href="{{ route('buyer.wallet.index') }}" class="btn btn-sm btn-success"
                            style="margin-inline-start:8px;">
                            <i class="mdi mdi-wallet"></i> {{ __('المحفظة') }}
                        </a>
                        <a href="{{ route('buyer.secondary-market.index') }}" class="btn btn-sm"
                            style="margin-inline-start:8px; background: #06b6d4; border-color: #06b6d4; color: white;">
                            <i class="mdi mdi-storefront"></i> {{ __('السوق الثانوي') }}
                        </a>
                        <form action="{{ route('marketplace.logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-light"
                                style="margin-inline-start:8px;">{{ __('خروج') }}</button>
                        </form>
                    @else
                        <a href="{{ route('marketplace.login') }}"
                            class="btn btn-sm btn-light">{{ __('دخول') }}</a>
                    @endif
                </div>
            </div>
        </nav>

        <div class="container-fluid page-body-wrapper full-page-wrapper">
            @yield('content')
        </div>

        <!-- Shared marketing footer -->
        <section id="contact" class="py-5 di-bg-primary text-white">
            <div class="container di-footer">
                <footer class="row">
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('assets/images/sahmi.jpeg') }}" alt="سهمي"
                                style="height:48px; width:48px; border-radius:10px; object-fit:cover; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                            <span
                                style="font-size:1.75rem; font-weight:900; margin-inline-start:14px; letter-spacing:1px;">سهمي</span>
                        </div>
                        <p class="mt-3" style="opacity:.95; color:#fff; line-height:1.8;">
                            منصة استثمار عقاري متكاملة توفر لك فرص استثمارية متنوعة في العقارات السعودية
                        </p>
                        <div class="mt-2">
                            <a href="#" class="di-me-2" style="color:#fff; opacity:.9;"><i
                                    class="mdi mdi-twitter"></i></a>
                            <a href="#" class="di-me-2" style="color:#fff; opacity:.9;"><i
                                    class="mdi mdi-linkedin"></i></a>
                            <a href="#" class="di-me-2" style="color:#fff; opacity:.9;"><i
                                    class="mdi mdi-facebook"></i></a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h6 class="mb-2">{{ __('app.resources') }}</h6>
                        <ul class="list-unstyled" style="opacity:.9;">
                            <li class="mb-2"><a href="{{ route('marketplace.offers.index') }}"
                                    style="color:#fff;">{{ __('العروض المتاحة') }}</a></li>
                            <li class="mb-2"><a href="{{ route('landing') }}#plans"
                                    style="color:#fff;">{{ __('Choose Your Plan') }}</a></li>
                            <li class="mb-2"><a href="{{ route('static.about') }}"
                                    style="color:#fff;">{{ __('app.about_title') }}</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h6 cla class="mb-2"><i class="mdi mdi-email-outline mr-1"></i> info@sahmi.sa</li>
                            <li class="mb-2"><i class="mdi mdi-phone-outline mr-1"></i> +966-55-123-4567</li>
                            <li class="mb-2"><i class="mdi mdi-whatsapp mr-1"></i> +966-55-987-6543</li>
                            <li class="mb-2"><i class="mdi mdi-map-marker-outline mr-1"></i> الرياض، المملكة العربية
                                السعودية
                            <li><i class="mdi mdi-whatsapp mr-1"></i> +966-55-987-6543</li>
                            <li><i class="mdi mdi-map-marker-outline mr-1"></i> {{ __('Riyadh, Saudi Arabia') }}</li>
                            </ul>
                    </div>
                    <div class="col-12 text-center mt-3" style="opacity:.8;">
                        <small>© {{ date('Y') }} سهمي — منصة الاستثمار العقاري</small>
                    </div>
                </footer>
            </div>
        </section>
    </div>

    <!-- base js -->
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- end base js -->

    <!-- plugin js -->
    @stack('plugin-scripts')
    <!-- end plugin js -->

    @include('layout.partials.carousel')
    @stack('custom-scripts')
    @include('layout.partials.auto-logout')

    <!-- Navbar toggle and close on outside click -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navToggle = document.querySelector('.di-nav-toggle');
            const navLinks = document.querySelector('.di-nav-links');
            const navbar = document.querySelector('.di-navbar');

            if (navToggle && navLinks) {
                // Toggle menu
                navToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    navLinks.classList.toggle('show');
                });

                // Close menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (!navbar.contains(e.target) && navLinks.classList.contains('show')) {
                        navLinks.classList.remove('show');
                    }
                });

                // Prevent closing when clicking inside nav links
                navLinks.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            // Fetch notification count and list
            @if ($loggedIn && $buyer)
                fetchNotifications();
                // Update notifications every 30 seconds
                setInterval(fetchNotifications, 30000);
            @endif
        });

        @if ($loggedIn && isset($buyer))
            function fetchNotifications() {
                fetch('{{ route('buyer.notifications.latest') }}')
                    .then(response => response.json())
                    .then(data => {
                        const badge = document.getElementById('notificationCount');
                        const bell = document.getElementById('notificationBell');
                        const notificationList = document.getElementById('notificationList');

                        // Filter only unread notifications
                        const unreadNotifications = data.notifications.filter(n => !n.is_read);
                        const count = unreadNotifications.length;

                        // Update badge
                        if (badge && count > 0) {
                            badge.textContent = count > 99 ? '99+' : count;
                            badge.style.display = 'inline-block';

                            // Add animation class
                            bell.classList.add('notification-pulse');
                        } else if (badge) {
                            badge.style.display = 'none';
                            bell.classList.remove('notification-pulse');
                        }

                        // Update dropdown list
                        if (notificationList) {
                            if (count === 0) {
                                notificationList.innerHTML = `
                                    <div class="dropdown-item text-center text-muted">
                                        <small>لا توجد تنبيهات جديدة</small>
                                    </div>
                                `;
                            } else {
                                notificationList.innerHTML = unreadNotifications.map(notification => {
                                    const icon = getNotificationIcon(notification.type);
                                    const color = getNotificationColor(notification.type);
                                    const time = formatTime(notification.created_at);

                                    return `
                                        <a class="dropdown-item notification-item" href="{{ route('buyer.notifications.index') }}#notification-${notification.id}" 
                                           onclick="markAsReadAndGo(event, ${notification.id})"
                                           style="white-space: normal; padding: 12px 20px; border-bottom: 1px solid #eee;">
                                            <div class="d-flex align-items-start">
                                                <i class="${icon}" style="font-size: 1.5rem; color: ${color}; margin-left: 10px;"></i>
                                                <div style="flex: 1;">
                                                    <strong style="font-size: 0.9rem; display: block; margin-bottom: 4px;">${notification.title}</strong>
                                                    <p style="font-size: 0.85rem; color: #666; margin: 0; line-height: 1.4;">${notification.message}</p>
                                                    <small style="color: #999; margin-top: 4px; display: block;"><i class="mdi mdi-clock-outline"></i> ${time}</small>
                                                </div>
                                            </div>
                                        </a>
                                    `;
                                }).join('');
                            }
                        }
                    })
                    .catch(error => console.error('خطأ في جلب التنبيهات:', error));
            }

            function getNotificationIcon(type) {
                const icons = {
                    'sale_completed': 'mdi mdi-check-circle',
                    'purchase_completed': 'mdi mdi-shopping',
                    'sale_offer_interest': 'mdi mdi-eye',
                    'wallet_deposit': 'mdi mdi-cash-plus',
                    'wallet_withdrawal': 'mdi mdi-cash-minus'
                };
                return icons[type] || 'mdi mdi-information';
            }

            function getNotificationColor(type) {
                const colors = {
                    'sale_completed': '#10b981',
                    'purchase_completed': '#3b82f6',
                    'sale_offer_interest': '#f59e0b',
                    'wallet_deposit': '#10b981',
                    'wallet_withdrawal': '#ef4444'
                };
                return colors[type] || '#6b7280';
            }

            function formatTime(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diffMs = now - date;
                const diffMins = Math.floor(diffMs / 60000);
                const diffHours = Math.floor(diffMs / 3600000);
                const diffDays = Math.floor(diffMs / 86400000);

                if (diffMins < 1) return 'الآن';
                if (diffMins < 60) return `منذ ${diffMins} دقيقة`;
                if (diffHours < 24) return `منذ ${diffHours} ساعة`;
                if (diffDays < 7) return `منذ ${diffDays} يوم`;

                return date.toLocaleDateString('ar-SA', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            }

            function markAsReadAndGo(event, notificationId) {
                event.preventDefault();

                // Mark as read
                fetch(`{{ route('buyer.notifications.read', ':id') }}`.replace(':id', notificationId), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(() => {
                        // Navigate to notifications page with hash
                        window.location.href =
                            `{{ route('buyer.notifications.index') }}#notification-${notificationId}`;
                    })
                    .catch(error => console.error('خطأ في تحديث حالة التنبيه:', error));
            }
        @endif
    </script>

    <style>
        @keyframes bellPulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        .notification-pulse {
            animation: bellPulse 2s infinite;
        }

        /* Notification dropdown styles */
        #notificationDropdown {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            border: none;
            border-radius: 8px;
        }

        #notificationDropdown .dropdown-header {
            background: linear-gradient(135deg, var(--primary), #1a5f3f);
            color: white;
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 8px 8px 0 0;
        }

        .notification-item {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .notification-item:hover {
            background-color: #f8f9fa !important;
            transform: translateX(-3px);
        }

        .notification-item:active {
            background-color: #e9ecef !important;
        }
    </style>
</body>

</html>
