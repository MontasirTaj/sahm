<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Central\Buyer;
use App\Models\Central\BuyerHolding;
use App\Models\Central\BuyerSaleOffer;
use App\Models\Central\ShareOperation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BuyerSalesController extends Controller
{
    /**
     * عرض أسهم للبيع (إنشاء عرض بيع ثانوي)
     */
    public function createSaleOffer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'holding_id' => 'required|integer|exists:central.buyer_holdings,id',
            'shares_count' => 'required|integer|min:1',
            'price_per_share' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:1000',
            'expires_in_days' => 'nullable|integer|min:1|max:365',
        ], [
            'holding_id.required' => 'يجب تحديد الأسهم المراد بيعها',
            'holding_id.exists' => 'الأسهم المحددة غير موجودة',
            'shares_count.required' => 'يجب تحديد عدد الأسهم',
            'shares_count.min' => 'يجب أن يكون عدد الأسهم على الأقل 1',
            'price_per_share.required' => 'يجب تحديد السعر',
            'price_per_share.min' => 'السعر يجب أن يكون أكبر من 0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $user = $request->user();
            
            // الحصول على بيانات المشتري
            $buyer = Buyer::on('central')->where('user_id', $user->id)->first();
            
            if (!$buyer) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'يجب إنشاء حساب مشتري أولاً',
                ], 403);
            }

            // الحصول على الممتلكات
            $holding = BuyerHolding::on('central')
                ->where('id', $request->holding_id)
                ->where('buyer_id', $buyer->id)
                ->first();

            if (!$holding) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'الأسهم المحددة غير موجودة أو لا تملكها',
                ], 404);
            }

            // التحقق من توفر الأسهم الكافية
            $availableShares = $holding->shares_owned;
            
            // خصم الأسهم المعروضة للبيع حالياً
            $alreadyListed = BuyerSaleOffer::on('central')
                ->where('seller_buyer_id', $buyer->id)
                ->where('holding_id', $holding->id)
                ->where('status', 'active')
                ->sum('shares_count');
            
            $availableShares -= $alreadyListed;

            if ($availableShares < $request->shares_count) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'عدد الأسهم المتاحة للبيع غير كافٍ',
                    'available_shares' => $availableShares,
                    'already_listed' => $alreadyListed,
                ], 422);
            }

            // حساب تاريخ الانتهاء
            $expiresAt = null;
            if ($request->filled('expires_in_days')) {
                $expiresAt = now()->addDays((int) $request->expires_in_days);
            }

            // إنشاء عرض البيع
            $saleOffer = BuyerSaleOffer::on('central')->create([
                'seller_buyer_id' => $buyer->id,
                'holding_id' => $holding->id,
                'original_offer_id' => $holding->offer_id,
                'shares_count' => $request->shares_count,
                'price_per_share' => $request->price_per_share,
                'currency' => $holding->offer->currency ?? 'SAR',
                'status' => 'active',
                'description' => $request->description,
                'expires_at' => $expiresAt,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم عرض الأسهم للبيع بنجاح',
                'data' => [
                    'sale_offer_id' => $saleOffer->id,
                    'shares_count' => $saleOffer->shares_count,
                    'price_per_share' => $saleOffer->price_per_share,
                    'total_value' => $saleOffer->shares_count * $saleOffer->price_per_share,
                    'currency' => $saleOffer->currency,
                    'status' => $saleOffer->status,
                    'expires_at' => $saleOffer->expires_at ? $saleOffer->expires_at->format('Y-m-d H:i:s') : null,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء عرض الأسهم للبيع',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * عرض جميع عروض البيع الخاصة بالمشتري
     */
    public function mySaleOffers(Request $request)
    {
        try {
            $user = $request->user();
            $buyer = Buyer::on('central')->where('user_id', $user->id)->first();

            if (!$buyer) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب إنشاء حساب مشتري أولاً',
                ], 403);
            }

            $saleOffers = BuyerSaleOffer::on('central')
                ->where('seller_buyer_id', $buyer->id)
                ->with('originalOffer:id,title,title_ar,cover_image')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($offer) {
                    return [
                        'id' => $offer->id,
                        'offer_title' => $offer->originalOffer->title_ar ?? $offer->originalOffer->title,
                        'offer_cover_image' => $offer->originalOffer->cover_image 
                            ? asset('storage/' . $offer->originalOffer->cover_image) 
                            : null,
                        'shares_count' => $offer->shares_count,
                        'price_per_share' => $offer->price_per_share,
                        'total_value' => $offer->shares_count * $offer->price_per_share,
                        'currency' => $offer->currency,
                        'status' => $offer->status,
                        'status_ar' => $this->getStatusArabic($offer->status),
                        'description' => $offer->description,
                        'expires_at' => $offer->expires_at ? $offer->expires_at->format('Y-m-d H:i:s') : null,
                        'sold_at' => $offer->sold_at ? $offer->sold_at->format('Y-m-d H:i:s') : null,
                        'created_at' => $offer->created_at->format('Y-m-d H:i:s'),
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $saleOffers,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * إلغاء عرض بيع
     */
    public function cancelSaleOffer(Request $request, $saleOfferId)
    {
        DB::beginTransaction();

        try {
            $user = $request->user();
            $buyer = Buyer::on('central')->where('user_id', $user->id)->first();

            if (!$buyer) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'يجب إنشاء حساب مشتري أولاً',
                ], 403);
            }

            $saleOffer = BuyerSaleOffer::on('central')
                ->where('id', $saleOfferId)
                ->where('seller_buyer_id', $buyer->id)
                ->where('status', 'active')
                ->first();

            if (!$saleOffer) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'عرض البيع غير موجود أو تم إلغاؤه مسبقاً',
                ], 404);
            }

            $saleOffer->status = 'cancelled';
            $saleOffer->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إلغاء عرض البيع بنجاح',
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إلغاء العرض',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * شراء أسهم من السوق الثانوي
     */
    public function buyFromSecondaryMarket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sale_offer_id' => 'required|integer|exists:central.buyer_sale_offers,id',
        ], [
            'sale_offer_id.required' => 'يجب تحديد العرض',
            'sale_offer_id.exists' => 'العرض غير موجود',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $user = $request->user();
            $buyer = Buyer::on('central')->where('user_id', $user->id)->first();

            if (!$buyer) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'يجب إنشاء حساب مشتري أولاً',
                ], 403);
            }

            $saleOffer = BuyerSaleOffer::on('central')
                ->where('id', $request->sale_offer_id)
                ->where('status', 'active')
                ->with(['seller', 'holding', 'originalOffer'])
                ->first();

            if (!$saleOffer) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'العرض غير متاح أو تم بيعه مسبقاً',
                ], 404);
            }

            // التحقق أن المشتري ليس البائع نفسه
            if ($saleOffer->seller_buyer_id == $buyer->id) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكنك شراء أسهمك الخاصة',
                ], 422);
            }

            // التحقق من انتهاء صلاحية العرض
            if ($saleOffer->expires_at && $saleOffer->expires_at < now()) {
                $saleOffer->status = 'expired';
                $saleOffer->save();
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'العرض منتهي الصلاحية',
                ], 422);
            }

            $sharesCount = $saleOffer->shares_count;
            $pricePerShare = $saleOffer->price_per_share;
            $totalAmount = $sharesCount * $pricePerShare;

            // تحديث ممتلكات البائع (تقليل الأسهم)
            $sellerHolding = $saleOffer->holding;
            $sellerHolding->shares_owned -= $sharesCount;
            
            if ($sellerHolding->shares_owned <= 0) {
                $sellerHolding->delete();
            } else {
                $sellerHolding->save();
            }

            // تحديث أو إنشاء ممتلكات المشتري (زيادة الأسهم)
            $buyerHolding = BuyerHolding::on('central')->firstOrNew([
                'buyer_id' => $buyer->id,
                'offer_id' => $saleOffer->original_offer_id,
            ]);

            $oldShares = $buyerHolding->shares_owned ?? 0;
            $oldAvg = $buyerHolding->avg_price_per_share ?? 0;
            
            $newShares = $oldShares + $sharesCount;
            $newAvg = (($oldShares * $oldAvg) + ($sharesCount * $pricePerShare)) / $newShares;
            
            $buyerHolding->shares_owned = $newShares;
            $buyerHolding->avg_price_per_share = $newAvg;
            $buyerHolding->last_transaction_at = now();
            $buyerHolding->save();

            // تسجيل عملية البيع للبائع
            ShareOperation::on('central')->create([
                'offer_id' => $saleOffer->original_offer_id,
                'tenant_id' => $saleOffer->originalOffer->tenant_id,
                'buyer_id' => $saleOffer->seller_buyer_id,
                'type' => 'sell',
                'shares_count' => $sharesCount,
                'price_per_share' => $pricePerShare,
                'amount_total' => $totalAmount,
                'currency' => $saleOffer->currency,
                'status' => 'completed',
                'metadata' => [
                    'secondary_market' => true,
                    'sale_offer_id' => $saleOffer->id,
                    'buyer_buyer_id' => $buyer->id,
                ],
            ]);

            // تسجيل عملية الشراء للمشتري
            ShareOperation::on('central')->create([
                'offer_id' => $saleOffer->original_offer_id,
                'tenant_id' => $saleOffer->originalOffer->tenant_id,
                'buyer_id' => $buyer->id,
                'type' => 'purchase',
                'shares_count' => $sharesCount,
                'price_per_share' => $pricePerShare,
                'amount_total' => $totalAmount,
                'currency' => $saleOffer->currency,
                'status' => 'completed',
                'metadata' => [
                    'secondary_market' => true,
                    'sale_offer_id' => $saleOffer->id,
                    'seller_buyer_id' => $saleOffer->seller_buyer_id,
                ],
            ]);

            // تحديث حالة عرض البيع
            $saleOffer->status = 'sold';
            $saleOffer->buyer_buyer_id = $buyer->id;
            $saleOffer->sold_price_per_share = $pricePerShare;
            $saleOffer->sold_at = now();
            $saleOffer->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم شراء الأسهم بنجاح من السوق الثانوي',
                'data' => [
                    'shares_count' => $sharesCount,
                    'price_per_share' => $pricePerShare,
                    'total_amount' => $totalAmount,
                    'currency' => $saleOffer->currency,
                    'offer_title' => $saleOffer->originalOffer->title_ar ?? $saleOffer->originalOffer->title,
                ],
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء عملية الشراء',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * ترجمة حالة العرض للعربية
     */
    private function getStatusArabic($status)
    {
        $statuses = [
            'active' => 'نشط',
            'sold' => 'تم البيع',
            'cancelled' => 'ملغي',
            'expired' => 'منتهي الصلاحية',
        ];

        return $statuses[$status] ?? $status;
    }
}
