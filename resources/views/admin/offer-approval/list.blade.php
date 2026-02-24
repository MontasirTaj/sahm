@extends('layout.master')

@section('title', $title)

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
                                <li class="breadcrumb-item active">{{ $title }}</li>
                            </ol>
                        </nav>
                        <h2 class="fw-bold mb-0">
                            <i class="mdi mdi-format-list-bulleted me-2"></i>
                            {{ $title }}
                        </h2>
                    </div>
                    <div>
                        <a href="{{ route('admin.offer-approval.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-arrow-right me-1"></i>
                            العودة للوحة التحكم
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->segment(4) === 'pending' ? 'active' : '' }}"
                            href="{{ route('admin.offer-approval.list', 'pending') }}">
                            <i class="mdi mdi-clock-outline me-1"></i>
                            قيد المراجعة الأولية
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->segment(4) === 'real-estate-review' ? 'active' : '' }}"
                            href="{{ route('admin.offer-approval.list', 'real-estate-review') }}">
                            <i class="mdi mdi-home-search-outline me-1"></i>
                            قيد المراجعة العقارية
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->segment(4) === 'approved' ? 'active' : '' }}"
                            href="{{ route('admin.offer-approval.list', 'approved') }}">
                            <i class="mdi mdi-check-circle-outline me-1"></i>
                            معتمد
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->segment(4) === 'rejected' ? 'active' : '' }}"
                            href="{{ route('admin.offer-approval.list', 'rejected') }}">
                            <i class="mdi mdi-close-circle-outline me-1"></i>
                            مرفوض
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Offers List -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        العروض ({{ $offers->total() }})
                    </h5>
                </div>
            </div>
            <div class="card-body p-0">
                @if ($offers->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>رقم العرض</th>
                                    <th>العنوان</th>
                                    <th>المشترك</th>
                                    <th>الموقع</th>
                                    <th>القيمة</th>
                                    <th>الحالة</th>
                                    <th>التقدم</th>
                                    <th>التاريخ</th>
                                    <th>الإجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($offers as $offer)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">#{{ $offer->id }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span
                                                    class="fw-semibold">{{ Str::limit($offer->title_ar ?? $offer->title, 40) }}</span>
                                                <small class="text-muted">{{ $offer->property_type ?? 'غير محدد' }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $offer->tenant->name ?? 'غير محدد' }}</td>
                                        <td>
                                            <small>{{ $offer->city }}, السعودية</small>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span
                                                    class="fw-bold">{{ number_format($offer->total_shares * $offer->price_per_share) }}</span>
                                                <small class="text-muted">{{ $offer->currency }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $offer->approval_status_color }}">
                                                {{ $offer->approval_status_text }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 6px; width: 60px;">
                                                    <div class="progress-bar bg-{{ $offer->approval_status_color }}"
                                                        role="progressbar" style="width: {{ $offer->approval_progress }}%">
                                                    </div>
                                                </div>
                                                <span class="text-muted small">{{ $offer->approval_progress }}%</span>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $offer->submitted_at?->format('Y-m-d') ?? $offer->created_at->format('Y-m-d') }}
                                            </small>
                                        </td>
                                        <td>
                                            @if ($offer->needsInitialReview())
                                                <a href="{{ route('admin.offer-approval.show', $offer->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="mdi mdi-eye me-1"></i>
                                                    مراجعة
                                                </a>
                                            @elseif($offer->needsRealEstateReview())
                                                <a href="{{ route('admin.offer-approval.real-estate.show', $offer->id) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="mdi mdi-home-edit me-1"></i>
                                                    مراجعة عقارية
                                                </a>
                                            @else
                                                <a href="{{ route('admin.offer-approval.show', $offer->id) }}"
                                                    class="btn btn-sm btn-outline-secondary">
                                                    <i class="mdi mdi-eye me-1"></i>
                                                    عرض
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-5 text-center text-muted">
                        <i class="mdi mdi-inbox-outline" style="font-size: 64px; opacity: 0.3;"></i>
                        <p class="mb-0 mt-3 fs-5">لا توجد عروض في هذه الفئة</p>
                        <a href="{{ route('admin.offer-approval.dashboard') }}" class="btn btn-outline-primary mt-3">
                            العودة للوحة التحكم
                        </a>
                    </div>
                @endif
            </div>

            @if ($offers->hasPages())
                <div class="card-footer bg-transparent">
                    {{ $offers->links() }}
                </div>
            @endif
        </div>
    </div>

@endsection
