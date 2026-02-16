<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Central\ShareOperation;
use App\Models\Central\ShareOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuyerController extends Controller
{
    /**
     * لوحة تحكم المشتري مع الإحصائيات
     */
    public function dashboard(Request $request)
    {
        try {
            $user = $request->user();
            $tenantId = $request->header('X-Tenant-Id') ?? $request->input('tenant_id');

            // إحصائيات المشتري
            $completedOperations = ShareOperation::where('buyer_id', $user->id)
                ->where('status', 'completed')
                ->when($tenantId, function($q) use ($tenantId) {
                    return $q->where('tenant_id', $tenantId);
                })
                ->get();

            $pendingOperations = ShareOperation::where('buyer_id', $user->id)
                ->where('status', 'pending')
                ->when($tenantId, function($q) use ($tenantId) {
                    return $q->where('tenant_id', $tenantId);
                })
                ->get();

            $totalSpent = $completedOperations->sum('amount_total');
            $totalShares = $completedOperations->sum('shares_count');

            // الأسهم المملوكة حسب النوع
            $sharesByType = $completedOperations->groupBy('type')->map(function($operations) {
                return [
                    'count' => $operations->sum('shares_count'),
                    'total_amount' => $operations->sum('amount_total'),
                ];
            });

            // العمليات حسب الحالة
            $operationsByStatus = ShareOperation::where('buyer_id', $user->id)
                ->when($tenantId, function($q) use ($tenantId) {
                    return $q->where('tenant_id', $tenantId);
                })
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status');

            $stats = [
                'total_operations' => ShareOperation::where('buyer_id', $user->id)
                    ->when($tenantId, function($q) use ($tenantId) {
                        return $q->where('tenant_id', $tenantId);
                    })
                    ->count(),
                'completed_operations' => $completedOperations->count(),
                'pending_operations' => $pendingOperations->count(),
                'total_shares_owned' => $totalShares,
                'total_spent' => $totalSpent,
                'shares_by_type' => $sharesByType,
                'operations_by_status' => $operationsByStatus,
            ];

            // أحدث العمليات
            $recentOperations = ShareOperation::where('buyer_id', $user->id)
                ->when($tenantId, function($q) use ($tenantId) {
                    return $q->where('tenant_id', $tenantId);
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($operation) {
                    return [
                        'id' => $operation->id,
                        'type' => $operation->type,
                        'status' => $operation->status,
                        'shares_count' => $operation->shares_count,
                        'amount_total' => $operation->amount_total,
                        'currency' => $operation->currency,
                        'external_reference' => $operation->external_reference,
                        'created_at' => $operation->created_at->format('Y-m-d H:i:s'),
                        'offer_title' => $operation->metadata['offer_title'] ?? null,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $stats,
                    'recent_operations' => $recentOperations,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب بيانات لوحة التحكم',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * الحصول على جميع عمليات المشتري
     */
    public function operations(Request $request)
    {
        try {
            $user = $request->user();
            $tenantId = $request->header('X-Tenant-Id') ?? $request->input('tenant_id');

            $query = ShareOperation::where('buyer_id', $user->id)
                ->when($tenantId, function($q) use ($tenantId) {
                    return $q->where('tenant_id', $tenantId);
                });

            // الفلاتر
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('from_date')) {
                $query->whereDate('created_at', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('created_at', '<=', $request->to_date);
            }

            // الترتيب
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // التصفح
            $perPage = $request->input('per_page', 15);
            $operations = $query->paginate($perPage);

            // إضافة معلومات العروض
            $operationsData = $operations->getCollection()->map(function($operation) {
                $offer = ShareOffer::find($operation->offer_id);
                
                return [
                    'id' => $operation->id,
                    'type' => $operation->type,
                    'status' => $operation->status,
                    'shares_count' => $operation->shares_count,
                    'price_per_share' => $operation->price_per_share,
                    'amount_total' => $operation->amount_total,
                    'currency' => $operation->currency,
                    'external_reference' => $operation->external_reference,
                    'payment_id' => $operation->payment_id,
                    'created_at' => $operation->created_at->format('Y-m-d H:i:s'),
                    'offer' => $offer ? [
                        'id' => $offer->id,
                        'title' => $offer->title_ar ?? $offer->title,
                        'city' => $offer->city,
                        'status' => $offer->status,
                    ] : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $operationsData,
                'pagination' => [
                    'total' => $operations->total(),
                    'per_page' => $operations->perPage(),
                    'current_page' => $operations->currentPage(),
                    'last_page' => $operations->lastPage(),
                    'from' => $operations->firstItem(),
                    'to' => $operations->lastItem(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب العمليات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * الحصول على تفاصيل عملية محددة
     */
    public function operationDetails(Request $request, $operationId)
    {
        try {
            $user = $request->user();

            $operation = ShareOperation::where('id', $operationId)
                ->where('buyer_id', $user->id)
                ->first();

            if (!$operation) {
                return response()->json([
                    'success' => false,
                    'message' => 'العملية غير موجودة أو غير مصرح لك بها'
                ], 404);
            }

            // معلومات العرض
            $offer = ShareOffer::find($operation->offer_id);

            $data = [
                'id' => $operation->id,
                'type' => $operation->type,
                'status' => $operation->status,
                'shares_count' => $operation->shares_count,
                'price_per_share' => $operation->price_per_share,
                'amount_total' => $operation->amount_total,
                'currency' => $operation->currency,
                'external_reference' => $operation->external_reference,
                'payment_id' => $operation->payment_id,
                'metadata' => $operation->metadata,
                'created_at' => $operation->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $operation->updated_at->format('Y-m-d H:i:s'),
                'offer' => $offer ? [
                    'id' => $offer->id,
                    'title' => $offer->title_ar ?? $offer->title,
                    'description' => $offer->description_ar ?? $offer->description,
                    'city' => $offer->city,
                    'address' => $offer->address,
                    'status' => $offer->status,
                    'price_per_share' => $offer->price_per_share,
                    'cover_image' => $offer->cover_image,
                ] : null,
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب تفاصيل العملية',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * الأسهم المملوكة للمشتري
     */
    public function myShares(Request $request)
    {
        try {
            $user = $request->user();
            $tenantId = $request->header('X-Tenant-Id') ?? $request->input('tenant_id');

            // الحصول على جميع عمليات الشراء المكتملة
            $operations = ShareOperation::where('buyer_id', $user->id)
                ->where('type', 'purchase')
                ->where('status', 'completed')
                ->when($tenantId, function($q) use ($tenantId) {
                    return $q->where('tenant_id', $tenantId);
                })
                ->get();

            // تجميع الأسهم حسب العرض
            $sharesByOffer = $operations->groupBy('offer_id')->map(function($ops, $offerId) {
                $totalShares = $ops->sum('shares_count');
                $totalInvested = $ops->sum('amount_total');
                $offer = ShareOffer::find($offerId);

                return [
                    'offer_id' => $offerId,
                    'offer_title' => $offer ? ($offer->title_ar ?? $offer->title) : null,
                    'offer_city' => $offer ? $offer->city : null,
                    'total_shares' => $totalShares,
                    'total_invested' => $totalInvested,
                    'average_price' => $totalShares > 0 ? round($totalInvested / $totalShares, 2) : 0,
                    'current_price' => $offer ? $offer->price_per_share : 0,
                    'operations_count' => $ops->count(),
                ];
            })->values();

            return response()->json([
                'success' => true,
                'data' => $sharesByOffer,
                'summary' => [
                    'total_offers' => $sharesByOffer->count(),
                    'total_shares' => $sharesByOffer->sum('total_shares'),
                    'total_invested' => $sharesByOffer->sum('total_invested'),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الأسهم المملوكة',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
