<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Central\AdminNotification;
use App\Models\Central\Alert;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    /**
     * عرض جميع التنبيهات
     */
    public function index()
    {
        $notifications = AdminNotification::on('central')
            ->with(['offer', 'tenant'])
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * جلب التنبيهات غير المقروءة (API لل real-time)
     */
    public function unread()
    {
        $notifications = AdminNotification::on('central')
            ->with(['offer', 'tenant'])
            ->unread()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $response = [
            'count' => $notifications->count(),
            'notifications' => $notifications->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'offer_id' => $notif->offer_id,
                    'tenant_id' => $notif->tenant_id,
                    'icon' => $notif->icon,
                    'color' => $notif->color,
                    'time_ago' => $notif->created_at->diffForHumans(),
                    'created_at' => $notif->created_at->format('Y-m-d H:i'),
                ];
            }),
        ];

        return response()->json($response);
    }

    /**
     * جلب جميع التنبيهات (موافقات العروض + تنبيهات السوق)
     */
    public function allUnread()
    {
        // جلب تنبيهات موافقات العروض
        $notifications = AdminNotification::on('central')
            ->with(['offer', 'tenant'])
            ->unread()
            ->orderBy('created_at', 'desc')
            ->get();

        // جلب تنبيهات السوق (Alerts)
        $alerts = Alert::on('central')
            ->where('scope', 'admin')
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();

        // دمج التنبيهات
        $allItems = [];

        foreach ($notifications as $notif) {
            $itemUrl = route('admin.offer-approval.dashboard');
            if ($notif->offer_id) {
                $itemUrl = route('admin.offer-approval.show', $notif->offer_id);
            }

            $allItems[] = [
                'type' => 'notification',
                'id' => $notif->id,
                'title' => $notif->title,
                'message' => $notif->message,
                'icon' => $notif->icon,
                'color' => $notif->color,
                'time_ago' => $notif->created_at->diffForHumans(),
                'created_at' => $notif->created_at->format('Y-m-d H:i'),
                'timestamp' => $notif->created_at->timestamp,
                'link' => $itemUrl,
            ];
        }

        foreach ($alerts as $alert) {
            $allItems[] = [
                'type' => 'alert',
                'id' => $alert->id,
                'title' => $alert->title,
                'message' => $alert->message,
                'icon' => 'mdi-bell-ring-outline',
                'color' => 'danger',
                'time_ago' => $alert->created_at->diffForHumans(),
                'created_at' => $alert->created_at->format('Y-m-d H:i'),
                'timestamp' => $alert->created_at->timestamp,
                'link' => route('admin.market.alerts'),
            ];
        }

        // ترتيب حسب الوقت
        usort($allItems, function($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });

        $response = [
            'count' => count($allItems),
            'items' => array_slice($allItems, 0, 10),
        ];

        return response()->json($response);
    }

    /**
     * تحديد تنبيه كمقروء
     */
    public function markAsRead($id)
    {
        $notification = AdminNotification::on('central')->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * تحديد جميع التنبيهات كمقروءة
     */
    public function markAllAsRead()
    {
        AdminNotification::on('central')
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * حذف تنبيه
     */
    public function destroy($id)
    {
        $notification = AdminNotification::on('central')->findOrFail($id);
        $notification->delete();

        return back()->with('success', 'تم حذف التنبيه');
    }
}
