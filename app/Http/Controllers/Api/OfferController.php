<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfferController extends Controller
{
    /**
     * عرض قائمة العروض مع الفلاتر
     */
    public function index(Request $request)
    {
        try {
            // بناء الاستعلام من قاعدة البيانات المركزية
            $query = DB::connection('central')->table('share_offers');

            // الفلاتر
            if ($request->filled('city')) {
                $query->where('city', $request->city);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('min_price')) {
                $query->where('price_per_share', '>=', $request->min_price);
            }

            if ($request->filled('max_price')) {
                $query->where('price_per_share', '<=', $request->max_price);
            }

            if ($request->filled('availability')) {
                if ($request->availability == 'available') {
                    $query->where('available_shares', '>', 0);
                } elseif ($request->availability == 'sold_out') {
                    $query->where('available_shares', '=', 0);
                }
            }

            // البحث
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('title_ar', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            }

            // الترتيب
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // التحقق من وجود أي نتائج
            $total = $query->count();

            // التصفح (Pagination)
            $perPage = $request->input('per_page', 15);
            $offers = $query->paginate($perPage);

            // إحصائيات إضافية
            $stats = [
                'total_offers' => DB::connection('central')->table('share_offers')->count(),
                'total_cities' => DB::connection('central')->table('share_offers')->distinct()->count('city'),
                'average_price' => DB::connection('central')->table('share_offers')->avg('price_per_share'),
            ];

            return response()->json([
                'success' => true,
                'data' => $offers->items(),
                'pagination' => [
                    'total' => $offers->total(),
                    'per_page' => $offers->perPage(),
                    'current_page' => $offers->currentPage(),
                    'last_page' => $offers->lastPage(),
                    'from' => $offers->firstItem(),
                    'to' => $offers->lastItem(),
                ],
                'stats' => $stats,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب العروض',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * عرض تفاصيل عرض محدد
     */
    public function show(Request $request, $id)
    {
        try {
            // العثور على العرض من قاعدة البيانات المركزية
            $offer = DB::connection('central')->table('share_offers')
                ->where('id', $id)
                ->first();

            if (! $offer) {
                return response()->json([
                    'success' => false,
                    'message' => 'العرض غير موجود',
                ], 404);
            }

            // حساب النسبة المباعة
            $soldPercentage = $offer->total_shares > 0
                ? round(($offer->sold_shares / $offer->total_shares) * 100, 2)
                : 0;

            // إضافة معلومات إضافية
            $offerData = (array) $offer;
            $offerData['sold_percentage'] = $soldPercentage;
            $offerData['is_available'] = $offer->available_shares > 0;
            $offerData['is_active'] = $offer->status === 'active';

            return response()->json([
                'success' => true,
                'data' => $offerData,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب تفاصيل العرض',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * الحصول على المدن المتاحة
     */
    public function cities(Request $request)
    {
        try {
            // الحصول على المدن من قاعدة البيانات المركزية
            $cities = DB::connection('central')->table('share_offers')
                ->select('city')
                ->distinct()
                ->whereNotNull('city')
                ->pluck('city');

            return response()->json([
                'success' => true,
                'data' => $cities,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب المدن',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * الحصول على إحصائيات العروض
     */
    public function statistics(Request $request)
    {
        try {
            // الإحصائيات من قاعدة البيانات المركزية
            $stats = [
                'total_offers' => DB::connection('central')->table('share_offers')->count(),
                'active_offers' => DB::connection('central')->table('share_offers')->where('status', 'active')->count(),
                'available_offers' => DB::connection('central')->table('share_offers')->where('available_shares', '>', 0)->count(),
                'total_cities' => DB::connection('central')->table('share_offers')->distinct()->count('city'),
                'average_price' => DB::connection('central')->table('share_offers')->avg('price_per_share'),
                'min_price' => DB::connection('central')->table('share_offers')->min('price_per_share'),
                'max_price' => DB::connection('central')->table('share_offers')->max('price_per_share'),
                'total_shares' => DB::connection('central')->table('share_offers')->sum('total_shares'),
                'sold_shares' => DB::connection('central')->table('share_offers')->sum('sold_shares'),
                'available_shares' => DB::connection('central')->table('share_offers')->sum('available_shares'),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الإحصائيات',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
