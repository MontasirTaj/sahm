@extends('layout.master-mini')

@section('page-title', __('التنبيهات'))

@push('plugin-styles')
    <style>
        .notification-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .notification-card.unread {
            background-color: #f0f9ff;
            border-left-color: #06b6d4;
        }

        .notification-card.highlighted {
            background-color: #fef3c7;
            border-left-color: #f59e0b;
            animation: highlightPulse 2s ease-in-out;
        }

        @keyframes highlightPulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.02);
            }
        }

        .notification-card:hover {
            transform: translateX(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .notification-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .notification-time {
            font-size: 0.875rem;
            color: #64748b;
        }

        .mark-read-btn {
            opacity: 0;
            transition: opacity 0.3s;
        }

        .notification-card:hover .mark-read-btn {
            opacity: 1;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="mdi mdi-bell"></i> {{ __('التنبيهات') }}</h4>
                        @if ($unreadCount > 0)
                            <button type="button" class="btn btn-sm btn-primary" onclick="markAllAsRead()">
                                <i class="mdi mdi-check-all"></i> {{ __('تحديد الكل كمقروء') }}
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if ($notifications->isEmpty())
                            <div class="text-center py-5">
                                <i class="mdi mdi-bell-off-outline" style="font-size: 4rem; color: #cbd5e1;"></i>
                                <p class="text-muted mt-3">{{ __('لا توجد تنبيهات') }}</p>
                            </div>
                        @else
                            <div class="row">
                                @foreach ($notifications as $notification)
                                    <div class="col-12 mb-3" id="notification-{{ $notification->id }}">
                                        <div class="notification-card card {{ $notification->is_read ? '' : 'unread' }}">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <div
                                                            class="notification-icon bg-{{ $notification->color }}-subtle text-{{ $notification->color }}">
                                                            <i class="mdi {{ $notification->icon }}"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <h5 class="mb-1">{{ $notification->title }}</h5>
                                                        <p class="mb-2">{{ $notification->message }}</p>
                                                        <div class="notification-time">
                                                            <i class="mdi mdi-clock-outline"></i>
                                                            {{ $notification->created_at->diffForHumans() }}
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        @if (!$notification->is_read)
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-primary mark-read-btn"
                                                                onclick="markAsRead({{ $notification->id }})">
                                                                <i class="mdi mdi-check"></i> {{ __('تحديد كمقروء') }}
                                                            </button>
                                                        @else
                                                            <span class="badge bg-secondary">{{ __('مقروء') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if ($notification->share_operation_id || $notification->wallet_transaction_id)
                                                    <div class="mt-3 pt-3 border-top">
                                                        <div class="d-flex gap-2">
                                                            @if ($notification->share_operation_id)
                                                                <a href="{{ route('buyer.dashboard') }}"
                                                                    class="btn btn-sm btn-info">
                                                                    <i class="mdi mdi-chart-line"></i>
                                                                    {{ __('عرض العملية') }}
                                                                </a>
                                                            @endif
                                                            @if ($notification->wallet_transaction_id)
                                                                <a href="{{ route('buyer.wallet.index') }}"
                                                                    class="btn btn-sm btn-success">
                                                                    <i class="mdi mdi-wallet"></i> {{ __('عرض المحفظة') }}
                                                                </a>
                                                            @endif
                                                            @if ($notification->sale_offer_id && $notification->saleOffer)
                                                                <a href="{{ route('buyer.secondary-market.index') }}"
                                                                    class="btn btn-sm btn-primary">
                                                                    <i class="mdi mdi-store"></i> {{ __('السوق الثانوي') }}
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4">
                                {{ $notifications->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script>
        function markAsRead(id) {
            fetch(`{{ route('buyer.notifications.read', ':id') }}`.replace(':id', id), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }

        function markAllAsRead() {
            fetch('{{ route('buyer.notifications.read-all') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }

        // Scroll to and highlight notification from URL hash
        document.addEventListener('DOMContentLoaded', function() {
            const hash = window.location.hash;
            if (hash) {
                const notificationElement = document.querySelector(hash);
                if (notificationElement) {
                    // Scroll to notification with offset for navbar
                    setTimeout(() => {
                        const offset = 100;
                        const elementPosition = notificationElement.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - offset;

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });

                        // Add highlight class to the card
                        const card = notificationElement.querySelector('.notification-card');
                        if (card) {
                            card.classList.add('highlighted');

                            // Remove highlight after 3 seconds
                            setTimeout(() => {
                                card.classList.remove('highlighted');
                            }, 3000);
                        }
                    }, 300);
                }
            }
        });
    </script>
@endpush
