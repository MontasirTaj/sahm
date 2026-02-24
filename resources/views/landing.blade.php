@extends('layout.master-mini')

@section('content')
    <div class="content-wrapper">
        @push('style')
            <style>
                :root {
                    --di-primary: var(--primary, #1A5F3F);
                }

                .testimonial-card::after,
                .faq-card::before,
                .faq-card::after {
                    display: none;
                }

                .counter {
                    font-size: 2rem;
                    font-weight: 600;
                }

                .muted {
                    color: #6c757d;
                }

                .readmore {
                    cursor: pointer;
                    color: var(--di-primary);
                    font-weight: 500;
                }

                .in-view {
                    animation: fadeInUp .6s ease both;
                }

                /* Hero carousel with modern enhancements */
                #diHeroCarousel .carousel-item {
                    min-height: 580px;
                    position: relative;
                    background-size: cover;
                    background-position: center;
                    transition: transform 0.5s ease;
                }

                #diHeroCarousel .di-hero-overlay {
                    position: absolute;
                    inset: 0;
                    background: linear-gradient(135deg, rgba(26, 95, 63, 0.75) 0%, rgba(15, 64, 41, 0.85) 100%);
                    z-index: 1;
                }

                #diHeroCarousel .di-hero-content {
                    position: relative;
                    z-index: 2;
                }

                #diHeroCarousel .container {
                    position: relative;
                    z-index: 2;
                }

                /* Ensure all slideshow text is white for readability */
                #diHeroCarousel,
                #diHeroCarousel .di-hero-content,
                #diHeroCarousel h1,
                #diHeroCarousel h2,
                #diHeroCarousel h3,
                #diHeroCarousel p,
                #diHeroCarousel a,
                #diHeroCarousel .lead,
                #diHeroCarousel .muted {
                    color: #ffffff;
                }

                #diHeroCarousel .di-btn-outline {
                    color: #ffffff;
                    border-color: #ffffff;
                }

                #diHeroCarousel .carousel-indicators li {
                    height: 4px;
                    background: rgba(255, 255, 255, 0.5);
                    transition: all 0.3s ease;
                }

                #diHeroCarousel .carousel-indicators li.active {
                    background: #fff;
                    transform: scaleX(1.5);
                }

                #diHeroCarousel .carousel-control-prev,
                #diHeroCarousel .carousel-control-next {
                    z-index: 3;
                    width: 56px;
                    height: 56px;
                    background: rgba(26, 95, 63, 0.9);
                    border-radius: 50%;
                    top: 50%;
                    transform: translateY(-50%);
                    opacity: 0.95;
                    transition: all 0.3s ease;
                }

                #diHeroCarousel .carousel-control-prev {
                    left: 20px;
                }

                #diHeroCarousel .carousel-control-next {
                    right: 20px;
                }

                #diHeroCarousel .carousel-control-prev:hover,
                #diHeroCarousel .carousel-control-next:hover {
                    opacity: 1;
                    transform: translateY(-50%) scale(1.12);
                    background: var(--primary);
                }

                #diHeroCarousel .carousel-control-prev-icon,
                #diHeroCarousel .carousel-control-next-icon {
                    width: 28px;
                    height: 28px;
                    filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.4));
                }

                #diHeroCarousel .sr-only,
                #diHeroCarousel .visually-hidden {
                    display: none;
                }

                .partner-card img {
                    filter: grayscale(30%);
                    opacity: .9;
                    transition: filter .2s ease, opacity .2s ease, transform 0.3s ease;
                }

                .partner-card:hover img {
                    filter: none;
                    opacity: 1;
                    transform: scale(1.05);
                }

                .partner-card img {
                    height: 56px;
                    width: auto;
                    object-fit: contain;
                }

                .section-title {
                    font-size: 2.25rem;
                    font-weight: 700;
                    letter-spacing: .2px;
                    color: var(--primary-dark);
                }

                .section-subtitle {
                    font-size: 1.05rem;
                    color: #6c757d;
                }

                @media (max-width: 576px) {
                    .section-title {
                        font-size: 1.85rem;
                    }
                }

                .di-section-alt {
                    background: linear-gradient(135deg, #f7f9fc 0%, #ffffff 50%, #f1f5f9 100%);
                }

                .di-section-gradient {
                    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 60%, var(--primary-light) 100%);
                    color: #fff;
                }

                .di-section-contrast {
                    background: #fff;
                }

                /* Inline badge for plan highlight */
                .di-badge {
                    display: inline-block;
                    background: var(--secondary);
                    color: #fff;
                    font-weight: 600;
                    font-size: .8rem;
                    padding: 2px 8px;
                    border-radius: 999px;
                    animation: pulse 2s ease infinite;
                }

                html[dir='rtl'] .di-badge {
                    margin-right: 8px;
                }

                .di-btn-group .btn+.btn {
                    margin-left: 12px;
                }

                html[dir='rtl'] .di-btn-group .btn+.btn {
                    margin-left: 0;
                    margin-right: 12px;
                }

                /* Logical margin utilities (start/end) for RTL/LTR */
                .di-ms-2 {
                    margin-left: .5rem;
                }

                .di-me-2 {
                    margin-right: .5rem;
                }

                html[dir='rtl'] .di-ms-2 {
                    margin-left: 0;
                    margin-right: .5rem;
                }

                html[dir='rtl'] .di-me-2 {
                    margin-right: 0;
                    margin-left: .5rem;
                }

                /* Sections background helpers */
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

                /* Hero offset and anchor scroll margin */
                .di-hero-carousel {
                    margin-top: 0;
                }

                #features,
                #plans,
                #testimonials,
                #faq,
                #contact {
                    scroll-margin-top: 80px;
                }

                /* RTL content alignment */
                html[dir='rtl'] .di-hero-content {
                    text-align: right;
                }

                html[dir='rtl'] .card .card-body {
                    text-align: right;
                }

                /* Collapse fallback */
                .collapse {
                    display: none;
                }

                .collapse.show {
                    display: block;
                }

                /* Pricing ribbons */
                .ribbon {
                    position: absolute;
                    top: 12px;
                    right: -8px;
                    background: #ffcc00;
                    color: #102c4f;
                    padding: 4px 10px;
                    font-weight: 600;
                    border-radius: 3px;
                }

                .plan-card {
                    position: relative;
                }

                /* Feature & solutions cards with professional modern styles */
                .feature-card {
                    border-radius: 20px;
                    border: 1px solid rgba(26, 95, 63, 0.08);
                    box-shadow: 0 10px 40px rgba(26, 95, 63, 0.08);
                    overflow: hidden;
                    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
                    background: #ffffff;
                    position: relative;
                }

                .feature-card::before {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 5px;
                    background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%);
                    transform: scaleX(0);
                    transform-origin: left;
                    transition: transform 0.5s ease;
                }

                .feature-card:hover::before {
                    transform: scaleX(1);
                }

                .feature-card .card-body {
                    padding: 2rem 1.7rem;
                }

                .feature-icon {
                    width: 56px;
                    height: 56px;
                    border-radius: 50%;
                    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    color: #ffffff;
                    margin-bottom: 1.2rem;
                    box-shadow: 0 10px 30px rgba(26, 95, 63, 0.35);
                    font-size: 1.5rem;
                    transition: all 0.3s ease;
                }

                .feature-card:hover .feature-icon {
                    transform: rotate(360deg) scale(1.1);
                    box-shadow: 0 15px 40px rgba(26, 95, 63, 0.5);
                }

                .feature-icon--subtle {
                    background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
                    color: var(--primary);
                    box-shadow: 0 8px 24px rgba(26, 95, 63, 0.15);
                    border: 2px solid rgba(26, 95, 63, 0.1);
                }

                .feature-card .card-title {
                    font-weight: 700;
                    margin-bottom: .8rem;
                    color: var(--primary);
                    font-size: 1.25rem;
                }

                .feature-card .card-text {
                    color: #4b5563;
                    line-height: 1.8;
                    font-size: 1rem;
                }

                .feature-card:hover {
                    transform: translateY(-12px);
                    box-shadow: 0 20px 60px rgba(26, 95, 63, 0.15);
                    border-color: rgba(26, 95, 63, 0.22);
                }

                /* Solutions cards with subtle brand background */
                .solutions-card {
                    background: linear-gradient(145deg, #f7f9fc 0%, #ffffff 45%, #f1f4fb 100%);
                }

                .solutions-card .card-title {
                    display: flex;
                    align-items: center;
                    gap: .5rem;
                }

                .solutions-card .card-title::before {
                    content: "";
                    width: 10px;
                    height: 10px;
                    border-radius: 999px;
                    background: var(--di-primary);
                    box-shadow: 0 0 0 4px rgba(26, 95, 63, 0.18);
                }

                /* Testimonials */
                .testimonial-card {
                    border: 1px solid rgba(0, 0, 0, .08);
                    border-radius: 8px;
                    padding: 20px;
                    height: 100%;
                }

                .testimonial-quote {
                    font-style: italic;
                }

                .testimonial-author {
                    display: flex;
                    align-items: center;
                    margin-top: 12px;
                }

                .testimonial-author img {
                    width: 36px;
                    height: 36px;
                    border-radius: 50%;
                    margin-right: 10px;
                }

                #diTestimonialsRow {
                    scroll-behavior: smooth;
                }

                .testimonial-item {
                    min-width: 280px;
                }

                @keyframes fadeInUp {
                    from {
                        opacity: 0;
                        transform: translate3d(0, 14px, 0);
                    }

                    to {
                        opacity: 1;
                        transform: none;
                    }
                }

                /* Ensure readable text inside cards on gradient section */
                .di-section-gradient .card {
                    background: #ffffff;
                    color: #212529;
                }

                .di-section-gradient .card .card-title,
                .di-section-gradient .card .card-text,
                .di-section-gradient .card ul,
                .di-section-gradient .card .readmore {
                    color: #212529 !important;
                }

                .di-section-gradient .card .text-muted {
                    color: #6c757d !important;
                }

                /* Featured plan styling */
                .plan-featured {
                    border: 2px solid #ffd54f;
                    box-shadow: 0 12px 28px rgba(255, 213, 79, .25);
                    transform: translateY(-4px);
                }

                .plan-featured h4 {
                    color: #102c4f;
                }

                .plan-featured .di-badge {
                    background: #ffd54f;
                    color: #102c4f;
                }

                /* FAQ accordion */
                .faq-accordion {
                    max-width: 900px;
                    margin: 0 auto;
                }

                .faq-card {
                    background: #ffffff;
                    border-radius: 12px;
                    border: 1px solid rgba(16, 44, 79, 0.12);
                    box-shadow: 0 6px 16px rgba(15, 37, 68, 0.05);
                    margin-bottom: 1rem;
                    overflow: hidden;
                }

                .faq-header {
                    width: 100%;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 1rem 1.25rem;
                    background: #ffffff;
                    border: 0;
                    outline: none;
                    cursor: pointer;
                    text-align: left;
                    font-weight: 600;
                    color: var(--di-primary);
                }

                html[dir='rtl'] .faq-header {
                    text-align: right;
                }

                .faq-header:hover {
                    background: #f5f7fb;
                }

                .faq-title {
                    flex: 1;
                }

                .faq-arrow {
                    margin-left: .75rem;
                    transition: transform .2s ease;
                    color: #6c757d;
                    display: flex;
                    align-items: center;
                }

                html[dir='rtl'] .faq-arrow {
                    margin-left: 0;
                    margin-right: .75rem;
                }

                .faq-toggle[aria-expanded="true"] .faq-arrow {
                    transform: rotate(180deg);
                }

                .faq-body {
                    padding: 0 1.25rem 1rem;
                    border-top: 1px solid #e9ecef;
                    background: #ffffff;
                }

                .faq-body p {
                    margin-bottom: 0;
                    color: #6c757d;
                }

                /* Plan buttons styling */
                .plan-card .btn {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    width: 100%;
                    padding: .9rem 1.6rem;
                    font-size: 1rem;
                    font-weight: 600;
                    border-radius: 999px;
                }

                .plan-card .btn.di-btn-outline,
                .plan-card .btn.btn-outline {
                    border-width: 2px;
                    background-color: #ffffff;
                    border-color: var(--di-primary);
                    color: var(--di-primary);
                    box-shadow: 0 8px 18px rgba(15, 37, 68, 0.18);
                }

                .plan-card .btn.di-btn-outline:hover,
                .plan-card .btn.btn-outline:hover {
                    background-color: #f0f4fb;
                    border-color: var(--di-primary);
                    color: var(--di-primary);
                }

                .plan-featured .btn {
                    background-color: var(--di-primary);
                    border-color: var(--di-primary);
                    color: #ffffff;
                    box-shadow: 0 10px 24px rgba(16, 44, 79, 0.4);
                }

                .plan-featured .btn:hover {
                    filter: brightness(1.05);
                    color: #ffffff;
                }

                /* Testimonials controls */
                .di-testimonials-controls {
                    display: flex;
                    justify-content: center;
                    gap: 0.75rem;
                    margin-top: 1rem;
                }

                .di-testimonials-btn {
                    width: 44px;
                    height: 44px;
                    border-radius: 50%;
                    background-color: var(--di-primary);
                    color: #ffffff;
                    border: none;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1.4rem;
                    box-shadow: 0 8px 20px rgba(16, 44, 79, 0.45);
                    cursor: pointer;
                }

                .di-testimonials-btn:hover {
                    filter: brightness(1.05);
                }

                /* Mobile App Section Animations */
                @keyframes float {
                    0%, 100% {
                        transform: translateY(0);
                    }
                    50% {
                        transform: translateY(-20px);
                    }
                }

                @keyframes pulse {
                    0%, 100% {
                        transform: scale(1);
                        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
                    }
                    50% {
                        transform: scale(1.05);
                        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
                    }
                }
            </style>
        @endpush

        <section class="di-bg-primary text-white">
            <div id="diHeroCarousel" class="carousel slide di-hero-carousel" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#diHeroCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#diHeroCarousel" data-slide-to="1"></li>
                    <li data-target="#diHeroCarousel" data-slide-to="2"></li>
                    <li data-target="#diHeroCarousel" data-slide-to="3"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active"
                        style="background-image:url('{{ asset('assets/images/carousel/slide1.png') }}');">
                        <div class="di-hero-overlay"></div>
                        <div class="container py-5 di-hero-content">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="mb-3"><img src="{{ asset('assets/images/logo-w.png') }}" alt="DataInsight"
                                            style="height:48px;"></div>
                                    <h1 class="display-4 mb-3">{{ __('Welcome to') }} <span>DataInsight</span></h1>
                                    <p class="lead mb-4">
                                        {{ __('We are a management consulting firm helping organizations make data-driven decisions.') }}
                                    </p>
                                    <p class="mb-4">
                                        {{ __('Our multi-tenant SaaS helps you manage tenants, users, and operations with isolated databases for each tenant.') }}
                                    </p>
                                    <div class="di-btn-group">
                                        <a href="#plans" class="btn di-btn-primary btn-lg">{{ __('View Plans') }}</a>
                                        <a href="#features"
                                            class="btn di-btn-outline-light btn-lg">{{ __('Learn More') }}</a>
                                        <a href="{{ route('marketplace.offers.index') }}"
                                            class="btn di-btn-outline-light btn-lg">{{ __('العروض المتاحة') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item"
                        style="background-image:url('{{ asset('assets/images/carousel/slide2.png') }}');">
                        <div class="di-hero-overlay"></div>
                        <div class="container py-5 di-hero-content">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h2 class="mb-3">{{ __('Modern Dashboard') }}</h2>
                                    <p class="mb-4">
                                        {{ __('Responsive dashboard with charts, tables, and role-based access.') }}</p>
                                    <a href="#features" class="btn di-btn-outline btn-lg">{{ __('Learn More') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item"
                        style="background-image:url('{{ asset('assets/images/carousel/slide3.png') }}');">
                        <div class="di-hero-overlay"></div>
                        <div class="container py-5 di-hero-content">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h2 class="mb-3">{{ __('Choose Your Plan') }}</h2>
                                    <p class="mb-4">{{ __('All plans include secure multi-tenant architecture.') }}</p>
                                    <a href="#plans" class="btn di-btn-primary btn-lg">{{ __('View Plans') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item"
                        style="background-image:url('{{ asset('assets/images/carousel/slide4.png') }}');">
                        <div class="di-hero-overlay"></div>
                        <div class="container py-5 di-hero-content">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h2 class="mb-3">{{ __('AI Insights for Your Documents') }}</h2>
                                    <p class="mb-4">
                                        @if (app()->getLocale() === 'ar')
                                            حوِّل المستندات غير المنظمة إلى لوحات معلومات غنية بالتحليلات والمؤشرات.
                                        @else
                                            {{ __('Turn unstructured documents into dashboards of actionable insights and metrics.') }}
                                        @endif
                                    </p>
                                    <a href="#plans" class="btn di-btn-primary btn-lg">{{ __('Start Now') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a class="carousel-control-prev" href="#diHeroCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </a>
                <a class="carousel-control-next" href="#diHeroCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </a>
            </div>
        </section>

        <section id="features" class="py-5 di-section-alt">
            <div class="container">
                <div class="row mb-4">
                    <div class="col text-center">
                        <h2 class="section-title">{{ __('Key Benefits') }}</h2>
                        <p class="section-subtitle">{{ __('Built for scalability, security, and speed.') }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-4 fade-in">
                        <div class="card h-100 feature-card">
                            <div class="card-body">
                                <div class="feature-icon"><i class="mdi mdi-database"></i></div>
                                <h5 class="card-title">{{ __('Isolated Databases') }}</h5>
                                <p class="card-text">
                                    {{ __('Each tenant gets its own database for maximum isolation and security.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4 fade-in-delay-1">
                        <div class="card h-100 feature-card">
                            <div class="card-body">
                                <div class="feature-icon"><i class="mdi mdi-rocket"></i></div>
                                <h5 class="card-title">{{ __('Quick Onboarding') }}</h5>
                                <p class="card-text">
                                    {{ __('Invite teams and start fast with streamlined setup and defaults.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4 fade-in-delay-2">
                        <div class="card h-100 feature-card">
                            <div class="card-body">
                                <div class="feature-icon"><i class="mdi mdi-view-dashboard"></i></div>
                                <h5 class="card-title">{{ __('Modern Dashboard') }}</h5>
                                <p class="card-text">
                                    {{ __('Responsive dashboard with charts, tables, and role-based access.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats -->
        <section class="py-5 di-section-contrast">
            <div class="container">
                <div class="row text-center">
                    <div class="col-md-3 mb-4">
                        <div class="counter di-text-primary" data-target="12800">0</div>
                        <div class="muted">{{ __('Visitors') }}</div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="counter di-text-primary" data-target="560">0</div>
                        <div class="muted">{{ __('Customers') }}</div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="counter di-text-primary" data-target="240">0</div>
                        <div class="muted">{{ __('Active Tenants') }}</div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="counter di-text-primary" data-target="99.99">0</div>
                        <div class="muted">{{ __('Uptime %') }}</div>
                    </div>
                </div>
            </div>
        </section>
        <section id="plans" class="py-5 di-section-gradient">
            <div class="container">
                <div class="row mb-4">
                    <div class="col text-center">
                        <h2 class="section-title">{{ __('Choose Your Plan') }}</h2>
                        <p class="section-subtitle" style="color:#e2e6ea;">
                            {{ __('All plans include secure multi-tenant architecture.') }}</p>
                    </div>
                </div>
                <div class="row">
                    @php
                        $locale = app()->getLocale();
                        $plansCollection = $plans ?? collect();
                    @endphp
                    @if ($plansCollection->count())
                        @foreach ($plansCollection as $index => $plan)
                            @php
                                $mainFeatures = $plan->getFeaturesForLocale($locale);
                                $moreFeatures = $plan->getMoreFeaturesForLocale($locale);
                                $collapseId = 'plan-more-' . $plan->id;
                                $isFeatured = $plan->is_featured;
                                $fadeClass = 'fade-in-delay-' . min($index % 4, 3);
                            @endphp
                            <div class="col-md-4 mb-4 {{ $fadeClass }}">
                                <div
                                    class="card h-100 {{ $isFeatured ? 'border-primary plan-card plan-featured' : 'border plan-card' }}">
                                    <div class="card-body d-flex flex-column">
                                        <div class="d-flex align-items-center mb-2">
                                            <h4 class="mb-0 di-me-2">{{ $plan->getNameForLocale($locale) }}</h4>
                                            @if ($isFeatured)
                                                <span class="di-badge di-ms-2">{{ __('Most Popular') }}</span>
                                            @endif
                                        </div>
                                        <p class="text-muted mb-2">{{ $plan->getSubtitleForLocale($locale) }}</p>
                                        <h2 class="mt-3">
                                            {{ $plan->price_monthly > 0 ? $plan->price_monthly : 0 }}
                                            <span class="text-muted">{{ $plan->currency }}/mo</span>
                                        </h2>
                                        @if (count($mainFeatures))
                                            <ul class="list-unstyled mt-3 mb-3">
                                                @foreach ($mainFeatures as $feat)
                                                    <li><i class="mdi mdi-check-circle-outline di-text-primary"></i>
                                                        {{ $feat }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        @if (count($moreFeatures))
                                            <div class="collapse" id="{{ $collapseId }}">
                                                <ul class="list-unstyled mb-3">
                                                    @foreach ($moreFeatures as $feat)
                                                        <li>• {{ $feat }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <span class="readmore" data-toggle="collapse"
                                                data-target="#{{ $collapseId }}">{{ __('Show more') }}</span>
                                        @endif
                                        <a href="{{ route('tenants.signup', ['plan' => $plan->code]) }}"
                                            class="btn {{ $isFeatured ? 'di-btn-primary' : 'di-btn-outline' }} mt-auto">{{ __('Subscribe') }}</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        {{-- Fallback: no plans configured --}}
                        <div class="col-12 text-center text-white-50">
                            {{ __('No plans are configured yet. Please contact the administrator.') }}
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- Solutions (new distinct section) -->
        <section id="solutions" class="py-5 di-section-contrast">
            <div class="container">
                <div class="row mb-4">
                    <div class="col text-center">
                        <h2 class="section-title">{{ __('app.solutions_title') }}</h2>
                        <p class="section-subtitle">{{ __('app.solutions_subtitle') }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-4 fade-in">
                        <div class="card h-100 feature-card solutions-card">
                            <div class="card-body">
                                <div class="feature-icon feature-icon--subtle"><i class="mdi mdi-cogs"></i></div>
                                <h5 class="card-title">{{ __('app.operations_suite') }}</h5>
                                <p class="card-text">{{ __('app.operations_desc') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4 fade-in-delay-1">
                        <div class="card h-100 feature-card solutions-card">
                            <div class="card-body">
                                <div class="feature-icon feature-icon--subtle"><i class="mdi mdi-chart-bar"></i></div>
                                <h5 class="card-title">{{ __('app.analytics_hub') }}</h5>
                                <p class="card-text">{{ __('app.analytics_desc') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4 fade-in-delay-2">
                        <div class="card h-100 feature-card solutions-card">
                            <div class="card-body">
                                <div class="feature-icon feature-icon--subtle"><i class="mdi mdi-shield-lock"></i></div>
                                <h5 class="card-title">{{ __('app.security_center') }}</h5>
                                <p class="card-text">{{ __('app.security_desc') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Partners -->
        <section class="py-5 di-section-alt">
            <div class="container">
                <div class="row mb-4">
                    <div class="col text-center">
                        <h2 class="section-title di-text-primary">{{ __('Trusted by partners') }}</h2>
                        <p class="section-subtitle">{{ __('We collaborate with leading organizations.') }}</p>
                    </div>
                </div>
                <div class="row justify-content-center text-center align-items-center">
                    @php
                        $partners = [
                            'assets/images/brand_icons/oval.jpg',
                            'assets/images/brand_icons/bitmap.jpg',
                            'assets/images/brand_icons/oval-copy.jpg',
                            'assets/images/brand_icons/oval.jpg',
                            'assets/images/brand_icons/bitmap.jpg',
                            'assets/images/brand_icons/oval-copy.jpg',
                            'assets/images/brand_icons/oval.jpg',
                        ];
                    @endphp
                    @foreach ($partners as $p)
                        <div class="col-6 col-md-3 col-lg-2 mb-3 d-flex justify-content-center">
                            <div class="partner-card p-3 border rounded w-100 d-flex justify-content-center">
                                <img src="{{ asset($p) }}" alt="{{ __('Partner Logo') }}">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Testimonials with horizontal slider -->
        <section id="testimonials" class="py-5 di-section-contrast">
            <div class="container">
                <div class="row mb-4">
                    <div class="col-12 text-center">
                        <h2 class="section-title di-text-primary">{{ __('What our customers say') }}</h2>
                        <p class="section-subtitle">{{ __('Real stories from teams using DataInsight') }}</p>
                        <div class="di-testimonials-controls">
                            <button id="diTestimonialsPrev" type="button" class="di-testimonials-btn"
                                aria-label="Previous testimonial"><i class="mdi mdi-chevron-left"></i></button>
                            <button id="diTestimonialsNext" type="button" class="di-testimonials-btn"
                                aria-label="Next testimonial"><i class="mdi mdi-chevron-right"></i></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div id="diTestimonialsRow" class="d-flex flex-nowrap overflow-auto">
                            <div class="testimonial-item pr-3">
                                <div class="testimonial-card h-100">
                                    <p class="testimonial-quote">
                                        “{{ __('DataInsight helped us launch multi-tenant ops in weeks, not months.') }}”
                                    </p>
                                    <div class="testimonial-author">
                                        <img src="{{ asset('assets/images/faces/face10.jpg') }}" alt="">
                                        <div>
                                            <strong>{{ __('Marian Garner') }}</strong><br>
                                            <span class="muted">{{ __('COO, FinTech Co.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="testimonial-item pr-3">
                                <div class="testimonial-card h-100">
                                    <p class="testimonial-quote">
                                        “{{ __('The isolation and security features are best-in-class.') }}”</p>
                                    <div class="testimonial-author">
                                        <img src="{{ asset('assets/images/faces/face12.jpg') }}" alt="">
                                        <div>
                                            <strong>{{ __('David Grey') }}</strong><br>
                                            <span class="muted">{{ __('CTO, HealthTech') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="testimonial-item pr-3">
                                <div class="testimonial-card h-100">
                                    <p class="testimonial-quote">
                                        “{{ __('We scaled to dozens of tenants with zero friction.') }}”</p>
                                    <div class="testimonial-author">
                                        <img src="{{ asset('assets/images/faces/face3.jpg') }}" alt="">
                                        <div>
                                            <strong>{{ __('Travis Jenkins') }}</strong><br>
                                            <span class="muted">{{ __('Head of Ops, Retail') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="testimonial-item pr-3">
                                <div class="testimonial-card h-100">
                                    <p class="testimonial-quote">
                                        “{{ __('We finally have a single place to manage all tenants securely.') }}”</p>
                                    <div class="testimonial-author">
                                        <img src="{{ asset('assets/images/faces/face10.jpg') }}" alt="">
                                        <div>
                                            <strong>{{ __('Sarah Ibrahim') }}</strong><br>
                                            <span class="muted">{{ __('Operations Lead, SaaS Group') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="testimonial-item pr-3">
                                <div class="testimonial-card h-100">
                                    <p class="testimonial-quote">
                                        “{{ __('The analytics and reports changed how our leadership sees the business.') }}”
                                    </p>
                                    <div class="testimonial-author">
                                        <img src="{{ asset('assets/images/faces/face12.jpg') }}" alt="">
                                        <div>
                                            <strong>{{ __('Ahmed Al-Qahtani') }}</strong><br>
                                            <span class="muted">{{ __('Head of BI, Retail Group') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="testimonial-item pr-3">
                                <div class="testimonial-card h-100">
                                    <p class="testimonial-quote">
                                        “{{ __('Multi-tenant security and audit trails are exactly what we needed.') }}”
                                    </p>
                                    <div class="testimonial-author">
                                        <img src="{{ asset('assets/images/faces/face3.jpg') }}" alt="">
                                        <div>
                                            <strong>{{ __('Lama Al-Saud') }}</strong><br>
                                            <span class="muted">{{ __('CISO, Financial Services') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mobile App Download Section -->
        <section class="py-5">
            <div class="container">
                <div class="row align-items-center" style="background: linear-gradient(135deg, #1A5F3F 0%, #2D7A56 100%); border-radius: 30px; padding: 60px 40px; box-shadow: 0 20px 60px rgba(26, 95, 63, 0.3); position: relative; overflow: hidden;">
                    <!-- Decorative Background Elements -->
                    <div style="position: absolute; top: -100px; right: -100px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(212, 175, 55, 0.15) 0%, transparent 70%); border-radius: 50%;"></div>
                    <div style="position: absolute; bottom: -80px; left: -80px; width: 250px; height: 250px; background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%); border-radius: 50%;"></div>
                    
                    <!-- Content Column -->
                    <div class="col-lg-7 mb-4 mb-lg-0" style="position: relative; z-index: 2;">
                        <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 25px;">
                            <div style="background: rgba(212, 175, 55, 0.2); border-radius: 20px; padding: 15px; border: 2px solid rgba(212, 175, 55, 0.4);">
                                <i class="mdi mdi-cellphone-check" style="font-size: 3rem; color: #D4AF37;"></i>
                            </div>
                            <div>
                                <h2 style="color: white; margin: 0; font-size: 2.2rem; font-weight: 800;">
                                    {{ __('حمّل تطبيق سهمي') }}
                                </h2>
                                <p style="color: rgba(255, 255, 255, 0.9); margin: 5px 0 0 0; font-size: 1.1rem;">
                                    {{ __('استثمر في العقار أينما كنت') }}
                                </p>
                            </div>
                        </div>
                        
                        <p style="color: rgba(255, 255, 255, 0.95); font-size: 1.05rem; line-height: 1.8; margin-bottom: 30px;">
                            {{ __('تصفح الفرص الاستثمارية، اشترِ الأسهم العقارية، وتتبع أرباحك من راحة هاتفك. تطبيقنا يوفر لك تجربة سلسة وآمنة للاستثمار في أي وقت وأي مكان.') }}
                        </p>
                        
                        <!-- Features List -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <i class="mdi mdi-shield-check" style="font-size: 1.5rem; color: #D4AF37;"></i>
                                    <span style="color: white; font-size: 0.95rem;">{{ __('آمن ومشفّر بالكامل') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <i class="mdi mdi-clock-fast" style="font-size: 1.5rem; color: #D4AF37;"></i>
                                    <span style="color: white; font-size: 0.95rem;">{{ __('معاملات فورية') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <i class="mdi mdi-bell-ring" style="font-size: 1.5rem; color: #D4AF37;"></i>
                                    <span style="color: white; font-size: 0.95rem;">{{ __('إشعارات فورية للفرص الجديدة') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <i class="mdi mdi-chart-line" style="font-size: 1.5rem; color: #D4AF37;"></i>
                                    <span style="color: white; font-size: 0.95rem;">{{ __('تتبع أرباحك لحظياً') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Download Buttons -->
                        <div style="display: flex; flex-wrap: wrap; gap: 15px;">
                            <a href="#" style="display: inline-flex; align-items: center; gap: 12px; background: white; color: #1A5F3F; padding: 15px 30px; border-radius: 15px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 30px rgba(0, 0, 0, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 20px rgba(0, 0, 0, 0.2)'">
                                <i class="mdi mdi-apple" style="font-size: 2rem;"></i>
                                <div style="text-align: right;">
                                    <small style="display: block; font-size: 0.7rem; opacity: 0.7;">{{ __('حمّل من') }}</small>
                                    <span style="font-size: 1.1rem;">App Store</span>
                                </div>
                            </a>
                            
                            <a href="#" style="display: inline-flex; align-items: center; gap: 12px; background: white; color: #1A5F3F; padding: 15px 30px; border-radius: 15px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 30px rgba(0, 0, 0, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 20px rgba(0, 0, 0, 0.2)'">
                                <i class="mdi mdi-google-play" style="font-size: 2rem;"></i>
                                <div style="text-align: right;">
                                    <small style="display: block; font-size: 0.7rem; opacity: 0.7;">{{ __('حمّل من') }}</small>
                                    <span style="font-size: 1.1rem;">Google Play</span>
                                </div>
                            </a>
                        </div>
                        
                        <!-- Stats -->
                        <div style="display: flex; flex-wrap: wrap; gap: 30px; margin-top: 35px; padding-top: 30px; border-top: 2px solid rgba(255, 255, 255, 0.2);">
                            <div>
                                <h3 style="color: #D4AF37; margin: 0; font-size: 2rem; font-weight: 800;">10K+</h3>
                                <small style="color: rgba(255, 255, 255, 0.8);">{{ __('تحميل للتطبيق') }}</small>
                            </div>
                            <div>
                                <h3 style="color: #D4AF37; margin: 0; font-size: 2rem; font-weight: 800;">4.8</h3>
                                <small style="color: rgba(255, 255, 255, 0.8);">{{ __('تقييم المستخدمين') }}</small>
                            </div>
                            <div>
                                <h3 style="color: #D4AF37; margin: 0; font-size: 2rem; font-weight: 800;">99.9%</h3>
                                <small style="color: rgba(255, 255, 255, 0.8);">{{ __('وقت التشغيل') }}</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Phone Mockup Column -->
                    <div class="col-lg-5" style="position: relative; z-index: 2;">
                        <div style="text-align: center; animation: float 3s ease-in-out infinite;">
                            <div style="position: relative; display: inline-block;">
                                <!-- Phone Frame -->
                                <div style="width: 280px; height: 560px; background: linear-gradient(180deg, #2D7A56 0%, #1A5F3F 100%); border-radius: 40px; padding: 15px; box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4); margin: 0 auto;">
                                    <!-- Screen -->
                                    <div style="width: 100%; height: 100%; background: white; border-radius: 30px; overflow: hidden; position: relative;">
                                        <!-- App Screenshot Mockup -->
                                        <div style="padding: 20px; text-align: center; padding-top: 60px;">
                                            <i class="mdi mdi-home-city" style="font-size: 4rem; color: #1A5F3F; margin-bottom: 15px;"></i>
                                            <h4 style="color: #1A5F3F; margin-bottom: 10px; font-size: 1.3rem;">{{ __('سهمي') }}</h4>
                                            <p style="color: #6c757d; font-size: 0.85rem; margin-bottom: 25px;">{{ __('الاستثمار العقاري الذكي') }}</p>
                                            
                                            <!-- Mockup Property Cards -->
                                            <div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 15px; padding: 15px; margin-bottom: 12px; text-align: right; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);">
                                                <small style="color: #1A5F3F; font-weight: 700;">{{ __('برج الرياض التجاري') }}</small><br>
                                                <small style="color: #D4AF37; font-weight: 600;">{{ __('عائد 9.5%') }}</small>
                                            </div>
                                            
                                            <div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 15px; padding: 15px; margin-bottom: 12px; text-align: right; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);">
                                                <small style="color: #1A5F3F; font-weight: 700;">{{ __('مجمع جدة السكني') }}</small><br>
                                                <small style="color: #D4AF37; font-weight: 600;">{{ __('عائد 8.2%') }}</small>
                                            </div>
                                            
                                            <div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 15px; padding: 15px; text-align: right; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);">
                                                <small style="color: #1A5F3F; font-weight: 700;">{{ __('واحة الدمام') }}</small><br>
                                                <small style="color: #D4AF37; font-weight: 600;">{{ __('عائد 7.8%') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Floating Elements -->
                                <div style="position: absolute; top: 20px; right: -30px; background: white; border-radius: 50%; padding: 12px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); animation: pulse 2s ease-in-out infinite;">
                                    <i class="mdi mdi-chart-line-variant" style="font-size: 1.5rem; color: #1A5F3F;"></i>
                                </div>
                                <div style="position: absolute; bottom: 80px; left: -30px; background: white; border-radius: 50%; padding: 12px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); animation: pulse 2s 1s ease-in-out infinite;">
                                    <i class="mdi mdi-cash-check" style="font-size: 1.5rem; color: #D4AF37;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ moved to a dedicated page: see route('static.faq') -->
    </div>
@endsection

@push('custom-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Simple counter animation
            document.querySelectorAll('.counter').forEach(function(el) {
                var target = parseFloat(el.getAttribute('data-target'));
                var isPercent = String(target).indexOf('.') !== -1;
                var duration = 1200;
                var start = null;

                function step(ts) {
                    if (!start) start = ts;
                    var progress = Math.min((ts - start) / duration, 1);
                    var val = target * progress;
                    el.textContent = isPercent ? val.toFixed(2) : Math.floor(val);
                    if (progress < 1) requestAnimationFrame(step);
                }
                requestAnimationFrame(step);
            });

            // Reveal on scroll
            var io = new IntersectionObserver(function(entries) {
                entries.forEach(function(e) {
                    if (e.isIntersecting) e.target.classList.add('in-view');
                });
            }, {
                threshold: 0.2
            });
            document.querySelectorAll('.feature-card, .plan-card, .partner-card').forEach(function(el) {
                io.observe(el);
            });

            // Minimal carousel logic (vanilla JS)
            var carousel = document.getElementById('diHeroCarousel');
            if (carousel) {
                var items = carousel.querySelectorAll('.carousel-item');
                var indicators = carousel.querySelectorAll('.carousel-indicators li');
                var prev = carousel.querySelector('.carousel-control-prev');
                var next = carousel.querySelector('.carousel-control-next');
                var index = 0;

                function show(i) {
                    items[index].classList.remove('active');
                    indicators[index].classList.remove('active');
                    index = (i + items.length) % items.length;
                    items[index].classList.add('active');
                    indicators[index].classList.add('active');
                }

                indicators.forEach(function(ind, i) {
                    ind.addEventListener('click', function() {
                        show(i);
                    });
                });
                if (prev) prev.addEventListener('click', function(e) {
                    e.preventDefault();
                    show(index - 1);
                });
                if (next) next.addEventListener('click', function(e) {
                    e.preventDefault();
                    show(index + 1);
                });

                setInterval(function() {
                    show(index + 1);
                }, 6000);
            }

            // Navbar toggle
            var toggle = document.querySelector('.di-nav-toggle');
            var links = document.getElementById('diNavLinks');
            if (toggle && links) {
                toggle.addEventListener('click', function() {
                    links.classList.toggle('show');
                });
            }

            // Simple collapse toggles for elements using data-toggle="collapse"
            document.querySelectorAll('[data-toggle="collapse"]').forEach(function(trigger) {
                var targetSel = trigger.getAttribute('data-target');
                var target = document.querySelector(targetSel);
                if (!target) return;
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var willShow = !target.classList.contains('show');

                    // Accordion behavior for FAQ: close siblings when opening a new one
                    var accordion = trigger.closest('.faq-accordion');
                    if (accordion && willShow) {
                        accordion.querySelectorAll('.collapse.show').forEach(function(openEl) {
                            if (openEl !== target) {
                                openEl.classList.remove('show');
                            }
                        });
                        accordion.querySelectorAll('[data-toggle="collapse"][aria-expanded="true"]')
                            .forEach(function(openTrigger) {
                                if (openTrigger !== trigger) {
                                    openTrigger.setAttribute('aria-expanded', 'false');
                                }
                            });
                    }

                    target.classList.toggle('show', willShow);
                    trigger.setAttribute('aria-expanded', willShow ? 'true' : 'false');
                });
            });

            // Testimonials horizontal slider (with arrows + auto-scroll)
            var testimonialsRow = document.getElementById('diTestimonialsRow');
            var testimonialsPrev = document.getElementById('diTestimonialsPrev');
            var testimonialsNext = document.getElementById('diTestimonialsNext');
            if (testimonialsRow && testimonialsPrev && testimonialsNext) {
                var item = testimonialsRow.querySelector('.testimonial-item');
                var step = item ? (item.offsetWidth + 16) : 320;

                function slideTestimonials(direction) {
                    if (direction === 'next') {
                        testimonialsRow.scrollLeft += step;
                    } else {
                        testimonialsRow.scrollLeft -= step;
                    }
                }

                testimonialsPrev.addEventListener('click', function() {
                    slideTestimonials('prev');
                });
                testimonialsNext.addEventListener('click', function() {
                    slideTestimonials('next');
                });

                // Auto-scroll every few seconds to feel like a carousel
                setInterval(function() {
                    slideTestimonials('next');
                }, 7000);
            }
        });
    </script>
@endpush
