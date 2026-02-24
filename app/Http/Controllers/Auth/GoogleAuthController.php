<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Central\Buyer;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth page
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Log Google user data for debugging
            \Log::info('Google Login Attempt', [
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
            ]);
            
            // Check if user already exists
            $user = User::on('central')->where('email', $googleUser->email)->first();
            
            if (!$user) {
                // Create new user
                $user = User::on('central')->create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => bcrypt(Str::random(16)), // Random password since they use Google login
                    'email_verified_at' => now(),
                    'google_id' => $googleUser->id,
                ]);
                
                \Log::info('New user created via Google', ['user_id' => $user->id]);
            } else {
                // Update google_id if not set
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
                
                \Log::info('Existing user logged in via Google', ['user_id' => $user->id]);
            }
            
            // Check if Buyer profile exists
            $buyer = Buyer::on('central')->where('user_id', $user->getKey())->first();
            
            if (!$buyer) {
                // Create Buyer profile
                Buyer::on('central')->create([
                    'user_id' => $user->getKey(),
                    'full_name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'phone' => null,
                    'national_id' => null,
                    'kyc_status' => 'unverified',
                ]);
                
                \Log::info('Buyer profile created', ['user_id' => $user->id]);
            }
            
            // Log the user in
            Auth::guard('web')->login($user, true);
            
            \Log::info('User logged in successfully via Google', ['user_id' => $user->id]);
            
            return redirect()->route('buyer.dashboard')->with('success', __('تم تسجيل الدخول بنجاح'));
            
        } catch (\Exception $e) {
            \Log::error('Google OAuth Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->route('marketplace.register')
                ->with('error', __('حدث خطأ أثناء التسجيل بواسطة Google. الرجاء المحاولة مرة أخرى.') . ' ' . $e->getMessage());
        }
    }
}
