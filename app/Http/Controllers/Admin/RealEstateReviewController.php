<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Central\ShareOffer;
use App\Models\Central\OfferReview;
use App\Models\Central\RealEstateCheckpoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RealEstateReviewController extends Controller
{
    /**
     * عرض صفحة المراجعة العقارية
     */
    public function show($id)
    {
        $offer = ShareOffer::on('central')
            ->with(['tenant', 'reviews', 'realEstateCheckpoints'])
            ->findOrFail($id);

        if (!$offer->needsRealEstateReview()) {
            return redirect()
                ->route('admin.offer-approval.dashboard')
                ->with('error', 'هذا العرض ليس في مرحلة المراجعة العقارية');
        }

        return view('admin.offer-approval.real-estate-review', compact('offer'));
    }

    /**
     * حفظ نقاط المراجعة العقارية
     */
    public function saveCheckpoints(Request $request, $id)
    {
        \Log::info('saveCheckpoints called', [
            'id' => $id,
            'checkpoints' => $request->checkpoints,
            'all_input' => $request->all()
        ]);

        $request->validate([
            'checkpoints' => 'required|array|min:1',
            'checkpoints.*' => 'required|string|min:5',
        ], [
            'checkpoints.required' => 'يجب إضافة نقطة واحدة على الأقل',
            'checkpoints.*.required' => 'يجب ملء جميع النقاط',
            'checkpoints.*.min' => 'كل نقطة يجب أن تكون 5 أحرف على الأقل',
        ]);

        $offer = ShareOffer::on('central')->findOrFail($id);

        if (!$offer->needsRealEstateReview()) {
            return back()->with('error', 'هذا العرض ليس في مرحلة المراجعة العقارية');
        }

        try {
            DB::connection('central')->transaction(function () use ($offer, $request) {
                // حذف النقاط القديمة
                $deleted = RealEstateCheckpoint::on('central')
                    ->where('offer_id', $offer->id)
                    ->delete();
                
                \Log::info('Deleted old checkpoints', ['count' => $deleted]);

                // إضافة النقاط الجديدة
                foreach ($request->checkpoints as $index => $checkpoint) {
                    $cp = new RealEstateCheckpoint();
                    $cp->setConnection('central');
                    $cp->offer_id = $offer->id;
                    $cp->checkpoint_text = $checkpoint;
                    $cp->sort_order = $index;
                    $cp->created_by = Auth::id();
                    $cp->save();
                    
                    \Log::info('Saved checkpoint', [
                        'id' => $cp->id,
                        'text' => $checkpoint,
                        'order' => $index
                    ]);
                }
            });

            \Log::info('Transaction completed successfully');
            return back()->with('success', 'تم حفظ نقاط المراجعة العقارية');
        } catch (\Exception $e) {
            \Log::error('Failed to save checkpoints', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'حدث خطأ أثناء حفظ النقاط: ' . $e->getMessage());
        }
    }

    /**
     * الموافقة العقارية
     */
    public function approve(Request $request, $id)
    {
        $offer = ShareOffer::on('central')->findOrFail($id);

        if (!$offer->needsRealEstateReview()) {
            return back()->with('error', 'هذا العرض ليس في مرحلة المراجعة العقارية');
        }

        // التحقق من وجود نقاط مراجعة
        $checkpointsCount = RealEstateCheckpoint::on('central')
            ->where('offer_id', $offer->id)
            ->count();

        if ($checkpointsCount === 0) {
            return back()->with('error', 'يجب إضافة نقاط المراجعة العقارية قبل الموافقة');
        }

        DB::connection('central')->transaction(function () use ($offer, $request) {
            // تسجيل المراجعة
            OfferReview::create([
                'offer_id' => $offer->id,
                'review_type' => 'real_estate',
                'decision' => 'approved',
                'notes' => $request->input('notes'),
                'reviewed_by' => Auth::id(),
            ]);

            // تحديث حالة العرض
            $offer->update([
                'approval_status' => 'real_estate_approved',
                'approval_progress' => 100,
                'real_estate_reviewed_at' => now(),
                'status' => 'active', // العرض يصبح نشط في الموقع
            ]);
        });

        return redirect()
            ->route('admin.offer-approval.dashboard')
            ->with('success', 'تمت الموافقة العقارية وأصبح العرض نشطاً');
    }

    /**
     * رفض عقاري
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

        if (!$offer->needsRealEstateReview()) {
            return back()->with('error', 'هذا العرض ليس في مرحلة المراجعة العقارية');
        }

        DB::connection('central')->transaction(function () use ($offer, $request) {
            OfferReview::create([
                'offer_id' => $offer->id,
                'review_type' => 'real_estate',
                'decision' => 'rejected',
                'notes' => $request->input('rejection_notes'),
                'reviewed_by' => Auth::id(),
            ]);

            $offer->update([
                'approval_status' => 'real_estate_rejected',
                'approval_progress' => 50,
                'real_estate_reviewed_at' => now(),
                'rejection_notes' => $request->input('rejection_notes'),
            ]);
        });

        return redirect()
            ->route('admin.offer-approval.dashboard')
            ->with('success', 'تم رفض العرض عقارياً');
    }
}
