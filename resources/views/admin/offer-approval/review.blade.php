@extends('layout.master')

@section('title', 'مراجعة العرض #' . $offer->id)

@section('content')

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-2">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.offer-approval.dashboard') }}">لوحة الموافقات</a>
                                </li>
                                <li class="breadcrumb-item active">مراجعة عرض #{{ $offer->id }}</li>
                            </ol>
                        </nav>
                        <h2 class="fw-bold mb-0">
                            <i class="mdi mdi-clipboard-text-outline me-2"></i>
                            مراجعة العرض الأولية
                        </h2>
                    </div>
                    <div>
                        <a href="{{ route('admin.offer-approval.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-arrow-right me-1"></i>
                            العودة
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Offer Details -->
            <div class="col-lg-8">
                <!-- Status Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-semibold mb-2">حالة العرض</h5>
                                <span class="badge bg-{{ $offer->approval_status_color }} fs-6">
                                    {{ $offer->approval_status_text }}
                                </span>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block">نسبة الإنجاز</small>
                                <h3 class="mb-0 fw-bold text-primary">{{ $offer->approval_progress }}%</h3>
                            </div>
                        </div>

                        @if ($offer->approval_progress > 0)
                            <div class="progress mt-3" style="height: 8px;">
                                <div class="progress-bar bg-{{ $offer->approval_status_color }}" role="progressbar"
                                    style="width: {{ $offer->approval_progress }}%">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Offer Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="mb-0 fw-semibold">
                            <i class="mdi mdi-information-outline me-2"></i>
                            معلومات العرض
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">العنوان (عربي)</label>
                                <p class="fw-semibold mb-0">{{ $offer->title_ar ?? 'غير محدد' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">العنوان (إنجليزي)</label>
                                <p class="fw-semibold mb-0">{{ $offer->title ?? 'غير محدد' }}</p>
                            </div>
                            <div class="col-12">
                                <label class="text-muted small mb-1">الوصف (عربي)</label>
                                <p class="mb-0">{{ $offer->description_ar ?? 'غير محدد' }}</p>
                            </div>
                            <div class="col-12">
                                <label class="text-muted small mb-1">الوصف (إنجليزي)</label>
                                <p class="mb-0">{{ $offer->description ?? 'غير محدد' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="mb-0 fw-semibold">
                            <i class="mdi mdi-map-marker-outline me-2"></i>
                            الموقع
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="text-muted small mb-1">الدولة</label>
                                <p class="fw-semibold mb-0">السعودية</p>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small mb-1">المدينة</label>
                                <p class="fw-semibold mb-0">{{ $offer->city ?? 'غير محدد' }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small mb-1">نوع العقار</label>
                                <p class="fw-semibold mb-0">{{ $offer->property_type ?? 'غير محدد' }}</p>
                            </div>
                            @if ($offer->address)
                                <div class="col-12">
                                    <label class="text-muted small mb-1">العنوان</label>
                                    <p class="mb-0">{{ $offer->address }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Shares & Pricing -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="mb-0 fw-semibold">
                            <i class="mdi mdi-currency-usd me-2"></i>
                            الأسهم والأسعار
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="text-muted small mb-1">إجمالي الأسهم</label>
                                <p class="fw-bold mb-0 fs-5">{{ number_format($offer->total_shares) }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small mb-1">الأسهم المتاحة</label>
                                <p class="fw-bold mb-0 fs-5 text-success">{{ number_format($offer->available_shares) }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small mb-1">الأسهم المباعة</label>
                                <p class="fw-bold mb-0 fs-5 text-primary">{{ number_format($offer->sold_shares) }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">سعر السهم</label>
                                <p class="fw-bold mb-0 fs-4 text-primary">
                                    {{ number_format($offer->price_per_share, 2) }} {{ $offer->currency }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">القيمة الإجمالية</label>
                                <p class="fw-bold mb-0 fs-4 text-success">
                                    {{ number_format($offer->total_shares * $offer->price_per_share, 2) }}
                                    {{ $offer->currency }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media -->
                @if ($offer->media && count($offer->media) > 0)
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-bottom">
                            <h5 class="mb-0 fw-semibold">
                                <i class="mdi mdi-image-multiple-outline me-2"></i>
                                صور العرض ({{ count($offer->media) }})
                            </h5>
                        </div>
                        <div class="card-body">
                            <div id="offerImagesCarousel" class="carousel slide" data-ride="carousel" data-interval="5000">
                                <!-- Indicators -->
                                <ol class="carousel-indicators">
                                    @foreach ($offer->media as $index => $image)
                                        <li data-target="#offerImagesCarousel" data-slide-to="{{ $index }}"
                                            class="{{ $index === 0 ? 'active' : '' }}"></li>
                                    @endforeach
                                </ol>

                                <!-- Slides -->
                                <div class="carousel-inner rounded" style="height: 500px; background-color: #f8f9fa;">
                                    @foreach ($offer->media as $index => $image)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}"
                                            style="height: 500px;">
                                            <img src="{{ asset('storage/' . $image) }}" class="d-block w-100 h-100"
                                                alt="الصورة {{ $index + 1 }}" style="object-fit: contain;">
                                            <div
                                                class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded px-3 py-2">
                                                <p class="mb-0">
                                                    @if ($index === 0 && $offer->cover_image === $image)
                                                        <i class="mdi mdi-star text-warning"></i> صورة الغلاف
                                                    @else
                                                        الصورة {{ $index + 1 }} من {{ count($offer->media) }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Controls -->
                                <a class="carousel-control-prev" href="#offerImagesCarousel" role="button"
                                    data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">السابق</span>
                                </a>
                                <a class="carousel-control-next" href="#offerImagesCarousel" role="button"
                                    data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">التالي</span>
                                </a>
                            </div>

                            <!-- Thumbnails -->
                            <div class="row g-2 mt-3">
                                @foreach ($offer->media as $index => $image)
                                    <div class="col-2">
                                        <img src="{{ asset('storage/' . $image) }}"
                                            class="img-thumbnail cursor-pointer carousel-thumb"
                                            style="height: 60px; width: 100%; object-fit: cover;"
                                            data-target="#offerImagesCarousel" data-slide-to="{{ $index }}"
                                            alt="صورة مصغرة {{ $index + 1 }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Tenant Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="mb-0 fw-semibold">
                            <i class="mdi mdi-domain me-2"></i>
                            معلومات المشترك
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <span class="text-muted">الاسم:</span>
                            <span class="fw-semibold">{{ $offer->tenant->name ?? 'غير محدد' }}</span>
                        </p>
                        <p class="mb-2">
                            <span class="text-muted">النطاق:</span>
                            <span class="fw-semibold">{{ $offer->tenant->domain ?? 'غير محدد' }}</span>
                        </p>
                        <p class="mb-0">
                            <span class="text-muted">تاريخ التقديم:</span>
                            <span
                                class="fw-semibold">{{ $offer->submitted_at?->format('Y-m-d H:i') ?? 'غير محدد' }}</span>
                        </p>
                    </div>
                </div>

                <!-- Review History -->
                @if ($offer->reviews->isNotEmpty())
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-transparent border-bottom">
                            <h5 class="mb-0 fw-semibold">
                                <i class="mdi mdi-history me-2"></i>
                                سجل المراجعات
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach ($offer->reviews as $review)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span
                                                class="badge bg-{{ $review->decision === 'approved' ? 'success' : 'danger' }}">
                                                {{ $review->decision_text }}
                                            </span>
                                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-1 small">
                                            <strong>{{ $review->review_type_text }}</strong>
                                        </p>
                                        <p class="mb-1 small text-muted">
                                            بواسطة: {{ $review->reviewer->name ?? 'غير محدد' }}
                                        </p>
                                        @if ($review->notes)
                                            <p class="mb-0 small">
                                                <em>{{ $review->notes }}</em>
                                            </p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                @if ($offer->needsInitialReview())
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-bottom">
                            <h5 class="mb-0 fw-semibold">
                                <i class="mdi mdi-check-decagram me-2"></i>
                                إجراءات المراجعة
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Approve Form -->
                            <form action="{{ route('admin.offer-approval.approve', $offer->id) }}" method="POST"
                                class="mb-3">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">ملاحظات الموافقة (اختياري)</label>
                                    <textarea name="notes" class="form-control" rows="3" placeholder="أضف أي ملاحظات..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="mdi mdi-check-circle me-1"></i>
                                    الموافقة على العرض
                                </button>
                            </form>

                            <!-- Reject Form -->
                            <form action="{{ route('admin.offer-approval.reject', $offer->id) }}" method="POST"
                                id="rejectForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">أسباب الرفض <span class="text-danger">*</span></label>
                                    <textarea name="rejection_notes" class="form-control @error('rejection_notes') is-invalid @enderror" rows="4"
                                        required placeholder="اذكر أسباب الرفض بالتفصيل (10 أحرف على الأقل)"></textarea>
                                    @error('rejection_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="mdi mdi-close-circle me-1"></i>
                                    رفض العرض
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="mdi mdi-information-outline me-2"></i>
                        هذا العرض لا يحتاج مراجعة أولية حالياً.
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .cursor-pointer {
            cursor: pointer;
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 8%;
            opacity: 0.7;
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            opacity: 1;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            width: 40px;
            height: 40px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 50%;
        }

        .carousel-indicators li {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
        }

        .carousel-indicators .active {
            background-color: #fff;
        }

        .carousel-thumb {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .carousel-thumb:hover {
            opacity: 0.7;
            transform: scale(1.05);
            border-color: #0d6efd !important;
        }

        .carousel-thumb.active {
            border-color: #0d6efd !important;
            border-width: 3px !important;
            box-shadow: 0 0 10px rgba(13, 110, 253, 0.5);
        }

        .carousel-caption {
            bottom: 10px;
            padding: 5px 15px;
        }
    </style>
@endpush

@push('plugin-styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@push('plugin-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize carousel
            $('#offerImagesCarousel').carousel({
                interval: 5000,
                wrap: true,
                keyboard: true,
                pause: 'hover'
            });

            // Handle thumbnail clicks
            $('.carousel-thumb').on('click', function() {
                var slideIndex = parseInt($(this).data('slide-to'));
                $('#offerImagesCarousel').carousel(slideIndex);
            });

            // Highlight active thumbnail
            $('#offerImagesCarousel').on('slid.bs.carousel', function(e) {
                var activeIndex = $(this).find('.carousel-item.active').index();
                $('.carousel-thumb').removeClass('active border-primary');
                $('.carousel-thumb[data-slide-to="' + activeIndex + '"]').addClass('active border-primary');
            });

            // Set initial active thumbnail
            $('.carousel-thumb[data-slide-to="0"]').addClass('active border-primary');

            // Handle reject form validation
            $('#rejectForm').on('submit', function(e) {
                e.preventDefault();

                var notes = $('textarea[name="rejection_notes"]').val();
                if (notes.length < 10) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'يجب أن تكون أسباب الرفض 10 أحرف على الأقل',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#dc3545'
                    });
                    return false;
                }

                Swal.fire({
                    icon: 'warning',
                    title: 'رفض العرض',
                    text: 'هل أنت متأكد من رفض هذا العرض؟',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، رفض العرض',
                    cancelButtonText: 'إلغاء',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#rejectForm')[0].submit();
                    }
                });
            });
        });
    </script>
@endpush
