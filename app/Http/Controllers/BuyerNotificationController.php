<?php

namespace App\Http\Controllers;

use App\Models\Central\Buyer;
use App\Models\Central\BuyerNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerNotificationController extends Controller
{
    /**
     * Get unread notifications count
     */
    public function count()
    {
        $user = Auth::guard('web')->user();
        $buyer = Buyer::on('central')->where('user_id', $user->id)->first();

        if (!$buyer) {
            return response()->json(['count' => 0]);
        }

        $count = BuyerNotification::on('central')
            ->where('buyer_id', $buyer->id)
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get all notifications
     */
    public function index()
    {
        $user = Auth::guard('web')->user();
        $buyer = Buyer::on('central')->where('user_id', $user->id)->first();

        if (!$buyer) {
            return redirect()->route('buyer.dashboard')
                ->withErrors(['error' => 'يجب إنشاء حساب مشتري أولاً']);
        }

        $notifications = BuyerNotification::on('central')
            ->where('buyer_id', $buyer->id)
            ->with(['saleOffer', 'shareOperation', 'walletTransaction'])
            ->orderByDesc('created_at')
            ->paginate(20);

        $unreadCount = BuyerNotification::on('central')
            ->where('buyer_id', $buyer->id)
            ->unread()
            ->count();

        return view('buyer.notifications', compact('buyer', 'notifications', 'unreadCount'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $user = Auth::guard('web')->user();
        $buyer = Buyer::on('central')->where('user_id', $user->id)->first();

        if (!$buyer) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification = BuyerNotification::on('central')
            ->where('id', $id)
            ->where('buyer_id', $buyer->id)
            ->first();

        if (!$notification) {
            return response()->json(['error' => 'Notification not found'], 404);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all as read
     */
    public function markAllAsRead()
    {
        $user = Auth::guard('web')->user();
        $buyer = Buyer::on('central')->where('user_id', $user->id)->first();

        if (!$buyer) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        BuyerNotification::on('central')
            ->where('buyer_id', $buyer->id)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Get latest notifications (for dropdown)
     */
    public function latest()
    {
        $user = Auth::guard('web')->user();
        $buyer = Buyer::on('central')->where('user_id', $user->id)->first();

        if (!$buyer) {
            return response()->json(['notifications' => []]);
        }

        $notifications = BuyerNotification::on('central')
            ->where('buyer_id', $buyer->id)
            ->with(['saleOffer', 'shareOperation', 'walletTransaction'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return response()->json(['notifications' => $notifications]);
    }
}
