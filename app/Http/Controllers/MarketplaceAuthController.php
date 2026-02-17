<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketplaceAuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $intended = $request->query('intended');
        return view('auth.marketplace-login', compact('intended'));
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','email'],
            'password' => ['required','string'],
        ]);

        if (Auth::guard('web')->attempt(['email' => $data['email'], 'password' => $data['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();
            $intended = $request->input('intended');
            return $intended ? redirect()->to($intended) : redirect()->route('buyer.dashboard');
        }

        return back()->withErrors(['email' => __('بيانات الدخول غير صحيحة')])->withInput();
    }

    public function showRegisterForm(Request $request)
    {
        $intended = $request->query('intended');
        return view('auth.marketplace-register', compact('intended'));
    }

    public function register(Request $request)
    {
        if (Auth::guard('web')->check()) {
            // Logged-in user: create Buyer profile for current user
            $data = $request->validate([
                'name' => ['required','string','max:255'],
                'phone' => ['nullable','string','max:30'],
                'national_id' => ['nullable','string','max:50'],
            ]);
            $user = Auth::guard('web')->user();
            \App\Models\Central\Buyer::on('central')->updateOrCreate(
                ['user_id' => $user->getKey()],
                [
                    'full_name' => $data['name'] ?? $user->name,
                    'email' => $user->email,
                    'phone' => $data['phone'] ?? null,
                    'national_id' => $data['national_id'] ?? null,
                    'kyc_status' => 'unverified',
                ]
            );
            $intended = $request->input('intended');
            return $intended ? redirect()->to($intended) : redirect()->route('buyer.dashboard');
        } else {
            // New marketplace account: create User and Buyer
            $data = $request->validate([
                'name' => ['required','string','max:255'],
                'email' => ['required','email','max:255','unique:central.users,email'],
                'password' => ['required','confirmed','min:6'],
                'phone' => ['nullable','string','max:30'],
                'national_id' => ['nullable','string','max:50'],
            ]);
            $user = \App\Models\User::on('central')->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);
            \App\Models\Central\Buyer::on('central')->create([
                'user_id' => $user->getKey(),
                'full_name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'national_id' => $data['national_id'] ?? null,
                'kyc_status' => 'unverified',
            ]);
            Auth::guard('web')->login($user);
            $intended = $request->input('intended');
            return $intended ? redirect()->to($intended) : redirect()->route('buyer.dashboard');
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing');
    }
}
