<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Central\ShareOperation;
use App\Models\Central\ShareOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    /**
     * شراء أسهم من عرض محدد
     */
    public function purchase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offer_id' => 'required|integer',
            'shares_count' => 'required|integer|min:1',
            'payment_method' => 'required|string|in:credit_card,bank_transfer,wallet',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $user = $request->user();
            $offerId = $request->offer_id;
            $sharesCount = $request->shares_count;

            // العثور على العرض في قاعدة البيانات المركزية
            $offer = ShareOffer::find($offerId);

            if (!$offer) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'العرض غير موجود'
                ], 404);
            }

            // التحقق من توفر الأسهم
            if ($offer->available_shares < $sharesCount) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'عدد الأسهم المتاحة غير كافٍ',
                    'available_shares' => $offer->available_shares
                ], 422);
            }

            // التحقق من حالة العرض
            if ($offer->status !== 'active') {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'العرض غير نشط حالياً'
                ], 422);
            }

            // حساب المبلغ الإجمالي
            $amountTotal = $sharesCount * $offer->price_per_share;

            // إنشاء عملية الشراء في قاعدة البيانات المركزية
            $operation = ShareOperation::create([
                'offer_id' => $offer->id,
                'tenant_id' => $offer->tenant_id,
                'buyer_id' => $user->id,
                'type' => 'purchase',
                'shares_count' => $sharesCount,
                'price_per_share' => $offer->price_per_share,
                'amount_total' => $amountTotal,
                'currency' => $offer->currency ?? 'SAR',
                'status' => 'pending', // في انتظار الدفع
                'payment_id' => null, // سيتم تحديثه بعد الدفع
                'external_reference' => 'OP-' . strtoupper(uniqid()),
                'metadata' => [
                    'payment_method' => $request->payment_method,
                    'buyer_name' => $user->name,
                    'buyer_email' => $user->email,
                    'offer_title' => $offer->title_ar ?? $offer->title,
                ]
            ]);

            // تحديث الأسهم المتاحة (حجز مؤقت)
            $offer->available_shares -= $sharesCount;
            $offer->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء طلب الشراء بنجاح. يرجى إكمال الدفع.',
                'data' => [
                    'operation_id' => $operation->id,
                    'external_reference' => $operation->external_reference,
                    'shares_count' => $sharesCount,
                    'price_per_share' => $offer->price_per_share,
                    'amount_total' => $amountTotal,
                    'currency' => $operation->currency,
                    'status' => $operation->status,
                    'payment_method' => $request->payment_method,
                    'offer' => [
                        'id' => $offer->id,
                        'title' => $offer->title_ar ?? $offer->title,
                        'city' => $offer->city,
                    ]
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء معالجة طلب الشراء',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تأكيد الدفع وإتمام عملية الشراء
     */
    public function confirmPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'operation_id' => 'required|integer',
            'payment_id' => 'required|string',
            'payment_status' => 'required|string|in:completed,failed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $user = $request->user();
            $operationId = $request->operation_id;

            // العثور على العملية
            $operation = ShareOperation::find($operationId);

            if (!$operation) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'العملية غير موجودة'
                ], 404);
            }

            // التحقق من ملكية العملية
            if ($operation->buyer_id !== $user->id) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك بهذه العملية'
                ], 403);
            }

            // تحديث حالة العملية
            if ($request->payment_status === 'completed') {
                $operation->status = 'completed';
                $operation->payment_id = $request->payment_id;
                
                // تحديث الأسهم المباعة
                $offer = ShareOffer::find($operation->offer_id);
                if ($offer) {
                    $offer->sold_shares += $operation->shares_count;
                    $offer->save();
                }

                $message = 'تم إتمام عملية الشراء بنجاح';
            } else {
                $operation->status = 'failed';
                $operation->payment_id = $request->payment_id;

                // إعادة الأسهم للعرض
                $offer = ShareOffer::find($operation->offer_id);
                if ($offer) {
                    $offer->available_shares += $operation->shares_count;
                    $offer->save();
                }

                $message = 'فشلت عملية الدفع';
            }

            $operation->save();
            DB::commit();

            return response()->json([
                'success' => $request->payment_status === 'completed',
                'message' => $message,
                'data' => [
                    'operation_id' => $operation->id,
                    'status' => $operation->status,
                    'external_reference' => $operation->external_reference,
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تأكيد الدفع',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * إلغاء عملية شراء
     */
    public function cancel(Request $request, $operationId)
    {
        DB::beginTransaction();

        try {
            $user = $request->user();

            // العثور على العملية
            $operation = ShareOperation::find($operationId);

            if (!$operation) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'العملية غير موجودة'
                ], 404);
            }

            // التحقق من ملكية العملية
            if ($operation->buyer_id !== $user->id) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك بهذه العملية'
                ], 403);
            }

            // التحقق من إمكانية الإلغاء
            if ($operation->status !== 'pending') {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن إلغاء هذه العملية. الحالة: ' . $operation->status
                ], 422);
            }

            // إلغاء العملية
            $operation->status = 'cancelled';
            $operation->save();

            // إعادة الأسهم للعرض
            $offer = ShareOffer::find($operation->offer_id);
            if ($offer) {
                $offer->available_shares += $operation->shares_count;
                $offer->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إلغاء العملية بنجاح',
                'data' => [
                    'operation_id' => $operation->id,
                    'status' => $operation->status,
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إلغاء العملية',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
