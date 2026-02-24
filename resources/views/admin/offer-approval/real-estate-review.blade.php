@extends('layout.master')

@section('title', 'المراجعة العقارية #' . $offer->id)

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
                                <li class="breadcrumb-item active">مراجعة عقارية #{{ $offer->id }}</li>
                            </ol>
                        </nav>
                        <h2 class="fw-bold mb-0">
                            <i class="mdi mdi-home-search-outline me-2"></i>
                            المراجعة العقارية للعرض
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
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Progress Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="fw-semibold mb-2">تقدم المراجعة</h5>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-{{ $offer->approval_status_color }}" role="progressbar"
                                        style="width: {{ $offer->approval_progress }}%">
                                        <span class="fw-semibold">{{ $offer->approval_progress }}%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <span class="badge bg-{{ $offer->approval_status_color }} fs-6">
                                    {{ $offer->approval_status_text }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Checkpoints Section -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-semibold">
                                <i class="mdi mdi-clipboard-check-outline me-2"></i>
                                نقاط المراجعة العقارية
                            </h5>
                            <button type="button" class="btn btn-sm btn-primary" id="addCheckpointBtn">
                                <i class="mdi mdi-plus me-1"></i>
                                إضافة نقطة
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.offer-approval.real-estate.checkpoints', $offer->id) }}"
                            method="POST" id="checkpointsForm">
                            @csrf
                            <div id="checkpointsList">
                                @if ($offer->realEstateCheckpoints->isNotEmpty())
                                    @foreach ($offer->realEstateCheckpoints as $index => $checkpoint)
                                        <div class="checkpoint-item mb-3">
                                            <div class="input-group">
                                                <span class="input-group-text">{{ $index + 1 }}</span>
                                                <input type="text" name="checkpoints[]" class="form-control"
                                                    value="{{ $checkpoint->checkpoint_text }}"
                                                    placeholder="اكتب نقطة المراجعة هنا..." required>
                                                <button type="button" class="btn btn-outline-danger remove-checkpoint">
                                                    <i class="mdi mdi-close"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="checkpoint-item mb-3">
                                        <div class="input-group">
                                            <span class="input-group-text">1</span>
                                            <input type="text" name="checkpoints[]" class="form-control"
                                                placeholder="اكتب نقطة المراجعة هنا..." required>
                                            <button type="button" class="btn btn-outline-danger remove-checkpoint">
                                                <i class="mdi mdi-close"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="alert alert-info mb-3">
                                <i class="mdi mdi-information-outline me-2"></i>
                                <strong>ملاحظة:</strong> نقاط المراجعة هي البنود التي تم فحصها والتحقق منها في العقار. سيتم
                                إدراجها في تقرير PDF النهائي.
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save me-1"></i>
                                حفظ نقاط المراجعة
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Offer Quick Info -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="mb-0 fw-semibold">
                            <i class="mdi mdi-information-outline me-2"></i>
                            نبذة عن العرض
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small">العنوان</label>
                                <p class="fw-semibold mb-0">{{ $offer->title_ar ?? $offer->title }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">الموقع</label>
                                <p class="fw-semibold mb-0">{{ $offer->city }}, السعودية</p>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">إجمالي الأسهم</label>
                                <p class="fw-bold mb-0">{{ number_format($offer->total_shares) }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">سعر السهم</label>
                                <p class="fw-bold mb-0">{{ number_format($offer->price_per_share, 2) }}
                                    {{ $offer->currency }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">القيمة الكلية</label>
                                <p class="fw-bold mb-0 text-success">
                                    {{ number_format($offer->total_shares * $offer->price_per_share, 2) }}
                                    {{ $offer->currency }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.offer-approval.show', $offer->id) }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class="mdi mdi-eye me-1"></i>
                                عرض التفاصيل الكاملة
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
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
                @if ($offer->needsRealEstateReview())
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-bottom">
                            <h5 class="mb-0 fw-semibold">
                                <i class="mdi mdi-check-decagram me-2"></i>
                                قرار المراجعة العقارية
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning mb-3">
                                <i class="mdi mdi-alert-outline me-2"></i>
                                <small>تأكد من حفظ نقاط المراجعة قبل الموافقة النهائية</small>
                            </div>

                            <!-- Approve Form -->
                            <form action="{{ route('admin.offer-approval.real-estate.approve', $offer->id) }}"
                                method="POST" class="mb-3" id="approveForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">ملاحظات الموافقة (اختياري)</label>
                                    <textarea name="notes" class="form-control" rows="3" placeholder="أضف أي ملاحظات..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="mdi mdi-check-circle me-1"></i>
                                    الموافقة النهائية ونشر العرض
                                </button>
                            </form>

                            <!-- Reject Form -->
                            <form action="{{ route('admin.offer-approval.real-estate.reject', $offer->id) }}"
                                method="POST" id="rejectForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">أسباب الرفض العقاري <span
                                            class="text-danger">*</span></label>
                                    <textarea name="rejection_notes" class="form-control @error('rejection_notes') is-invalid @enderror" rows="4"
                                        required placeholder="اذكر أسباب الرفض العقاري بالتفصيل (10 أحرف على الأقل)"></textarea>
                                    @error('rejection_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="mdi mdi-close-circle me-1"></i>
                                    رفض العرض عقارياً
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="mdi mdi-information-outline me-2"></i>
                        هذا العرض لا يحتاج مراجعة عقارية حالياً.
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@push('plugin-styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@push('plugin-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('custom-scripts')
    <script>
        $(document).ready(function() {
            let checkpointCounter = {{ $offer->realEstateCheckpoints->count() ?: 1 }};

            // Add checkpoint
            $('#addCheckpointBtn').on('click', function(e) {
                e.preventDefault();

                checkpointCounter++;
                const checkpointHtml = `
                    <div class="checkpoint-item mb-3">
                        <div class="input-group">
                            <span class="input-group-text">${checkpointCounter}</span>
                            <input type="text" 
                                   name="checkpoints[]" 
                                   class="form-control" 
                                   placeholder="اكتب نقطة المراجعة هنا..."
                                   required>
                            <button type="button" class="btn btn-outline-danger remove-checkpoint">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>
                    </div>
                `;
                $('#checkpointsList').append(checkpointHtml);
                updateCheckpointNumbers();
            });

            // Remove checkpoint
            $(document).on('click', '.remove-checkpoint', function() {
                const checkpointItems = $('.checkpoint-item');
                if (checkpointItems.length > 1) {
                    $(this).closest('.checkpoint-item').remove();
                    updateCheckpointNumbers();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'تنبيه',
                        text: 'يجب أن يكون هناك نقطة مراجعة واحدة على الأقل',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#3085d6'
                    });
                }
            });

            // Update checkpoint numbers
            function updateCheckpointNumbers() {
                $('.checkpoint-item').each(function(index) {
                    $(this).find('.input-group-text').text(index + 1);
                });
                checkpointCounter = $('.checkpoint-item').length;
            }

            // Approve form confirmation
            $('#approveForm').on('submit', function(e) {
                e.preventDefault();

                const checkpointsCount = {{ $offer->realEstateCheckpoints->count() }};

                if (checkpointsCount === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'تنبيه',
                        text: 'لم يتم حفظ نقاط المراجعة. هل تريد المتابعة؟',
                        showCancelButton: true,
                        confirmButtonText: 'نعم، متابعة',
                        cancelButtonText: 'إلغاء',
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            showFinalApprovalConfirmation();
                        }
                    });
                } else {
                    showFinalApprovalConfirmation();
                }

                function showFinalApprovalConfirmation() {
                    Swal.fire({
                        icon: 'question',
                        title: 'الموافقة النهائية',
                        text: 'هل أنت متأكد من الموافقة النهائية على هذا العرض؟ سيتم نشره مباشرة في الموقع.',
                        showCancelButton: true,
                        confirmButtonText: 'نعم، موافقة نهائية',
                        cancelButtonText: 'إلغاء',
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#approveForm')[0].submit();
                        }
                    });
                }
            });

            // Reject form validation
            $('#rejectForm').on('submit', function(e) {
                e.preventDefault();

                const notes = $('textarea[name="rejection_notes"]').val();

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
                    title: 'رفض العرض عقارياً',
                    text: 'هل أنت متأكد من رفض هذا العرض عقارياً؟',
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
