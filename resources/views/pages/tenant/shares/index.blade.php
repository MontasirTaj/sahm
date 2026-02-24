@extends('layout.master')

@section('content')
    @php $subdomain = request()->route('subdomain'); @endphp
    <div class="tenant-page-header">
        <div class="card tenant-page-header-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="tenant-page-header-title">{{ __('عروض الأسهم العقارية') }}</div>
                    <p class="tenant-page-header-subtitle mb-0">{{ __('إدارة وإنشاء عروض الأسهم في منشأتك') }}</p>
                </div>
                <div class="tenant-page-header-actions">
                    <a href="{{ route('tenant.subdomain.shares.create', ['subdomain' => $subdomain]) }}"
                        class="btn btn-primary">{{ __('إنشاء عرض جديد') }}</a>
                </div>
            </div>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif



    <div class="tenant-table-wrapper">
        <div class="table-responsive">
            <table class="table tenant-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('العنوان') }}</th>
                        <th>{{ __('السعر/سهم') }}</th>
                        <th>{{ __('إجمالي الأسهم') }}</th>
                        <th>{{ __('المتاحة') }}</th>
                        <th>{{ __('الحالة') }}</th>
                        <th>{{ __('حالة الموافقة') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($offers as $o)
                        <tr>
                            <td>{{ $o->id }}</td>
                            <td>{{ $o->title }}</td>
                            <td>{{ number_format($o->price_per_share, 2) }} {{ $o->currency }}</td>
                            <td>{{ $o->total_shares }}</td>
                            <td>{{ $o->available_shares }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $o->status === 'active' ? 'success' : ($o->status === 'draft' ? 'secondary' : ($o->status === 'paused' ? 'warning' : ($o->status === 'completed' ? 'info' : 'danger'))) }}">
                                    {{ $o->status_text }}
                                </span>
                            </td>
                            <td style="min-width: 200px;">
                                @if (isset($o->approval_status))
                                    <x-offer-approval-progress :status="$o->approval_status" :progress="$o->approval_progress ?? 0" :rejectionNotes="$o->rejection_notes" />
                                @else
                                    <span class="badge bg-secondary">غير متزامن</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if (!isset($o->approval_status) || $o->approval_status !== 'real_estate_approved')
                                    <a class="btn btn-sm btn-outline-primary tenant-action-btn"
                                        href="{{ route('tenant.subdomain.shares.edit', ['subdomain' => $subdomain, 'share' => $o->id]) }}">{{ __('تعديل') }}</a>
                                @else
                                    <span class="badge bg-success">معتمد نهائياً</span>
                                @endif
                                <form method="POST"
                                    action="{{ route('tenant.subdomain.shares.destroy', ['subdomain' => $subdomain, 'share' => $o->id]) }}"
                                    style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger tenant-action-btn"
                                        onclick="return confirm('{{ __('حذف العرض؟') }}')">{{ __('حذف') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">{{ __('لا توجد عروض بعد') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $offers->links() }}
        </div>
    </div>
@endsection
