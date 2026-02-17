@extends('layout.master-mini')

@section('page-title', __('المحفظة المالية'))

@push('plugin-styles')
    <style>
        .wallet-card {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            border-radius: 15px;
            padding: 30px;
            color: white;
            box-shadow: 0 10px 30px rgba(6, 182, 212, 0.3);
            margin-bottom: 30px;
        }

        .wallet-balance {
            font-size: 3rem;
            font-weight: bold;
            margin: 20px 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .stat-box {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .stat-label {
            color: #64748b;
            font-size: 0.875rem;
            margin-bottom: 5px;
        }

        .stat-value {
            color: #1e293b;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .transaction-item {
            border-bottom: 1px solid #e2e8f0;
            padding: 15px 0;
        }

        .transaction-item:last-child {
            border-bottom: none;
        }

        .transaction-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        /* DataTable Row Colors - ألوان هادئة للصفوف */
        .transaction-row-deposit {
            background-color: #ecfdf5 !important;
            /* أخضر فاتح للإيداعات */
        }

        .transaction-row-withdrawal {
            background-color: #fef2f2 !important;
            /* أحمر فاتح للسحوبات */
        }

        .transaction-row-purchase {
            background-color: #fef9ec !important;
            /* برتقالي فاتح للمشتريات */
        }

        .transaction-row-sale {
            background-color: #eff6ff !important;
            /* أزرق فاتح للمبيعات */
        }

        table.dataTable tbody tr:hover {
            filter: brightness(0.95);
        }
    </style>
@endpush

@push('plugin-styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Wallet Card -->
        <div class="wallet-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h4 class="mb-2"><i class="mdi mdi-wallet"></i> {{ __('محفظتي') }}</h4>
                    <p class="mb-0" style="opacity: 0.9;">{{ $buyer->name }}</p>
                </div>
                <div class="text-end">
                    <small style="opacity: 0.8;">{{ __('الرصيد المتاح') }}</small>
                </div>
            </div>
            <div class="wallet-balance">
                {{ number_format($stats['available_balance'], 2) }} <small
                    style="font-size: 1.5rem;">{{ __('ريال') }}</small>
            </div>
            @if ($stats['pending_balance'] > 0)
                <p class="mb-0" style="opacity: 0.9;">
                    <i class="mdi mdi-clock-outline"></i> {{ __('رصيد معلق') }}:
                    {{ number_format($stats['pending_balance'], 2) }} {{ __('ريال') }}
                </p>
            @endif
            <div class="mt-4">
                <button class="btn btn-light me-2" data-toggle="modal" data-target="#depositModal">
                    <i class="mdi mdi-plus-circle"></i> {{ __('إيداع') }}
                </button>
                <button class="btn btn-outline-light" data-toggle="modal" data-target="#withdrawModal">
                    <i class="mdi mdi-bank-transfer-out"></i> {{ __('سحب') }}
                </button>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-label">{{ __('إجمالي الإيداعات') }}</div>
                    <div class="stat-value text-success">
                        <i class="mdi mdi-arrow-down-circle"></i>
                        {{ number_format($stats['total_deposits'], 2) }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-label">{{ __('إجمالي السحوبات') }}</div>
                    <div class="stat-value text-danger">
                        <i class="mdi mdi-arrow-up-circle"></i>
                        {{ number_format($stats['total_withdrawals'], 2) }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-label">{{ __('إجمالي المعاملات') }}</div>
                    <div class="stat-value text-primary">
                        <i class="mdi mdi-swap-horizontal"></i>
                        {{ $stats['total_transactions'] }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-label">{{ __('الرصيد الكلي') }}</div>
                    <div class="stat-value" style="color: #06b6d4;">
                        <i class="mdi mdi-wallet"></i>
                        {{ number_format($stats['current_balance'], 2) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions by Type -->
        @if ($transactionsByType->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="mdi mdi-chart-pie"></i> {{ __('المعاملات حسب النوع') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($transactionsByType as $type => $data)
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="p-3 rounded" style="background: #f8fafc; border-left: 4px solid #06b6d4;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted">{{ __($type) }}</small>
                                            <h6 class="mb-0">{{ $data->count }} {{ __('عملية') }}</h6>
                                        </div>
                                        <div class="text-end">
                                            <small class="fw-bold">{{ number_format($data->total, 2) }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Recent Transactions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="mdi mdi-history"></i> {{ __('سجل المعاملات') }}</h5>
            </div>
            <div class="card-body">
                @if ($recentTransactions->isEmpty())
                    <div class="text-center py-5">
                        <i class="mdi mdi-receipt-text-outline" style="font-size: 4rem; color: #cbd5e1;"></i>
                        <p class="text-muted mt-3">{{ __('لا توجد معاملات بعد') }}</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table id="transactions-table" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>{{ __('النوع') }}</th>
                                    <th>{{ __('الوصف') }}</th>
                                    <th>{{ __('المبلغ') }}</th>
                                    <th>{{ __('الرصيد قبل') }}</th>
                                    <th>{{ __('الرصيد بعد') }}</th>
                                    <th>{{ __('التاريخ') }}</th>
                                    <th>{{ __('الحالة') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentTransactions as $transaction)
                                    <tr data-transaction-type="{{ $transaction->type }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="transaction-icon bg-{{ $transaction->color }}-subtle text-{{ $transaction->color }} me-2">
                                                    <i class="mdi {{ $transaction->icon }}"></i>
                                                </div>
                                                <span class="fw-bold">{{ $transaction->type_name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <small>{{ $transaction->description }}</small>
                                        </td>
                                        <td>
                                            <span
                                                class="fw-bold text-{{ in_array($transaction->type, ['deposit', 'sale']) ? 'success' : 'danger' }}">
                                                {{ in_array($transaction->type, ['deposit', 'sale']) ? '+' : '-' }}
                                                {{ number_format($transaction->amount, 2) }}
                                            </span>
                                            <small class="text-muted">{{ $transaction->currency }}</small>
                                        </td>
                                        <td>
                                            <small
                                                class="text-muted">{{ number_format($transaction->balance_before, 2) }}</small>
                                        </td>
                                        <td>
                                            <small
                                                class="text-muted">{{ number_format($transaction->balance_after, 2) }}</small>
                                        </td>
                                        <td>
                                            <small>{{ $transaction->created_at->format('Y-m-d') }}</small><br>
                                            <small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            @if ($transaction->status === 'completed')
                                                <span class="badge bg-success">{{ __('مكتملة') }}</span>
                                            @elseif($transaction->status === 'pending')
                                                <span class="badge bg-warning">{{ __('معلقة') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __($transaction->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Deposit Modal -->
    <div class="modal fade" id="depositModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #06b6d4; color: white;">
                    <h5 class="modal-title"><i class="mdi mdi-plus-circle"></i> {{ __('إيداع في المحفظة') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('buyer.wallet.deposit') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">{{ __('المبلغ') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="amount" step="0.01" min="1"
                                    placeholder="0.00" required>
                                <span class="input-group-text">{{ __('ريال') }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('طريقة الدفع') }} <span class="text-danger">*</span></label>
                            <select class="form-select" name="payment_method" required>
                                <option value="bank_transfer">{{ __('تحويل بنكي') }}</option>
                                <option value="credit_card" disabled>{{ __('بطاقة ائتمانية') }} ({{ __('قريباً') }})
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('رقم المرجع') }} <span
                                    class="text-muted">({{ __('اختياري') }})</span></label>
                            <input type="text" class="form-control" name="reference"
                                placeholder="{{ __('رقم مرجع العملية البنكية') }}" maxlength="255">
                            <small class="text-muted">{{ __('أدخل رقم العملية البنكية إن وجد') }}</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('إلغاء') }}</button>
                        <button type="submit" class="btn btn-primary"
                            style="background: #06b6d4; border-color: #06b6d4;">{{ __('إيداع') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Withdraw Modal -->
    <div class="modal fade" id="withdrawModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #dc3545; color: white;">
                    <h5 class="modal-title"><i class="mdi mdi-bank-transfer-out"></i> {{ __('سحب من المحفظة') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('buyer.wallet.withdraw') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <strong>{{ __('الرصيد المتاح للسحب') }}:</strong>
                            {{ number_format($stats['available_balance'], 2) }} {{ __('ريال') }}
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('المبلغ') }}</label>
                            <input type="number" class="form-control" name="amount" step="0.01" min="1"
                                max="{{ $stats['available_balance'] }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('اسم البنك') }}</label>
                            <input type="text" class="form-control" name="bank_account" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('رقم الآيبان') }}</label>
                            <input type="text" class="form-control" name="iban" placeholder="SA..." required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('إلغاء') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('تقديم طلب السحب') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
@endpush

@push('custom-scripts')
    <script>
        $(function() {
            $('#transactions-table').DataTable({
                pageLength: 15,
                lengthMenu: [
                    [10, 15, 25, 50, 100, -1],
                    [10, 15, 25, 50, 100, '{{ app()->getLocale() === 'ar' ? 'الكل' : 'All' }}']
                ],
                order: [
                    [5, 'desc']
                ], // ترتيب حسب التاريخ (تنازلي)
                ordering: true,
                scrollX: true,
                language: {
                    url: '{{ app()->getLocale() === 'ar' ? 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/ar.json' : 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/en-GB.json' }}'
                },
                // تلوين الصفوف حسب نوع العملية
                rowCallback: function(row, data, index) {
                    var transactionType = $(row).attr('data-transaction-type');
                    $(row).addClass('transaction-row-' + transactionType);
                }
            });
        });
    </script>
@endpush
