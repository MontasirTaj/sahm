@php
    $progressClass = match ($progress) {
        0 => 'bg-secondary',
        0. . 0.4 => 'bg-danger',
        41. . 0.7 => 'bg-warning',
        71. . 0.99 => 'bg-info',
        100 => 'bg-success',
        default => 'bg-secondary',
    };

    $statusText = match ($status ?? '') {
        'draft' => 'مسودة',
        'pending_approval' => 'قيد المراجعة الأولية',
        'approved' => 'تمت الموافقة الأولية',
        'rejected' => 'مرفوض',
        'under_real_estate_review' => 'قيد المراجعة العقارية',
        'real_estate_approved' => 'معتمد ✓',
        'real_estate_rejected' => 'مرفوض عقارياً',
        default => 'غير محدد',
    };

    $statusColor = match ($status ?? '') {
        'draft' => 'secondary',
        'pending_approval' => 'warning',
        'approved' => 'info',
        'rejected' => 'danger',
        'under_real_estate_review' => 'primary',
        'real_estate_approved' => 'success',
        'real_estate_rejected' => 'danger',
        default => 'secondary',
    };
@endphp

<div class="offer-approval-progress {{ $class ?? '' }}">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="badge bg-{{ $statusColor }}">{{ $statusText }}</span>
        <span class="small text-muted">{{ $progress }}%</span>
    </div>
    <div class="progress" style="height: 8px;">
        <div class="progress-bar {{ $progressClass }}" role="progressbar" style="width: {{ $progress }}%"
            aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
        </div>
    </div>

    @if (isset($rejectionNotes) && $rejectionNotes && in_array($status, ['rejected', 'real_estate_rejected']))
        <div class="alert alert-danger mt-2 mb-0 small">
            <strong>سبب الرفض:</strong><br>
            {{ $rejectionNotes }}
        </div>
    @endif
</div>
