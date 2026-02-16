@extends('layout.master')

@section('content')
    <h4 class="mb-3">{{ __('عمليات الشراء/البيع (كافة المشتركين)') }}</h4>
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('المشترك') }}</th>
                        <th>{{ __('العرض') }}</th>
                        <th>{{ __('النوع') }}</th>
                        <th>{{ __('الأسهم') }}</th>
                        <th>{{ __('السعر/سهم') }}</th>
                        <th>{{ __('الإجمالي') }}</th>
                        <th>{{ __('الحالة') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ops as $op)
                        <tr>
                            <td>{{ $op->id }}</td>
                            <td>{{ $op->tenant_id }}</td>
                            <td>{{ $op->offer_id }}</td>
                            <td>{{ $op->type }}</td>
                            <td>{{ $op->shares_count }}</td>
                            <td>{{ number_format($op->price_per_share, 2) }} {{ $op->currency }}</td>
                            <td>{{ number_format($op->amount_total, 2) }} {{ $op->currency }}</td>
                            <td>{{ $op->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $ops->links() }}
        </div>
    </div>
@endsection
