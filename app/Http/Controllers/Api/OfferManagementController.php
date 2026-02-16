<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\TenantHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OfferManagementController extends Controller
{
    /**
     * إضافة عرض جديد (يتطلب مصادقة)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tenant_domain' => 'required|string',
            'title' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'country' => 'nullable|string',
            'city' => 'required|string',
            'address' => 'nullable|string',
            'total_shares' => 'required|integer|min:1',
            'price_per_share' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'status' => 'nullable|string|in:active,inactive,pending',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
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
            $tenantDomain = $request->tenant_domain;

            // العثور على Tenant وإعداد الاتصال
            $tenant = TenantHelper::setupTenantConnection($tenantDomain);

            if (!$tenant) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'النطاق غير موجود'
                ], 404);
            }

            // إنشاء العرض في قاعدة البيانات الفرعية (Tenant)
            $offerId = DB::connection('tenant')->table('share_offers')->insertGetId([
                'title' => $request->title,
                'title_ar' => $request->title_ar,
                'description' => $request->description,
                'description_ar' => $request->description_ar,
                'country' => $request->country ?? 'Saudi Arabia',
                'city' => $request->city,
                'address' => $request->address,
                'total_shares' => $request->total_shares,
                'available_shares' => $request->total_shares,
                'sold_shares' => 0,
                'price_per_share' => $request->price_per_share,
                'currency' => $request->currency ?? 'SAR',
                'status' => $request->status ?? 'active',
                'starts_at' => $request->starts_at,
                'ends_at' => $request->ends_at,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // نسخ العرض إلى قاعدة البيانات المركزية أيضاً
            $centralOfferId = DB::connection('central')->table('share_offers')->insertGetId([
                'tenant_id' => $tenant->TenantID,
                'title' => $request->title,
                'title_ar' => $request->title_ar,
                'description' => $request->description,
                'description_ar' => $request->description_ar,
                'country' => $request->country ?? 'Saudi Arabia',
                'city' => $request->city,
                'address' => $request->address,
                'total_shares' => $request->total_shares,
                'available_shares' => $request->total_shares,
                'sold_shares' => 0,
                'price_per_share' => $request->price_per_share,
                'currency' => $request->currency ?? 'SAR',
                'status' => $request->status ?? 'active',
                'starts_at' => $request->starts_at,
                'ends_at' => $request->ends_at,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // تحديث العرض في Tenant بـ central_offer_id
            DB::connection('tenant')->table('share_offers')
                ->where('id', $offerId)
                ->update(['central_offer_id' => $centralOfferId]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة العرض بنجاح',
                'data' => [
                    'tenant_offer_id' => $offerId,
                    'central_offer_id' => $centralOfferId,
                    'title' => $request->title_ar ?? $request->title,
                    'city' => $request->city,
                    'total_shares' => $request->total_shares,
                    'price_per_share' => $request->price_per_share,
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة العرض',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث عرض موجود
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tenant_domain' => 'required|string',
            'title' => 'sometimes|string|max:255',
            'title_ar' => 'sometimes|string|max:255',
            'city' => 'sometimes|string',
            'price_per_share' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|string|in:active,inactive,pending',
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
            $tenantDomain = $request->tenant_domain;

            // العثور على Tenant وإعداد الاتصال
            $tenant = TenantHelper::setupTenantConnection($tenantDomain);

            if (!$tenant) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'النطاق غير موجود'
                ], 404);
            }

            // التحقق من وجود العرض في قاعدة البيانات الفرعية
            $offer = DB::connection('tenant')->table('share_offers')->where('id', $id)->first();

            if (!$offer) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'العرض غير موجود'
                ], 404);
            }

            // تحديث البيانات
            $updateData = array_filter([
                'title' => $request->title,
                'title_ar' => $request->title_ar,
                'city' => $request->city,
                'price_per_share' => $request->price_per_share,
                'status' => $request->status,
                'updated_at' => now(),
            ], function($value) { return $value !== null; });

            // تحديث في قاعدة البيانات الفرعية
            DB::connection('tenant')->table('share_offers')
                ->where('id', $id)
                ->update($updateData);

            // تحديث في قاعدة البيانات المركزية أيضاً
            if ($offer->central_offer_id) {
                DB::connection('central')->table('share_offers')
                    ->where('id', $offer->central_offer_id)
                    ->update($updateData);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث العرض بنجاح',
                'data' => [
                    'offer_id' => $id,
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث العرض',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف عرض
     */
    public function destroy(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tenant_domain' => 'required|string',
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
            $tenantDomain = $request->tenant_domain;

            // العثور على Tenant وإعداد الاتصال
            $tenant = TenantHelper::setupTenantConnection($tenantDomain);

            if (!$tenant) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'النطاق غير موجود'
                ], 404);
            }

            // التحقق من وجود العرض
            $offer = DB::connection('tenant')->table('share_offers')->where('id', $id)->first();

            if (!$offer) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'العرض غير موجود'
                ], 404);
            }

            // حذف من قاعدة البيانات الفرعية
            DB::connection('tenant')->table('share_offers')->where('id', $id)->delete();

            // حذف من قاعدة البيانات المركزية
            if ($offer->central_offer_id) {
                DB::connection('central')->table('share_offers')
                    ->where('id', $offer->central_offer_id)
                    ->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف العرض بنجاح'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف العرض',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
