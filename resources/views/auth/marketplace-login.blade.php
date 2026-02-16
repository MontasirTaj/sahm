@extends('layout.master-mini')

@section('content')
    <div class="content-wrapper container auth-wrapper">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <img src="{{ asset('assets/images/sahmi.jpeg') }}" alt="سهمي"
                        style="height:48px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                </div>
                <div class="auth-title">{{ __('تسجيل الدخول') }}</div>
                <div class="auth-subtitle">{{ __('أدخل بياناتك للوصول إلى حساب المشتري') }}</div>
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
                <form method="POST" action="{{ route('marketplace.login.post') }}">
                    @csrf
                    <input type="hidden" name="intended" value="{{ $intended }}">
                    <div class="mb-3">
                        <label class="form-label">{{ __('البريد الإلكتروني') }}</label>
                        <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                        <div class="form-help mt-1">{{ __('استخدم بريدك المسجل لدينا') }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('كلمة المرور') }}</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••" required>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">{{ __('تذكرني') }}</label>
                        </div>
                        {{-- Optional: add forgot password when available --}}
                        {{-- <a href="{{ route('password.request') }}">{{ __('نسيت كلمة المرور؟') }}</a> --}}
                    </div>
                    <button type="submit" class="btn btn-auth-primary">{{ __('دخول') }}</button>
                </form>
                <div class="auth-alt">
                    {{ __('ليس لديك حساب؟') }}
                    <a
                        href="{{ route('marketplace.register', ['intended' => $intended]) }}">{{ __('إنشاء حساب جديد') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
