<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;

class TenantProfileController extends Controller
{
    public function editPassword()
    {
        $user = Auth::guard('tenant')->user();

        return view('pages.tenant.profile.password', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::guard('tenant')->user();

        if (! $user) {
            abort(403);
        }

        $forceChange = isset($user->must_change_password) && $user->must_change_password;
        $changingPassword = $request->filled('current_password') || $request->filled('password') || $request->filled('password_confirmation');
        $changingAvatar = $request->hasFile('avatar');

        $rules = [];

        if ($changingPassword) {
            // عندما يكون المستخدم مجبَرًا على تغيير كلمة المرور، لا تطلب كلمة المرور الحالية
            if ($forceChange) {
                $rules['password'] = ['required', 'confirmed', PasswordRule::min(6)->mixedCase()->numbers()->symbols()];
            } else {
                $rules['current_password'] = ['required'];
                $rules['password'] = ['required', 'confirmed', PasswordRule::min(6)->mixedCase()->numbers()->symbols()];
            }
        }

        if ($changingAvatar) {
            $rules['avatar'] = ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'];
        }

        if (empty($rules)) {
            return back()->withErrors(['general' => __('app.nothing_to_update')]);
        }

        $messages = [
            'current_password.required' => __('يرجى إدخال كلمة المرور الحالية'),
            'password.required' => __('يرجى إدخال كلمة المرور الجديدة'),
            'password.confirmed' => __('تأكيد كلمة المرور لا يطابق الجديدة'),
            'avatar.image' => __('الملف يجب أن يكون صورة'),
            'avatar.mimes' => __('الصيغ المسموحة: jpg, jpeg, png, webp'),
            'avatar.max' => __('أقصى حجم للصورة 2MB'),
        ];
        $validated = $request->validate($rules, $messages);

        if ($changingPassword) {
            // تحقق من كلمة المرور الحالية فقط إذا لم يكن مجبَرًا على التغيير
            if (! $forceChange) {
                if (! Hash::check($validated['current_password'], $user->password)) {
                    return back()->withErrors(['current_password' => __('كلمة المرور الحالية غير صحيحة')])->withInput();
                }
                if (isset($validated['password']) && $validated['password'] === $validated['current_password']) {
                    return back()->withErrors(['password' => __('الرجاء اختيار كلمة مرور مختلفة عن الحالية')])->withInput();
                }
            }

            $user->password = Hash::make($validated['password']);
            // بعد تغيير كلمة المرور بنجاح، لم يعد مجبَرًا على تغييرها
            if (isset($user->must_change_password) && $user->must_change_password) {
                $user->must_change_password = false;
            }
        }

        if ($changingAvatar) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        // إذا كان التغيير إجباري وتم بنجاح، وجّه المستخدم للوحة التحكم مباشرة
        if ($changingPassword && $forceChange) {
            $subdomain = $request->route('subdomain');
            return redirect()->route('tenant.subdomain.dashboard', ['subdomain' => $subdomain])
                ->with('status', __('app.profile_updated'));
        }

        return back()->with('status', __('app.profile_updated'));
    }
}
