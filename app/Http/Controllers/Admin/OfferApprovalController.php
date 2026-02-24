<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Central\ShareOffer;
use App\Models\Central\OfferReview;
use App\Models\Central\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OfferApprovalController extends Controller
{
    /**
     * عرض تفاصيل العرض للمراجعة
     */
    public function show($id)
    {
        $offer = ShareOffer::on('central')
            ->with(['tenant', 'reviews.reviewer'])
            ->findOrFail($id);

        return view('admin.offer-approval.review', compact('offer'));
    }

    /**
     * الموافقة على العرض (المراجعة الأولية)
     */
    public function approve(Request $request, $id)
    {
        $offer = ShareOffer::on('central')->findOrFail($id);

        if ($offer->approval_status !== 'pending_approval') {
            return back()->with('error', 'هذا العرض ليس في حالة تسمح بالموافقة الأولية');
        }

        DB::connection('central')->transaction(function () use ($offer, $request) {
            // تسجيل المراجعة
            OfferReview::create([
                'offer_id' => $offer->id,
                'review_type' => 'initial',
                'decision' => 'approved',
                'notes' => $request->input('notes'),
                'reviewed_by' => Auth::id(),
            ]);

            // تحديث حالة العرض
            $offer->update([
                'approval_status' => 'under_real_estate_review',
                'approval_progress' => 50,
                'first_reviewed_at' => now(),
                'reviewed_by' => Auth::id(),
                'rejection_notes' => null,
            ]);
        });

        return redirect()
            ->route('admin.offer-approval.dashboard')
            ->with('success', 'تمت الموافقة على العرض ونقله للمراجعة العقارية');
    }

    /**
     * رفض العرض (المراجعة الأولية)
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_notes' => 'required|string|min:10',
        ], [
            'rejection_notes.required' => 'يجب إدخال أسباب الرفض',
            'rejection_notes.min' => 'أسباب الرفض يجب أن تكون 10 أحرف على الأقل',
        ]);

        $offer = ShareOffer::on('central')->findOrFail($id);

        if ($offer->approval_status !== 'pending_approval') {
            return back()->with('error', 'هذا العرض ليس في حالة تسمح بالرفض');
        }

        DB::connection('central')->transaction(function () use ($offer, $request) {
            OfferReview::create([
                'offer_id' => $offer->id,
                'review_type' => 'initial',
                'decision' => 'rejected',
                'notes' => $request->input('rejection_notes'),
                'reviewed_by' => Auth::id(),
            ]);

            $offer->update([
                'approval_status' => 'rejected',
                'approval_progress' => 0,
                'first_reviewed_at' => now(),
                'reviewed_by' => Auth::id(),
                'rejection_notes' => $request->input('rejection_notes'),
            ]);
        });

        return redirect()
            ->route('admin.offer-approval.dashboard')
            ->with('success', 'تم رفض العرض وإبلاغ صاحبه بالأسباب');
    }
}
