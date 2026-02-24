<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('web')->attempt($credentials, $remember)) {
            // احصل على intended URL قبل تجديد الجلسة
            $intended = $request->session()->get('url.intended');
            
            $request->session()->regenerate();
            
            // امسح url.intended من الـ session بعد التجديد
            $request->session()->forget('url.intended');
            
            // تصفية intended URL لتجنب التوجيه للإشعارات
            if ($intended && !str_contains($intended, '/notifications/')) {
                return redirect()->to($intended);
            }
            
            return redirect()->route('admin.dashboard');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'auth' => __('auth.failed'),
            ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.logout.success');
    }
}
