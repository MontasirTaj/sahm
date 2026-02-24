<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Central\ShareOffer;
use App\Models\Central\OfferReview;
use Illuminate\Support\Facades\DB;

class OfferApprovalDashboardController extends Controller
{
    /**
     * عرض Dashboard الرئيسي للعروض
     */
    public function index()
    {
        // إحصائيات العروض
        $stats = [
            'pending_initial' => ShareOffer::on('central')
                ->where('approval_status', 'pending_approval')
                ->count(),
            
            'pending_real_estate' => ShareOffer::on('central')
                ->where('approval_status', 'under_real_estate_review')
                ->count(),
            
            'approved' => ShareOffer::on('central')
                ->where('approval_status', 'real_estate_approved')
                ->count(),
            
            'rejected' => ShareOffer::on('central')
                ->whereIn('approval_status', ['rejected', 'real_estate_rejected'])
                ->count(),
            
            'total' => ShareOffer::on('central')->count(),
        ];

        // العروض المعلقة (تحتاج مراجعة)
        $pendingOffers = ShareOffer::on('central')
            ->with('tenant')
            ->where('approval_status', 'pending_approval')
            ->orderBy('submitted_at', 'desc')
            ->limit(10)
            ->get();

        // العروض تحت المراجعة العقارية
        $realEstateReviewOffers = ShareOffer::on('central')
            ->with('tenant')
            ->where('approval_status', 'under_real_estate_review')
            ->orderBy('first_reviewed_at', 'desc')
            ->limit(10)
            ->get();

        // آخر المراجعات
        $recentReviews = OfferReview::on('central')
            ->with(['offer', 'reviewer'])
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        return view('admin.offer-approval.dashboard', compact(
            'stats',
            'pendingOffers',
            'realEstateReviewOffers',
            'recentReviews'
        ));
    }

    /**
     * عرض قائمة العروض حسب الحالة
     */
    public function listByStatus($status)
    {
        $query = ShareOffer::on('central')->with('tenant');

        switch ($status) {
            case 'pending':
                $query->where('approval_status', 'pending_approval');
                $title = 'العروض المعلقة - تحتاج مراجعة أولية';
                break;
            case 'real-estate-review':
                $query->where('approval_status', 'under_real_estate_review');
                $title = 'العروض تحت المراجعة العقارية';
                break;
            case 'approved':
                $query->where('approval_status', 'real_estate_approved');
                $title = 'العروض المعتمدة';
                break;
            case 'rejected':
                $query->whereIn('approval_status', ['rejected', 'real_estate_rejected']);
                $title = 'العروض المرفوضة';
                break;
            default:
                $title = 'جميع العروض';
        }

        $offers = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.offer-approval.list', compact('offers', 'title', 'status'));
    }
}
