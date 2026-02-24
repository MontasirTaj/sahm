@extends('layout.master')

@section('title', 'التنبيهات')

@section('content')

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-1">
                            <i class="mdi mdi-bell-outline me-2"></i>
                            التنبيهات
                        </h2>
                        <p class="text-muted mb-0">جميع التنبيهات والإشعارات</p>
                    </div>
                    <div>
                        @if ($notifications->whereNull('read_at')->count() > 0)
                            <form action="{{ route('admin.notifications.read-all') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-check-all me-1"></i>
                                    تحديد الكل كمقروء
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        @if ($notifications->isNotEmpty())
                            <div class="list-group list-group-flush">
                                @foreach ($notifications as $notification)
                                    <div class="list-group-item {{ $notification->is_read ? '' : 'bg-light' }}">
                                        <div class="d-flex w-100 justify-content-between align-items-start">
                                            <div class="d-flex align-items-start flex-grow-1">
                                                <div class="me-3">
                                                    <div
                                                        class="avatar-sm rounded-circle bg-{{ $notification->color }} bg-opacity-25 d-flex align-items-center justify-content-center">
                                                        <i class="mdi {{ $notification->icon }} text-{{ $notification->color }}"
                                                            style="font-size: 24px;"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 fw-semibold">{{ $notification->title }}</h6>
                                                    <p class="mb-2">{{ $notification->message }}</p>
                                                    <div class="d-flex gap-3 align-items-center">
                                                        <small class="text-muted">
                                                            <i class="mdi mdi-clock-outline me-1"></i>
                                                            {{ $notification->created_at->diffForHumans() }}
                                                        </small>
                                                        @if ($notification->tenant)
                                                            <small class="text-muted">
                                                                <i class="mdi mdi-domain me-1"></i>
                                                                {{ $notification->tenant->name ?? 'غير محدد' }}
                                                            </small>
                                                        @endif
                                                        @if (!$notification->is_read)
                                                            <span class="badge bg-primary">جديد</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 ms-3">
                                                @if ($notification->offer_id)
                                                    <a href="{{ route('admin.offer-approval.show', $notification->offer_id) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="mdi mdi-eye me-1"></i>
                                                        عرض
                                                    </a>
                                                @endif
                                                @if (!$notification->is_read)
                                                    <form
                                                        action="{{ route('admin.notifications.read', $notification->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary"
                                                            title="تحديد كمقروء">
                                                            <i class="mdi mdi-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form
                                                    action="{{ route('admin.notifications.destroy', $notification->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('هل أنت متأكد من حذف هذا التنبيه؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="حذف">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-5 text-center text-muted">
                                <i class="mdi mdi-bell-off-outline" style="font-size: 64px; opacity: 0.3;"></i>
                                <p class="mb-0 mt-3 fs-5">لا توجد تنبيهات</p>
                            </div>
                        @endif
                    </div>

                    @if ($notifications->hasPages())
                        <div class="card-footer bg-transparent">
                            {{ $notifications->links() }}
                        </div>
                    @endif
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
        }
    </style>
@endpush
