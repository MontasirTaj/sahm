<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BuyerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::guard('web')->user();
        $buyer = DB::connection('central')->table('buyers')->where('user_id', $user->getKey())->first();
        if (! $buyer) {
            return redirect()->route('marketplace.register', ['intended' => route('buyer.dashboard')])
                ->withErrors(['buyer' => __('يجب إنشاء حساب مشتري أولاً')]);
        }

        $holdings = DB::connection('central')->table('buyer_holdings')
            ->join('share_offers', 'buyer_holdings.offer_id', '=', 'share_offers.id')
            ->select('buyer_holdings.*', 'share_offers.title', 'share_offers.currency', 'share_offers.price_per_share')
            ->where('buyer_holdings.buyer_id', $buyer->id)
            ->orderByDesc('buyer_holdings.last_transaction_at')
            ->get();

        $operations = DB::connection('central')->table('share_operations')
            ->join('share_offers', 'share_operations.offer_id', '=', 'share_offers.id')
            ->select('share_operations.*', 'share_offers.title')
            ->where('share_operations.buyer_id', $buyer->id)
            ->orderByDesc('share_operations.id')
            ->paginate(10);

        // Statistics
        $stats = [
            'total_shares' => DB::connection('central')->table('buyer_holdings')
                ->where('buyer_id', $buyer->id)
                ->sum('shares_owned'),
            'total_offers' => DB::connection('central')->table('buyer_holdings')
                ->where('buyer_id', $buyer->id)
                ->count(),
            'total_invested' => DB::connection('central')->table('share_operations')
                ->where('buyer_id', $buyer->id)
                ->where('type', 'purchase')
                ->where('status', 'completed')
                ->sum('amount_total'),
            'completed_operations' => DB::connection('central')->table('share_operations')
                ->where('buyer_id', $buyer->id)
                ->where('status', 'completed')
                ->count(),
        ];

        // Operations by type
        $operationsByType = DB::connection('central')->table('share_operations')
            ->select('type', DB::raw('count(*) as count'))
            ->where('buyer_id', $buyer->id)
            ->groupBy('type')
            ->get()
            ->pluck('count', 'type')
            ->toArray();

        // Operations by status
        $operationsByStatus = DB::connection('central')->table('share_operations')
            ->select('status', DB::raw('count(*) as count'))
            ->where('buyer_id', $buyer->id)
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        return view('buyer.dashboard', compact('user', 'buyer', 'holdings', 'operations', 'stats', 'operationsByType', 'operationsByStatus'));
    }

    public function profile()
    {
        $user = Auth::guard('web')->user();
        $buyer = DB::connection('central')->table('buyers')->where('user_id', $user->getKey())->first();
        return view('buyer.profile', compact('user', 'buyer'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::guard('web')->user();
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'phone' => ['nullable','string','max:30'],
            'national_id' => ['nullable','string','max:50'],
            'avatar' => ['nullable','image','max:2048'],
        ]);

        // Avatar upload
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path; // use asset('storage/'.$user->avatar) in views
        }
        $user->name = $data['name'];
        $user->save();

        // Update buyer extra fields
        DB::connection('central')->table('buyers')->updateOrInsert([
            'user_id' => $user->getKey(),
        ], [
            'full_name' => $data['name'],
            'email' => $user->email,
            'phone' => $data['phone'] ?? null,
            'national_id' => $data['national_id'] ?? null,
            'updated_at' => now(),
        ]);

        return redirect()->route('buyer.profile')->with('status', __('تم تحديث الملف الشخصي'));
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::guard('web')->user();
        $data = $request->validate([
            'current_password' => ['required','string'],
            'password' => ['required','confirmed','min:6'],
        ]);
        if (! Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => __('كلمة المرور الحالية غير صحيحة')]);
        }
        $user->password = $data['password'];
        $user->save();
        return redirect()->route('buyer.profile')->with('status', __('تم تغيير كلمة المرور'));
    }
}
