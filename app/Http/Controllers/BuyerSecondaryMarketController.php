<?php

namespace App\Http\Controllers;

use App\Models\Central\Buyer;
use App\Models\Central\BuyerHolding;
use App\Models\Central\BuyerNotification;
use App\Models\Central\BuyerSaleOffer;
use App\Models\Central\ShareOperation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BuyerSecondaryMarketController extends Controller
{
    /**
     * عرض أسهم للبيع
     */
    public function createSaleOffer(Request $request)
    {
        $data = $request->validate([
            'holding_id' => 'required|integer|exists:central.buyer_holdings,id',
            'shares_count' => 'required|integer|min:1',
            'price_per_share' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:1000',
            'expires_in_days' => 'nullable|integer|min:1|max:365',
        ], [
            'holding_id.required' => 'يجب تحديد الأسهم المراد بيعها',
            'shares_count.required' => 'يجب تحديد عدد الأسهم',
            'price_per_share.required' => 'يجب تحديد السعر',
        ]);

        DB::beginTransaction();

        try {
            $user = Auth::guard('web')->user();
            $buyer = Buyer::on('central')->where('user_id', $user->id)->first();

            if (!$buyer) {
                DB::rollBack();
                return redirect()->back()->withErrors(['error' => 'يجب إنشاء حساب مشتري أولاً'])->withInput();
            }

            $holding = BuyerHolding::on('central')
                ->where('id', $data['holding_id'])
                ->where('buyer_id', $buyer->id)
                ->first();

            if (!$holding) {
                DB::rollBack();
                return redirect()->back()->withErrors(['error' => 'الأسهم المحددة غير موجودة أو لا تملكها'])->withInput();
            }

            // التحقق من توفر الأسهم
            $availableShares = $holding->shares_owned;
            $alreadyListed = BuyerSaleOffer::on('central')
                ->where('seller_buyer_id', $buyer->id)
                ->where('holding_id', $holding->id)
                ->where('status', 'active')
                ->sum('shares_count');
            
            $availableShares -= $alreadyListed;

            if ($availableShares < $data['shares_count']) {
                DB::rollBack();
                return redirect()->back()->withErrors([
                    'error' => 'عدد الأسهم المتاحة للبيع غير كافٍ. المتاح: ' . $availableShares . ' سهم، المعروض حالياً: ' . $alreadyListed . ' سهم'
                ])->withInput();
            }

            $expiresAt = null;
            if (!empty($data['expires_in_days'])) {
                $expiresAt = now()->addDays((int) $data['expires_in_days']);
            }

            $saleOffer = BuyerSaleOffer::on('central')->create([
                'seller_buyer_id' => $buyer->id,
                'holding_id' => $holding->id,
                'original_offer_id' => $holding->offer_id,
                'shares_count' => $data['shares_count'],
                'price_per_share' => $data['price_per_share'],
                'currency' => $holding->offer->currency ?? 'SAR',
                'status' => 'active',
                'description' => $data['description'] ?? null,
                'expires_at' => $expiresAt,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'تم عرض الأسهم للبيع بنجاح في السوق الثانوي');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء عرض الأسهم للبيع: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * عرض جميع عروض البيع في السوق الثانوي
     */
    public function index(Request $request)
    {
        $user = Auth::guard('web')->user();
        $buyer = null;
        
        if ($user) {
            $buyer = Buyer::on('central')->where('user_id', $user->id)->first();
        }

        $query = BuyerSaleOffer::on('central')
            ->where('status', 'active')
            ->where('shares_count', '>', 0)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->with(['seller', 'originalOffer']);

        // Hide user's own offers - cannot buy from yourself
        if ($buyer) {
            $query->where('seller_buyer_id', '!=', $buyer->id);
        }

        // Filters
        if ($request->filled('min_price')) {
            $query->where('price_per_share', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price_per_share', '<=', $request->max_price);
        }
        if ($request->filled('min_shares')) {
            $query->where('shares_count', '>=', $request->min_shares);
        }
        if ($request->filled('city')) {
            $query->whereHas('originalOffer', function($q) use ($request) {
                $q->where('city', $request->city);
            });
        }

        $allOffers = $query->orderBy('created_at', 'desc')->get();

        // Group by original offer (العقار)
        $groupedOffers = $allOffers->groupBy('original_offer_id')->map(function ($offers) {
            $firstOffer = $offers->first();
            return [
                'original_offer_id' => $firstOffer->original_offer_id,
                'offer_title' => $firstOffer->originalOffer->title ?? 'N/A',
                'offer_city' => $firstOffer->originalOffer->city ?? '',
                'cover_image' => $firstOffer->originalOffer->cover_image ?? null,
                'total_shares' => $offers->sum('shares_count'),
                'min_price' => $offers->min('price_per_share'),
                'max_price' => $offers->max('price_per_share'),
                'avg_price' => $offers->avg('price_per_share'),
                'offers_count' => $offers->count(),
                'currency' => $firstOffer->currency,
                'offers' => $offers, // All individual offers
            ];
        })->values();

        // Paginate manually
        $perPage = 12;
        $currentPage = $request->input('page', 1);
        $total = $groupedOffers->count();
        $grouped = $groupedOffers->forPage($currentPage, $perPage);
        
        $paginatedOffers = new \Illuminate\Pagination\LengthAwarePaginator(
            $grouped,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Get available cities for filter
        $cities = DB::connection('central')
            ->table('share_offers')
            ->whereIn('id', $allOffers->pluck('original_offer_id'))
            ->distinct()
            ->pluck('city')
            ->filter()
            ->sort()
            ->values();

        return view('buyer.secondary-market', [
            'groupedOffers' => $paginatedOffers,
            'cities' => $cities,
            'filters' => $request->only(['min_price', 'max_price', 'min_shares', 'city']),
        ]);
    }

    /**
     * عرض تفاصيل عرض معين مع المخططات
     */
    public function show($offerId)
    {
        $user = Auth::guard('web')->user();
        $buyer = null;
        
        if ($user) {
            $buyer = Buyer::on('central')->where('user_id', $user->id)->first();
        }
        
        // Get wallet balance
        $walletBalance = 0;
        if ($buyer) {
            $wallet = $buyer->getOrCreateWallet();
            $walletBalance = $wallet->balance ?? 0;
        }
        
        // Get all active offers for this property
        $offers = BuyerSaleOffer::on('central')
            ->where('original_offer_id', $offerId)
            ->where('status', 'active')
            ->where('shares_count', '>', 0)
            ->with(['seller', 'originalOffer'])
            ->orderBy('price_per_share', 'asc')
            ->get();

        if ($offers->isEmpty()) {
            return redirect()->route('buyer.secondary-market.index')
                ->withErrors(['error' => 'العرض غير متوفر']);
        }

        $offer = $offers->first()->originalOffer;

        // Get historical price data starting with first ACTUAL purchase (not offer price)
        // The chart should track from when buyers actually started purchasing/selling shares
        $priceHistory = DB::connection('central')
            ->table('share_operations')
            ->where('offer_id', $offerId)
            ->where('status', 'completed')
            ->whereIn('type', ['purchase', 'sell'])
            ->orderBy('created_at', 'asc')
            ->select('price_per_share', 'created_at', 'type', 'shares_count')
            ->get();

        // Calculate statistics
        $stats = [
            'total_offers' => $offers->count(),
            'total_shares_available' => $offers->sum('shares_count'),
            'min_price' => $offers->min('price_per_share'),
            'max_price' => $offers->max('price_per_share'),
            'avg_price' => $offers->avg('price_per_share'),
            'original_price' => $offer->price_per_share ?? 0,
        ];

        // Price trend
        if ($priceHistory->count() > 1) {
            $firstPrice = $priceHistory->first()->price_per_share;
            $lastPrice = $priceHistory->last()->price_per_share;
            $stats['price_change'] = $lastPrice - $firstPrice;
            $stats['price_change_percent'] = $firstPrice > 0 ? (($lastPrice - $firstPrice) / $firstPrice) * 100 : 0;
        } else {
            $stats['price_change'] = 0;
            $stats['price_change_percent'] = 0;
        }

        return view('buyer.secondary-market-details', compact('offers', 'offer', 'priceHistory', 'stats', 'walletBalance'));
    }

    /**
     * شراء من السوق الثانوي
     */
    public function buy(Request $request)
    {
        $data = $request->validate([
            'sale_offer_id' => 'required|integer|exists:central.buyer_sale_offers,id',
            'shares_count' => 'required|integer|min:1',
            'payment_method' => 'required|in:wallet,credit_card',
        ]);

        DB::beginTransaction();

        try {
            $user = Auth::guard('web')->user();
            $buyer = Buyer::on('central')->where('user_id', $user->id)->first();

            if (!$buyer) {
                DB::rollBack();
                return redirect()->back()->withErrors(['error' => 'يجب إنشاء حساب مشتري أولاً']);
            }

            // التحقق من طريقة الدفع
            if ($data['payment_method'] === 'credit_card') {
                DB::rollBack();
                return redirect()->back()->withErrors(['error' => 'الدفع بالبطاقة الائتمانية غير متاح حالياً. يرجى الدفع من المحفظة.']);
            }

            $saleOffer = BuyerSaleOffer::on('central')
                ->where('id', $data['sale_offer_id'])
                ->where('status', 'active')
                ->with(['seller', 'holding', 'originalOffer'])
                ->first();

            if (!$saleOffer) {
                DB::rollBack();
                return redirect()->back()->withErrors(['error' => 'العرض غير متاح أو تم بيعه مسبقاً']);
            }

            if ($saleOffer->seller_buyer_id == $buyer->id) {
                DB::rollBack();
                return redirect()->back()->withErrors(['error' => 'لا يمكنك شراء أسهمك الخاصة']);
            }

            if ($saleOffer->expires_at && $saleOffer->expires_at < now()) {
                $saleOffer->status = 'expired';
                $saleOffer->save();
                DB::rollBack();
                return redirect()->back()->withErrors(['error' => 'العرض منتهي الصلاحية']);
            }

            // التحقق من عدد الأسهم المطلوبة
            $requestedShares = $data['shares_count'];
            if ($requestedShares > $saleOffer->shares_count) {
                DB::rollBack();
                return redirect()->back()->withErrors(['error' => 'عدد الأسهم المطلوبة أكبر من المتاح']);
            }

            $sharesCount = $requestedShares;
            $pricePerShare = $saleOffer->price_per_share;
            $totalAmount = $sharesCount * $pricePerShare;

            // معالجة الدفع من محفظة المشتري
            $buyerWallet = $buyer->getOrCreateWallet();
            if (!$buyerWallet->hasSufficientBalance($totalAmount)) {
                DB::rollBack();
                return redirect()->back()->withErrors([
                    'error' => sprintf(
                        'رصيد المحفظة غير كافٍ. المطلوب: %s %s، المتاح: %s %s',
                        number_format($totalAmount, 2),
                        $saleOffer->currency,
                        number_format($buyerWallet->available_balance, 2),
                        $buyerWallet->currency
                    )
                ]);
            }

            // خصم المبلغ من محفظة المشتري
            $buyerTransaction = $buyerWallet->processPurchase(
                $totalAmount,
                $saleOffer->id,
                "شراء {$sharesCount} سهم من السوق الثانوي - {$saleOffer->originalOffer->title}"
            );

            // إضافة المبلغ لمحفظة البائع
            $sellerWallet = $saleOffer->seller->getOrCreateWallet();
            $sellerTransaction = $sellerWallet->processSale(
                $totalAmount,
                $saleOffer->id,
                "بيع {$sharesCount} سهم في السوق الثانوي - {$saleOffer->originalOffer->title}"
            );

            // ربط المعاملات ببعضها
            $buyerTransaction->update(['related_buyer_id' => $saleOffer->seller_buyer_id]);
            $sellerTransaction->update(['related_buyer_id' => $buyer->id]);

            // تحديث ممتلكات البائع
            $sellerHolding = $saleOffer->holding;
            $sellerHolding->shares_owned -= $sharesCount;
            
            if ($sellerHolding->shares_owned <= 0) {
                $sellerHolding->delete();
            } else {
                $sellerHolding->save();
            }

            // تحديث أو إنشاء ممتلكات المشتري
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
            $sellerOperation = ShareOperation::on('central')->create([
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
            $buyerOperation = ShareOperation::on('central')->create([
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

            // ربط المعاملات المالية بالعمليات
            $buyerTransaction->update(['share_operation_id' => $buyerOperation->id]);
            $sellerTransaction->update(['share_operation_id' => $sellerOperation->id]);

            // تحديث حالة عرض البيع
            $saleOffer->shares_count -= $sharesCount;
            
            $isFullySold = false;
            // إذا تم شراء كل الأسهم، تحديث الحالة إلى sold
            if ($saleOffer->shares_count <= 0) {
                $saleOffer->status = 'sold';
                $saleOffer->buyer_buyer_id = $buyer->id;
                $saleOffer->sold_price_per_share = $pricePerShare;
                $saleOffer->sold_at = now();
                $isFullySold = true;
            }
            // إذا بقيت أسهم، العرض يبقى active
            $saleOffer->save();

            // إنشاء تنبيه للبائع
            $notificationType = $isFullySold ? 'sale_completed' : 'partial_sale';
            $notificationTitle = $isFullySold 
                ? '✅ تم بيع جميع أسهمك!' 
                : '🔔 تم بيع جزء من أسهمك!';
            $notificationMessage = $isFullySold
                ? sprintf('تم بيع جميع الأسهم (%d سهم) من عرضك بسعر %s ريال للسهم. إجمالي المبلغ: %s ريال', 
                    $sharesCount, 
                    number_format($pricePerShare, 2), 
                    number_format($totalAmount, 2))
                : sprintf('تم بيع %d سهم من أصل %d من عرضك بسعر %s ريال للسهم. المبلغ: %s ريال. تبقى %d سهم', 
                    $sharesCount, 
                    ($sharesCount + $saleOffer->shares_count),
                    number_format($pricePerShare, 2), 
                    number_format($totalAmount, 2),
                    $saleOffer->shares_count);

            BuyerNotification::on('central')->create([
                'buyer_id' => $saleOffer->seller_buyer_id,
                'type' => $notificationType,
                'title' => $notificationTitle,
                'message' => $notificationMessage,
                'sale_offer_id' => $saleOffer->id,
                'share_operation_id' => $sellerOperation->id,
                'wallet_transaction_id' => $sellerTransaction->id,
                'metadata' => [
                    'shares_sold' => $sharesCount,
                    'price_per_share' => $pricePerShare,
                    'total_amount' => $totalAmount,
                    'buyer_buyer_id' => $buyer->id,
                    'buyer_name' => $buyer->name,
                    'property_title' => $saleOffer->originalOffer->title,
                ],
            ]);

            DB::commit();

            $message = $saleOffer->shares_count <= 0 
                ? 'تم شراء جميع الأسهم بنجاح من السوق الثانوي'
                : "تم شراء {$sharesCount} سهم بنجاح من السوق الثانوي. تبقى {$saleOffer->shares_count} سهم متاح";

            return redirect()->route('buyer.dashboard')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء عملية الشراء: ' . $e->getMessage()]);
        }
    }

    /**
     * إلغاء عرض بيع
     */
    public function cancelSaleOffer($id)
    {
        try {
            $user = Auth::guard('web')->user();
            $buyer = Buyer::on('central')->where('user_id', $user->id)->first();

            if (!$buyer) {
                return redirect()->back()->withErrors(['error' => 'حساب المشتري غير موجود']);
            }

            $saleOffer = BuyerSaleOffer::on('central')
                ->where('id', $id)
                ->where('seller_buyer_id', $buyer->id)
                ->where('status', 'active')
                ->first();

            if (!$saleOffer) {
                return redirect()->back()->withErrors(['error' => 'العرض غير موجود أو لا يمكن إلغاؤه']);
            }

            $saleOffer->status = 'cancelled';
            $saleOffer->save();

            return redirect()->back()->with('success', 'تم إلغاء العرض بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء إلغاء العرض: ' . $e->getMessage()]);
        }
    }
}
