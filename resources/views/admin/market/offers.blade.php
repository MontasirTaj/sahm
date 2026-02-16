@extends('layout.master')

@section('content')
    <h4 class="mb-3">{{ __('عروض الأسهم (كافة المشتركين)') }}</h4>
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('المشترك') }}</th>
                        <th>{{ __('العنوان') }}</th>
                        <th>{{ __('السعر/سهم') }}</th>
                        <th>{{ __('المتاحة/الإجمالي') }}</th>
                        <th>{{ __('الحالة') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($offers as $o)
                        <tr>
                            <td>{{ $o->id }}</td>
                            <td>{{ $o->tenant_id }}</td>
                            <td>{{ $o->title }}</td>
                            <td>{{ number_format($o->price_per_share, 2) }} {{ $o->currency }}</td>
                            <td>{{ $o->available_shares }} / {{ $o->total_shares }}</td>
                            <td>{{ $o->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $offers->links() }}
        </div>
    </div>
@endsection
