@extends('layout.master')

@section('content')
    <h4 class="mb-3">{{ __('المشترون') }}</h4>
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('الاسم') }}</th>
                        <th>{{ __('البريد') }}</th>
                        <th>{{ __('الجوال') }}</th>
                        <th>{{ __('KYC') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($buyers as $b)
                        <tr>
                            <td>{{ $b->id }}</td>
                            <td>{{ $b->full_name }}</td>
                            <td>{{ $b->email }}</td>
                            <td>{{ $b->phone }}</td>
                            <td>{{ $b->kyc_status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $buyers->links() }}
        </div>
    </div>
@endsection
