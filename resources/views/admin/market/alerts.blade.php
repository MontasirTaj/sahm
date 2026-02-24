@extends('layout.master')

@push('plugin-styles')
    <style>
        .pagination-wrapper .pagination {
            margin-bottom: 0;
            font-size: 0.875rem;
        }

        .pagination-wrapper .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.8125rem;
            line-height: 1.5;
        }

        .pagination-wrapper .page-item:first-child .page-link,
        .pagination-wrapper .page-item:last-child .page-link {
            border-radius: 0.25rem;
        }

        .pagination-wrapper svg {
            width: 14px !important;
            height: 14px !important;
        }

        /* تنسيق التنبيهات المقروءة */
        tr.table-secondary.opacity-75 {
            opacity: 0.65;
            background-color: #f8f9fa !important;
        }

        tr.table-secondary.opacity-75 td {
            color: #6c757d;
        }
    </style>
@endpush

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold mb-1">
                                <i class="mdi mdi-bell-alert-outline me-2"></i>
                                {{ __('تنبيهات السوق') }}
                            </h2>
                            <p class="text-muted mb-0">متابعة جميع التنبيهات والإشعارات في النظام</p>
                        </div>
                        <div>
                            @php
                                $unreadCount = $alerts->where('is_read', false)->count();
                            @endphp
                            <span class="badge bg-primary fs-6">{{ $alerts->total() }} تنبيه</span>
                            @if ($unreadCount > 0)
                                <span class="badge bg-danger fs-6 ms-2">{{ $unreadCount }} جديد</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th style="width: 100px;">{{ __('الحالة') }}</th>
                            <th style="width: 120px;">{{ __('النوع') }}</th>
                            <th style="width: 150px;">{{ __('المشترك') }}</th>
                            <th>{{ __('العنوان') }}</th>
                            <th>{{ __('الرسالة') }}</th>
                            <th style="width: 180px;">{{ __('التاريخ') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($alerts as $a)
                            <tr class="{{ $a->is_read ? 'table-secondary opacity-75' : '' }}">
                                <td class="fw-semibold">{{ $a->id }}</td>
                                <td>
                                    @if ($a->is_read)
                                        <span class="badge bg-secondary">
                                            <i class="mdi mdi-email-open-outline me-1"></i>
                                            مقروء
                                        </span>
                                    @else
                                        <span class="badge bg-primary">
                                            <i class="mdi mdi-email-outline me-1"></i>
                                            جديد
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $typeConfig = [
                                            'warning' => ['color' => 'warning', 'icon' => 'alert-outline'],
                                            'info' => ['color' => 'info', 'icon' => 'information-outline'],
                                            'success' => ['color' => 'success', 'icon' => 'check-circle-outline'],
                                            'error' => ['color' => 'danger', 'icon' => 'close-circle-outline'],
                                            'critical' => ['color' => 'danger', 'icon' => 'alert-circle-outline'],
                                        ];
                                        $config = $typeConfig[$a->type] ?? [
                                            'color' => 'secondary',
                                            'icon' => 'bell-outline',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $config['color'] }}">
                                        <i class="mdi mdi-{{ $config['icon'] }} me-1"></i>
                                        {{ $a->type }}
                                    </span>
                                </td>
                                <td>
                                    @if ($a->tenant_id)
                                        <span class="badge bg-light text-dark">
                                            <i class="mdi mdi-domain me-1"></i>
                                            {{ $a->tenant_id }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $a->title }}</strong>
                                </td>
                                <td>
                                    <span class="text-muted">{{ Str::limit($a->message, 100) }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark">
                                            <i class="mdi mdi-calendar-outline me-1"></i>
                                            {{ $a->created_at->format('Y-m-d') }}
                                        </span>
                                        <small class="text-muted">
                                            <i class="mdi mdi-clock-outline me-1"></i>
                                            {{ $a->created_at->format('H:i:s') }}
                                        </small>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="mdi mdi-bell-off-outline display-3 text-muted mb-3 d-block"></i>
                                    <p class="text-muted mb-0">لا توجد تنبيهات حالياً</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($alerts->hasPages())
                <div class="mt-3 d-flex justify-content-center">
                    <div class="pagination-wrapper">
                        {{ $alerts->onEachSide(1)->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
