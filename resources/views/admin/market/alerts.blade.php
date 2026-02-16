@extends('layout.master')

@section('content')
    <h4 class="mb-3">{{ __('التنبيهات') }}</h4>
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('النوع') }}</th>
                        <th>{{ __('المشترك') }}</th>
                        <th>{{ __('العنوان') }}</th>
                        <th>{{ __('الرسالة') }}</th>
                        <th>{{ __('الزمن') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($alerts as $a)
                        <tr>
                            <td>{{ $a->id }}</td>
                            <td>{{ $a->type }}</td>
                            <td>{{ $a->tenant_id }}</td>
                            <td>{{ $a->title }}</td>
                            <td>{{ $a->message }}</td>
                            <td>{{ $a->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $alerts->links() }}
        </div>
    </div>
@endsection
