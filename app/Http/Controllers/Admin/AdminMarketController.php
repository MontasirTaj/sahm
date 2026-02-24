<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Central\ShareOffer;
use App\Models\Central\ShareOperation;
use App\Models\Central\Buyer;
use App\Models\Central\Alert;

class AdminMarketController extends Controller
{
    public function offers()
    {
        $offers = ShareOffer::on('central')->orderByDesc('id')->paginate(20);
        return view('admin.market.offers', compact('offers'));
    }

    public function operations()
    {
        $ops = ShareOperation::on('central')->orderByDesc('id')->paginate(30);
        return view('admin.market.operations', compact('ops'));
    }

    public function buyers()
    {
        $buyers = Buyer::on('central')->orderByDesc('id')->paginate(30);
        return view('admin.market.buyers', compact('buyers'));
    }

    public function alerts()
    {
        // عرض كل التنبيهات (مقروءة وغير مقروءة)
        $alerts = Alert::on('central')->where('scope','admin')->orderByDesc('id')->paginate(30);
        return view('admin.market.alerts', compact('alerts'));
    }

    public function alertsFeed()
    {
        // عرض فقط التنبيهات غير المقروءة في زر التنبيهات
        $items = Alert::on('central')
            ->where('scope','admin')
            ->where('is_read', false)
            ->orderByDesc('id')
            ->limit(10)
            ->get()
            ->map(function($a){
                return [
                    'id' => $a->id,
                    'type' => $a->type,
                    'title' => $a->title,
                    'message' => $a->message,
                    'created_at' => optional($a->created_at)->format('Y-m-d H:i'),
                    'link' => route('admin.market.alerts'),
                ];
            });
        $counts = [
            'recent' => $items->count(),
        ];
        return response()->json(['items' => $items, 'counts' => $counts]);
    }

    public function markAlertAsRead($id)
    {
        $alert = Alert::on('central')->find($id);
        if ($alert && $alert->scope === 'admin') {
            $alert->is_read = true;
            $alert->save();
        }
        return response()->json(['success' => true]);
    }
}
