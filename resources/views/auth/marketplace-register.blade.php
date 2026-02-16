@extends('layout.master-mini')

@section('content')
    <div class="content-wrapper container auth-wrapper">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <img src="{{ asset('assets/images/logo-w.png') }}" alt="Logo" style="height:32px;">
                </div>
                <div class="auth-title">{{ __('إنشاء حساب') }}</div>
                <div class="auth-subtitle">{{ __('سجّل حساب مشتري لإتمام عمليات الشراء') }}</div>
            </div>
            <div class="auth-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('marketplace.register.post') }}">
                    @csrf
                    <input type="hidden" name="intended" value="{{ request()->query('intended') }}">
                    @php($loggedIn = \Illuminate\Support\Facades\Auth::guard('web')->check())
                    <div class="mb-3">
                        <label class="form-label">{{ __('الاسم الكامل') }}</label>
                        <input type="text" name="name" class="form-control" placeholder="محمد أحمد" required>
                    </div>
                    @if (!$loggedIn)
                        <div class="mb-3">
                            <label class="form-label">{{ __('البريد الإلكتروني') }}</label>
                            <input type="email" name="email" class="form-control" placeholder="name@example.com"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('كلمة المرور') }}</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('تأكيد كلمة المرور') }}</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="••••••"
                                required>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">{{ __('الجوال (اختياري)') }}</label>
                        <input type="text" name="phone" class="form-control" placeholder="مثل: +966551234567">
                        <div class="form-help mt-1">{{ __('يفضل كتابة رقم الجوال بصيغة دولية') }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('الهوية الوطنية (اختياري)') }}</label>
                        <input type="text" name="national_id" class="form-control" placeholder="1234567890">
                    </div>
                    <button type="submit" class="btn btn-auth-primary">{{ __('تسجيل') }}</button>
                </form>
                <div class="auth-alt">
                    {{ __('لديك حساب بالفعل؟') }}
                    <a
                        href="{{ route('marketplace.login', ['intended' => request()->query('intended')]) }}">{{ __('تسجيل دخول') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
