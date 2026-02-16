<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\TenantUser;
use App\Models\Tenant;

class TenantAuthController extends Controller
{
    public function showLoginForm(string $subdomain)
    {
        return view('pages.tenant.auth.login', ['subdomain' => $subdomain]);
    }

    public function login(Request $request, string $subdomain)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required']
        ], [
            'email.required' => __('البريد الإلكتروني مطلوب'),
            'email.email' => __('صيغة البريد الإلكتروني غير صحيحة'),
            'password.required' => __('كلمة المرور مطلوبة'),
        ]);

        // تأكد أن هذا التينانت ما زال مفعّلًا في قاعدة البيانات الرئيسية (central)
        $tenant = Tenant::on('central')->where('Subdomain', $subdomain)->first();

        if (! $tenant || ! $tenant->IsActive) {
            return back()
                ->with('login_error', __('app.tenant_inactive_message'))
                ->onlyInput('email');
        }

        // جلب المستخدم يدويًا من قاعدة بيانات المستأجر ثم تسجيل دخوله
        $user = TenantUser::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()
                ->with('login_error', __('لا يوجد مستخدم بهذا البريد في هذا المستأجر'))
                ->onlyInput('email');
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return back()
                ->with('login_error', __('كلمة المرور غير صحيحة'))
                ->onlyInput('email');
        }

        // خزّن هوية المستخدم في الجلسة مربوطة بالمستأجر الحالي، وسجّل الدخول عبر حارس tenant
        $request->session()->put('tenant_sessions.' . $subdomain . '.user_id', $user->id);
        $request->session()->put('tenant_active_subdomain', $subdomain);
        Auth::guard('tenant')->login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // حمّل الحالة من قاعدة بيانات المستأجر بشكل مؤكد بعد تسجيل الدخول
        $fresh = \App\Models\TenantUser::find($user->id);
        // إذا كان المستخدم مجبَرًا على تغيير كلمة المرور، وجّهه مباشرة لصفحة تغيير كلمة المرور
        if ($fresh && ($fresh->must_change_password ?? false)) {
            return redirect()->route('tenant.subdomain.password.edit', ['subdomain' => $subdomain]);
        }

        return redirect()->route('tenant.subdomain.dashboard', ['subdomain' => $subdomain]);
    }

    public function logout(Request $request, string $subdomain)
    {
        Auth::guard('tenant')->logout();

        // نظّف مفاتيح جلسة التينانت الحالي فقط، مع الحفاظ على جلسات التينانتات الأخرى
        $request->session()->forget('tenant_sessions.' . $subdomain . '.user_id');
        if ($request->session()->get('tenant_active_subdomain') === $subdomain) {
            $request->session()->forget('tenant_active_subdomain');
        }

        // جدد الـ CSRF token لأمان أفضل دون إبطال الجلسة بالكامل
        $request->session()->regenerateToken();
        // أعد التوجيه لنفس صفحة تسجيل الدخول على نطاق المستأجر الحالي
        return redirect()->route('tenant.subdomain.login', ['subdomain' => $subdomain]);
    }
}
