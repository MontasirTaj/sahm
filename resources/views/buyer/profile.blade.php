@extends('layout.master-mini')

@section('content')
    <div class="content-wrapper container">
        <div class="section-header">
            <div class="card section-card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ __('الملف الشخصي للمشتري') }}</h3>
                        <p class="text-muted mb-0">{{ __('حدّث بياناتك وصورتك وكلمة المرور') }}</p>
                    </div>
                    <div>
                        <a href="{{ route('buyer.dashboard') }}" class="btn btn-outline-primary">{{ __('عودة للوحة') }}</a>
                    </div>
                </div>
            </div>
        </div>
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">{{ __('تحديث المعلومات') }}</h5>
                        <form method="POST" action="{{ route('buyer.profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-2">
                                <label>{{ __('الاسم') }}</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="form-group mb-2">
                                <label>{{ __('الجوال') }}</label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', optional($buyer)->phone) }}">
                            </div>
                            <div class="form-group mb-2">
                                <label>{{ __('الهوية الوطنية') }}</label>
                                <input type="text" name="national_id" class="form-control"
                                    value="{{ old('national_id', optional($buyer)->national_id) }}">
                            </div>
                            <div class="form-group mb-2">
                                <label>{{ __('الصورة الشخصية') }}</label>
                                <input type="file" name="avatar" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('حفظ') }}</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">{{ __('تغيير كلمة المرور') }}</h5>
                        <form method="POST" action="{{ route('buyer.password.update') }}">
                            @csrf
                            <div class="form-group mb-2">
                                <label>{{ __('كلمة المرور الحالية') }}</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="form-group mb-2">
                                <label>{{ __('كلمة المرور الجديدة') }}</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>{{ __('تأكيد كلمة المرور') }}</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-outline-primary">{{ __('تغيير') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
