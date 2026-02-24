@extends('layout.master')

@section('title', 'لوحة متابعة الموافقات على العروض')

@section('content')

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-1">
                            <i class="mdi mdi-clipboard-check-multiple-outline me-2"></i>
                            لوحة متابعة الموافقات على العروض
                        </h2>
                        <p class="text-muted mb-0">متابعة مراحل مراجعة واعتماد العروض العقارية</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-primary">
                            <i class="mdi mdi-bell-outline me-1"></i>
                            التنبيهات
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <!-- Pending Initial Review -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="avatar-lg rounded-circle bg-warning bg-opacity-25 d-flex align-items-center justify-content-center">
                                    <h2 class="mb-0 fw-bold" style="color: #000;">{{ $stats['pending_initial'] }}</h2>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-0">قيد المراجعة الأولية</h6>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.offer-approval.list', 'pending') }}"
                                class="text-primary text-decoration-none small">
                                عرض الكل <i class="mdi mdi-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Real Estate Review -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="avatar-lg rounded-circle bg-info bg-opacity-25 d-flex align-items-center justify-content-center">
                                    <h2 class="mb-0 fw-bold" style="color: #000;">{{ $stats['pending_real_estate'] }}
                                    </h2>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-0">قيد المراجعة العقارية</h6>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.offer-approval.list', 'real-estate-review') }}"
                                class="text-primary text-decoration-none small">
                                عرض الكل <i class="mdi mdi-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approved -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="avatar-lg rounded-circle bg-success bg-opacity-25 d-flex align-items-center justify-content-center">
                                    <h2 class="mb-0 fw-bold" style="color: #000;">{{ $stats['approved'] }}</h2>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-0">معتمد</h6>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.offer-approval.list', 'approved') }}"
                                class="text-primary text-decoration-none small">
                                عرض الكل <i class="mdi mdi-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rejected -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="avatar-lg rounded-circle bg-danger bg-opacity-25 d-flex align-items-center justify-content-center">
                                    <h2 class="mb-0 fw-bold" style="color: #000;">{{ $stats['rejected'] }}</h2>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-0">مرفوض</h6>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.offer-approval.list', 'rejected') }}"
                                class="text-primary text-decoration-none small">
                                عرض الكل <i class="mdi mdi-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Pending Initial Review -->
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-semibold">
                                <i class="mdi mdi-clock-outline text-warning me-2"></i>
                                عروض بانتظار المراجعة الأولية
                            </h5>
                            <span class="badge bg-warning">{{ $pendingOffers->count() }}</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if ($pendingOffers->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>العرض</th>
                                            <th>المشترك</th>
                                            <th>تاريخ التقديم</th>
                                            <th>الإجراء</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pendingOffers as $offer)
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-semibold">#{{ $offer->id }}</span>
                                                        <small
                                                            class="text-muted">{{ Str::limit($offer->property_type, 30) }}</small>
                                                    </div>
                                                </td>
                                                <td>{{ $offer->tenant->name ?? 'غير محدد' }}</td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $offer->submitted_at?->diffForHumans() ?? 'غير محدد' }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.offer-approval.show', $offer->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="mdi mdi-eye me-1"></i>
                                                        مراجعة
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted">
                                <i class="mdi mdi-check-circle-outline" style="font-size: 48px; opacity: 0.3;"></i>
                                <p class="mb-0 mt-2">لا توجد عروض جديدة للمراجعة</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Pending Real Estate Review -->
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-semibold">
                                <i class="mdi mdi-home-search-outline text-info me-2"></i>
                                عروض بانتظار المراجعة العقارية
                            </h5>
                            <span class="badge bg-info">{{ $realEstateReviewOffers->count() }}</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if ($realEstateReviewOffers->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>العرض</th>
                                            <th>المشترك</th>
                                            <th>نسبة الإنجاز</th>
                                            <th>الإجراء</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($realEstateReviewOffers as $offer)
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-semibold">#{{ $offer->id }}</span>
                                                        <small
                                                            class="text-muted">{{ Str::limit($offer->property_type, 30) }}</small>
                                                    </div>
                                                </td>
                                                <td>{{ $offer->tenant->name ?? 'غير محدد' }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                            <div class="progress-bar bg-info" role="progressbar"
                                                                style="width: {{ $offer->approval_progress }}%"
                                                                aria-valuenow="{{ $offer->approval_progress }}"
                                                                aria-valuemin="0" aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                        <span
                                                            class="text-muted small">{{ $offer->approval_progress }}%</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.offer-approval.real-estate.show', $offer->id) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="mdi mdi-home-edit me-1"></i>
                                                        مراجعة
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted">
                                <i class="mdi mdi-check-circle-outline" style="font-size: 48px; opacity: 0.3;"></i>
                                <p class="mb-0 mt-2">لا توجد عروض للمراجعة العقارية</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Reviews -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="mb-0 fw-semibold">
                            <i class="mdi mdi-history me-2"></i>
                            آخر المراجعات
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if ($recentReviews->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>العرض</th>
                                            <th>نوع المراجعة</th>
                                            <th>القرار</th>
                                            <th>المراجع</th>
                                            <th>التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentReviews as $review)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.offer-approval.show', $review->offer_id) }}"
                                                        class="text-decoration-none">
                                                        #{{ $review->offer_id }}
                                                    </a>
                                                </td>
                                                <td>{{ $review->review_type_text }}</td>
                                                <td>
                                                    @if ($review->decision === 'approved')
                                                        <span class="badge bg-success">
                                                            <i class="mdi mdi-check me-1"></i>
                                                            {{ $review->decision_text }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger">
                                                            <i class="mdi mdi-close me-1"></i>
                                                            {{ $review->decision_text }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{ $review->reviewer->name ?? 'غير محدد' }}</td>
                                                <td>
                                                    <small
                                                        class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted">
                                <i class="mdi mdi-history" style="font-size: 48px; opacity: 0.3;"></i>
                                <p class="mb-0 mt-2">لا توجد مراجعات حتى الآن</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .avatar-sm {
            width: 48px;
            height: 48px;
            min-width: 48px;
            min-height: 48px;
        }

        .avatar-lg {
            width: 80px;
            height: 80px;
            min-width: 80px;
            min-height: 80px;
            max-width: 80px;
            max-height: 80px;
            border-radius: 50% !important;
            aspect-ratio: 1 / 1;
        }

        .avatar-lg h2 {
            font-size: 1.75rem;
            line-height: 1;
        }

        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
    </style>
@endpush
