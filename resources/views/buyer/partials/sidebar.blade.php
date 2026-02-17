@php
    $buyer = App\Models\Central\Buyer::on('central')
        ->where('user_id', auth()->id())
        ->first();
    $currentRoute = Route::currentRouteName();
    $unreadCount = $buyer ? $buyer->unreadNotifications()->count() : 0;
@endphp

<style>
    .buyer-sidebar {
        position: fixed;
        top: 0;
        right: 0;
        width: 280px;
        height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: -2px 0 20px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        transition: transform 0.3s ease;
        overflow-y: auto;
    }

    body[dir="rtl"] .buyer-sidebar {
        right: 0;
        left: auto;
    }

    body[dir="ltr"] .buyer-sidebar {
        left: 0;
        right: auto;
    }

    .buyer-sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .buyer-sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
    }

    .buyer-sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 3px;
    }

    .sidebar-brand-section {
        padding: 2rem 1.5rem;
        background: rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .sidebar-logo-img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: 3px solid rgba(255, 255, 255, 0.3);
        padding: 5px;
        background: white;
        margin-bottom: 0.75rem;
    }

    .sidebar-brand-text {
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
    }

    .sidebar-user-card {
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        margin: 1rem;
        border-radius: 12px;
        text-align: center;
    }

    .sidebar-user-avatar {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        border: 3px solid white;
    }

    .sidebar-user-avatar i {
        font-size: 2.5rem;
        color: white;
    }

    .sidebar-user-name {
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 0.25rem;
    }

    .sidebar-user-email {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.85rem;
    }

    .sidebar-nav {
        padding: 0 1rem 2rem;
    }

    .sidebar-nav-item {
        margin-bottom: 0.35rem;
    }

    .sidebar-nav-link {
        display: flex;
        align-items: center;
        padding: 0.85rem 1rem;
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        border-radius: 10px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .sidebar-nav-link:hover {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        transform: translateX(-3px);
    }

    body[dir="ltr"] .sidebar-nav-link:hover {
        transform: translateX(3px);
    }

    .sidebar-nav-link.active {
        background: rgba(255, 255, 255, 0.25);
        color: white;
        font-weight: 600;
    }

    .sidebar-nav-link.active::before {
        content: '';
        position: absolute;
        right: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: white;
        border-radius: 2px 0 0 2px;
    }

    body[dir="ltr"] .sidebar-nav-link.active::before {
        right: auto;
        left: 0;
        border-radius: 0 2px 2px 0;
    }

    .sidebar-nav-icon {
        font-size: 1.35rem;
        margin-left: 0.75rem;
        min-width: 28px;
        text-align: center;
    }

    body[dir="ltr"] .sidebar-nav-icon {
        margin-left: 0;
        margin-right: 0.75rem;
    }

    .sidebar-nav-text {
        flex: 1;
    }

    .sidebar-nav-badge {
        background: #ff4757;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        min-width: 24px;
        text-align: center;
    }

    .sidebar-divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.2);
        margin: 1rem 1.5rem;
    }

    .sidebar-nav-link.logout-link {
        color: #ff6b6b;
        background: rgba(255, 255, 255, 0.15);
    }

    .sidebar-nav-link.logout-link:hover {
        background: rgba(255, 107, 107, 0.2);
        color: #ff4757;
    }

    /* Mobile Toggle Button */
    .sidebar-mobile-toggle {
        display: none;
        position: fixed;
        top: 80px;
        right: 20px;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        border: none;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        z-index: 999;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    body[dir="ltr"] .sidebar-mobile-toggle {
        right: auto;
        left: 20px;
    }

    .sidebar-mobile-toggle:hover {
        transform: scale(1.1);
    }

    .sidebar-mobile-toggle:active {
        transform: scale(0.95);
    }

    /* Content Margin */
    .main-content-with-sidebar {
        margin-right: 280px;
        transition: margin 0.3s ease;
    }

    body[dir="ltr"] .main-content-with-sidebar {
        margin-right: 0;
        margin-left: 280px;
    }

    /* Mobile Responsive */
    @media (max-width: 991px) {
        .buyer-sidebar {
            transform: translateX(100%);
        }

        body[dir="ltr"] .buyer-sidebar {
            transform: translateX(-100%);
        }

        .buyer-sidebar.show {
            transform: translateX(0);
        }

        .sidebar-mobile-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .main-content-with-sidebar {
            margin-right: 0;
            margin-left: 0;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar-overlay.show {
            display: block;
        }
    }

    @media (min-width: 992px) {
        .sidebar-overlay {
            display: none !important;
        }
    }
</style>

<!-- Mobile Toggle Button -->
<button class="sidebar-mobile-toggle" id="sidebarMobileToggle">
    <i class="mdi mdi-menu"></i>
</button>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<div class="buyer-sidebar" id="buyerSidebar">
    <!-- Brand Section -->
    <div class="sidebar-brand-section">
        <img src="{{ asset('assets/images/sahmi.jpeg') }}" alt="سهمي" class="sidebar-logo-img">
        <h3 class="sidebar-brand-text">سهمي</h3>
    </div>

    <!-- User Card -->
    <div class="sidebar-user-card">
        <div class="sidebar-user-avatar">
            <i class="mdi mdi-account"></i>
        </div>
        <div class="sidebar-user-name">{{ $buyer->first_name ?? 'المستخدم' }} {{ $buyer->last_name ?? '' }}</div>
        <div class="sidebar-user-email">{{ auth()->user()->email }}</div>
    </div>

    <!-- Navigation Menu -->
    <nav class="sidebar-nav">
        <ul class="list-unstyled">
            <li class="sidebar-nav-item">
                <a href="{{ route('buyer.dashboard') }}"
                    class="sidebar-nav-link {{ $currentRoute === 'buyer.dashboard' ? 'active' : '' }}">
                    <i class="mdi mdi-view-dashboard sidebar-nav-icon"></i>
                    <span class="sidebar-nav-text">لوحة التحكم</span>
                </a>
            </li>

            <li class="sidebar-nav-item">
                <a href="{{ route('buyer.wallet.index') }}"
                    class="sidebar-nav-link {{ Str::startsWith($currentRoute, 'buyer.wallet') ? 'active' : '' }}">
                    <i class="mdi mdi-wallet sidebar-nav-icon"></i>
                    <span class="sidebar-nav-text">المحفظة المالية</span>
                </a>
            </li>

            <li class="sidebar-nav-item">
                <a href="{{ route('marketplace.offers.index') }}" class="sidebar-nav-link">
                    <i class="mdi mdi-store sidebar-nav-icon"></i>
                    <span class="sidebar-nav-text">العروض المتاحة</span>
                </a>
            </li>

            <li class="sidebar-nav-item">
                <a href="{{ route('buyer.secondary-market.index') }}"
                    class="sidebar-nav-link {{ Str::startsWith($currentRoute, 'buyer.secondary-market') ? 'active' : '' }}">
                    <i class="mdi mdi-shopping sidebar-nav-icon"></i>
                    <span class="sidebar-nav-text">السوق الثانوي</span>
                </a>
            </li>

            <li class="sidebar-nav-item">
                <a href="{{ route('buyer.notifications.index') }}"
                    class="sidebar-nav-link {{ Str::startsWith($currentRoute, 'buyer.notifications') ? 'active' : '' }}">
                    <i class="mdi mdi-bell sidebar-nav-icon"></i>
                    <span class="sidebar-nav-text">التنبيهات</span>
                    @if ($unreadCount > 0)
                        <span class="sidebar-nav-badge">{{ $unreadCount }}</span>
                    @endif
                </a>
            </li>

            <div class="sidebar-divider"></div>

            <li class="sidebar-nav-item">
                <a href="{{ route('buyer.profile') }}"
                    class="sidebar-nav-link {{ Str::startsWith($currentRoute, 'buyer.profile') ? 'active' : '' }}">
                    <i class="mdi mdi-account-cog sidebar-nav-icon"></i>
                    <span class="sidebar-nav-text">الإعدادات</span>
                </a>
            </li>

            <li class="sidebar-nav-item">
                <a href="#"
                    onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();"
                    class="sidebar-nav-link logout-link">
                    <i class="mdi mdi-logout sidebar-nav-icon"></i>
                    <span class="sidebar-nav-text">تسجيل الخروج</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

<!-- Logout Form -->
<form id="sidebar-logout-form" action="{{ route('marketplace.logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('buyerSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarMobileToggle');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            });
        }

        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }

        // Add margin to main content on desktop
        if (window.innerWidth >= 992) {
            const mainContent = document.querySelector('.content-wrapper, .container-fluid, .container');
            if (mainContent && !mainContent.classList.contains('main-content-with-sidebar')) {
                mainContent.classList.add('main-content-with-sidebar');
            }
        }

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        });
    });
</script>
</form>

<!-- Sidebar Toggle Button (Mobile) -->
<button class="sidebar-toggle" id="sidebarToggle">
    <i class="mdi mdi-menu"></i>
</button>

@push('custom-styles')
    <style>
        /* Hide sidebar completely */
        .sahmi-sidebar,
        .sidebar-overlay,
        .sidebar-toggle {
            display: none !important;
        }

        /* Remove body margin since sidebar is hidden */
        body {
            margin-right: 0 !important;
        }
    </style>
@endpush
