<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\TenantHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class OfferManagementController extends Controller
{
    /**
     * رفع صورة وإرجاع المسار
     */
    private function uploadImage($image, $path = 'offers')
    {
        if (!$image) {
            return null;
        }

        try {
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $imagePath = $image->storeAs($path, $filename, 'public');
            
            // إرجاع المسار بدون storage/ ليتوافق مع Web Controller
            return $imagePath;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * إضافة عرض جديد (يتطلب مصادقة)
     */
    public function store(Request $request)
    {
        // التحقق من وجود ملف الصورة قبل الـ validation
        if (!$request->hasFile('cover_image')) {
            return response()->json([
                'success' => false,
                'message' => '❌ صورة الغلاف مطلوبة - لم يتم إرفاق أي صورة',
                'errors' => [
                    'cover_image' => [
                        'صورة الغلاف مطلوبة عند إنشاء عرض جديد',
                        'تأكد من استخدام form-data في Postman وليس raw/JSON',
                        'تأكد من اختيار نوع File لحقل cover_image',
                        'اسم الحقل يجب أن يكون: cover_image'
                    ]
                ],
                'help' => [
                    'body_type' => 'form-data',
                    'field_name' => 'cover_image',
                    'field_type' => 'File',
                    'allowed_formats' => ['jpeg', 'jpg', 'png', 'gif', 'webp'],
                    'max_size' => '5MB'
                ]
            ], 422);
        }

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
            'cover_image' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'images' => 'nullable|array|max:14', // صور إضافية (اختيارية) بحد أقصى 14 صورة إضافية
            'images.*' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ], [
            // رسائل الصورة
            'cover_image.required' => '❌ صورة الغلاف مطلوبة - يجب إرفاق صورة واحدة على الأقل',
            'cover_image.image' => '❌ الملف المرفق ليس صورة صحيحة',
            'cover_image.mimes' => '❌ صيغة الصورة غير مدعومة. الصيغ المسموحة: jpeg, jpg, png, gif, webp',
            'cover_image.max' => '❌ حجم الصورة كبير جداً. الحد الأقصى: 5 ميجابايت',
            
            // رسائل الصور الإضافية
            'images.array' => 'الصور الإضافية يجب أن تكون في شكل مصفوفة',
            'images.max' => '❌ الحد الأقصى 14 صورة إضافية (بالإضافة إلى صورة الغلاف = 15 صورة إجمالي)',
            'images.*.image' => '❌ أحد الملفات الإضافية ليس صورة صحيحة',
            'images.*.mimes' => '❌ صيغة إحدى الصور الإضافية غير مدعومة',
            'images.*.max' => '❌ حجم إحدى الصور الإضافية كبير جداً',
            
            // رسائل الحقول الأخرى
            'tenant_domain.required' => 'نطاق المستأجر مطلوب',
            'title.required' => 'عنوان العرض مطلوب',
            'title_ar.required' => 'عنوان العرض بالعربية مطلوب',
            'city.required' => 'المدينة مطلوبة',
            'total_shares.required' => 'عدد الأسهم الكلي مطلوب',
            'total_shares.min' => 'عدد الأسهم يجب أن يكون 1 على الأقل',
            'price_per_share.required' => 'سعر السهم مطلوب',
            'price_per_share.min' => 'سعر السهم يجب أن يكون صفر أو أكثر',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '❌ خطأ في البيانات المدخلة - يرجى التحقق من الحقول المطلوبة',
                'errors' => $validator->errors(),
                'validation_failed_fields' => array_keys($validator->errors()->messages())
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

            // رفع الصورة الرئيسية (إلزامية)
            $coverImagePath = $this->uploadImage($request->file('cover_image'));
            
            if (!$coverImagePath) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => '❌ فشل رفع الصورة إلى السيرفر',
                    'errors' => [
                        'cover_image' => [
                            'حدث خطأ أثناء حفظ الصورة في مجلد التخزين',
                            'تأكد من أن الصورة صالحة وليست تالفة',
                            'تأكد من أن حجم الصورة لا يتجاوز 5MB',
                            'تأكد من صلاحيات مجلد storage/app/public/offers'
                        ]
                    ],
                    'technical_details' => 'Storage upload failed'
                ], 500);
            }

            // تجهيز مصفوفة media تحتوي على جميع الصور
            $allImages = [$coverImagePath]; // نبدأ بصورة الغلاف
            
            // رفع الصور الإضافية إن وجدت
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = $this->uploadImage($image);
                    if ($imagePath) {
                        $allImages[] = $imagePath;
                    }
                }
            }

            // تحويل مصفوفة الصور إلى JSON
            $mediaJson = json_encode($allImages);

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
                'cover_image' => $coverImagePath,
                'media' => $mediaJson, // حفظ جميع الصور
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
                'cover_image' => $coverImagePath,
                'media' => $mediaJson, // حفظ جميع الصور
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
                    'cover_image' => asset('storage/' . $coverImagePath),
                    'total_images_uploaded' => count($allImages),
                    'all_images' => array_map(function($img) {
                        return asset('storage/' . $img);
                    }, $allImages),
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
            'cover_image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
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

            // التحقق من وجود صورة في العرض
            // إذا لم يكن هناك صورة في الطلب الجديد ولا يوجد صورة قديمة، نرفض التحديث
            if (!$request->hasFile('cover_image') && empty($offer->cover_image)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'العرض يجب أن يحتوي على صورة غلاف واحدة على الأقل'
                ], 422);
            }

            // رفع صورة جديدة إذا وُجدت
            $coverImagePath = null;
            if ($request->hasFile('cover_image')) {
                // حذف الصورة القديمة إن وُجدت
                if ($offer->cover_image && Storage::disk('public')->exists(str_replace('storage/', '', $offer->cover_image))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $offer->cover_image));
                }
                
                $coverImagePath = $this->uploadImage($request->file('cover_image'));
                
                if (!$coverImagePath) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'فشل رفع الصورة الجديدة'
                    ], 500);
                }
            }

            // تحديث البيانات
            $updateData = array_filter([
                'title' => $request->title,
                'title_ar' => $request->title_ar,
                'city' => $request->city,
                'price_per_share' => $request->price_per_share,
                'status' => $request->status,
                'cover_image' => $coverImagePath,
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

            // Check central database for restrictions before deletion
            if ($offer->central_offer_id) {
                $centralOfferId = $offer->central_offer_id;
                
                // Check if there are active secondary market offers
                $hasActiveSales = DB::connection('central')
                    ->table('buyer_sale_offers')
                    ->where('original_offer_id', $centralOfferId)
                    ->where('status', 'active')
                    ->exists();
                
                if ($hasActiveSales) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'لا يمكن حذف هذا العرض لأنه يحتوي على عروض بيع نشطة في السوق الثانوي'
                    ], 422);
                }
                
                // Check if there are any buyer holdings
                $hasBuyerHoldings = DB::connection('central')
                    ->table('buyer_holdings')
                    ->where('offer_id', $centralOfferId)
                    ->where('shares_count', '>', 0)
                    ->exists();
                
                if ($hasBuyerHoldings) {
                    // Instead of deleting, mark as cancelled
                    DB::connection('central')->table('share_offers')
                        ->where('id', $centralOfferId)
                        ->update(['status' => 'cancelled', 'updated_at' => now()]);
                    \Log::info('Central offer marked as cancelled via API', ['central_offer_id' => $centralOfferId]);
                } else {
                    // Safe to delete from central
                    DB::connection('central')->table('share_offers')
                        ->where('id', $centralOfferId)
                        ->delete();
                    \Log::info('Central offer deleted via API', ['central_offer_id' => $centralOfferId]);
                }
            }

            // حذف من قاعدة البيانات الفرعية
            DB::connection('tenant')->table('share_offers')->where('id', $id)->delete();

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

    /**
     * رفع صورة غلاف لعرض موجود
     */
    public function uploadCoverImage(Request $request, $id)
    {
        // التحقق من وجود ملف الصورة قبل الـ validation
        if (!$request->hasFile('cover_image')) {
            return response()->json([
                'success' => false,
                'message' => '❌ صورة الغلاف مطلوبة - لم يتم إرفاق أي صورة',
                'errors' => [
                    'cover_image' => [
                        'يجب إرفاق صورة لتحديث صورة الغلاف',
                        'استخدم form-data في Postman وليس raw/JSON',
                        'اسم الحقل: cover_image',
                        'نوع الحقل: File'
                    ]
                ]
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'tenant_domain' => 'required|string',
            'cover_image' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ], [
            'tenant_domain.required' => 'نطاق المستأجر مطلوب',
            'cover_image.required' => '❌ صورة الغلاف مطلوبة',
            'cover_image.image' => '❌ الملف المرفق ليس صورة صحيحة',
            'cover_image.mimes' => '❌ صيغة الصورة غير مدعومة. استخدم: jpeg, jpg, png, gif, webp',
            'cover_image.max' => '❌ حجم الصورة كبير جداً. الحد الأقصى: 5MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '❌ خطأ في البيانات المدخلة',
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

            // حذف الصورة القديمة إن وُجدت
            if ($offer->cover_image && Storage::disk('public')->exists(str_replace('storage/', '', $offer->cover_image))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $offer->cover_image));
            }

            // رفع الصورة الجديدة
            $coverImagePath = $this->uploadImage($request->file('cover_image'));

            if (!$coverImagePath) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'فشل رفع الصورة'
                ], 500);
            }

            // تحديث مسار الصورة في قاعدة البيانات الفرعية
            DB::connection('tenant')->table('share_offers')
                ->where('id', $id)
                ->update([
                    'cover_image' => $coverImagePath,
                    'updated_at' => now()
                ]);

            // تحديث في قاعدة البيانات المركزية أيضاً
            if ($offer->central_offer_id) {
                DB::connection('central')->table('share_offers')
                    ->where('id', $offer->central_offer_id)
                    ->update([
                        'cover_image' => $coverImagePath,
                        'updated_at' => now()
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم رفع الصورة بنجاح',
                'data' => [
                    'offer_id' => $id,
                    'cover_image' => asset('storage/' . $coverImagePath),
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء رفع الصورة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * إضافة صور متعددة لعرض موجود
     */
    public function uploadMultipleImages(Request $request, $id)
    {
        // التحقق من وجود ملفات الصور قبل الـ validation
        if (!$request->hasFile('images')) {
            return response()->json([
                'success' => false,
                'message' => '❌ لم يتم إرفاق أي صور',
                'errors' => [
                    'images' => [
                        'يجب إرفاق صورة واحدة على الأقل',
                        'استخدم form-data في Postman',
                        'اسم الحقل: images[]',
                        'نوع الحقل: File (يمكن اختيار أكثر من صورة)',
                        'الحد الأقصى: 15 صورة'
                    ]
                ],
                'help' => [
                    'body_type' => 'form-data',
                    'field_name' => 'images[]',
                    'field_type' => 'File',
                    'multiple' => true,
                    'max_images' => 15,
                    'max_size_per_image' => '5MB'
                ]
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'tenant_domain' => 'required|string',
            'images' => 'required|array|min:1|max:15',
            'images.*' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ], [
            'tenant_domain.required' => 'نطاق المستأجر مطلوب',
            'images.required' => '❌ يجب إرفاق صورة واحدة على الأقل',
            'images.array' => '❌ يجب أن تكون الصور في شكل مصفوفة (images[])',
            'images.min' => '❌ يجب إرفاق صورة واحدة على الأقل',
            'images.max' => '❌ الحد الأقصى 15 صورة في الطلب الواحد',
            'images.*.required' => '❌ أحد الملفات المرفقة غير صالح',
            'images.*.image' => '❌ أحد الملفات المرفقة ليس صورة صحيحة',
            'images.*.mimes' => '❌ صيغة الصورة غير مدعومة. استخدم: jpeg, jpg, png, gif, webp',
            'images.*.max' => '❌ حجم إحدى الصور كبير جداً. الحد الأقصى: 5MB لكل صورة',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '❌ خطأ في البيانات المدخلة - تحقق من الصور المرفقة',
                'errors' => $validator->errors(),
                'images_received' => count($request->file('images', []))
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

            // الحصول على الصور الحالية
            $existingMedia = $offer->media ? json_decode($offer->media, true) : [];
            if (!is_array($existingMedia)) {
                $existingMedia = [];
            }

            // رفع الصور الجديدة
            $uploadedImages = [];
            foreach ($request->file('images') as $image) {
                $imagePath = $this->uploadImage($image);
                if ($imagePath) {
                    $uploadedImages[] = $imagePath;
                }
            }

            if (empty($uploadedImages)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'فشل رفع الصور'
                ], 500);
            }

            // دمج الصور الجديدة مع القديمة (حد أقصى 15)
            $allMedia = array_merge($existingMedia, $uploadedImages);
            $allMedia = array_slice($allMedia, 0, 15); // الحد الأقصى 15 صورة

            // تحديث cover_image إذا لم يكن موجوداً
            $coverImage = $offer->cover_image ?: $allMedia[0];

            // تحديث في قاعدة البيانات الفرعية
            DB::connection('tenant')->table('share_offers')
                ->where('id', $id)
                ->update([
                    'media' => json_encode($allMedia),
                    'cover_image' => $coverImage,
                    'updated_at' => now()
                ]);

            // تحديث في قاعدة البيانات المركزية أيضاً
            if ($offer->central_offer_id) {
                DB::connection('central')->table('share_offers')
                    ->where('id', $offer->central_offer_id)
                    ->update([
                        'media' => json_encode($allMedia),
                        'cover_image' => $coverImage,
                        'updated_at' => now()
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم رفع الصور بنجاح',
                'data' => [
                    'offer_id' => $id,
                    'uploaded_count' => count($uploadedImages),
                    'total_images' => count($allMedia),
                    'images' => array_map(fn($path) => asset('storage/' . $path), $allMedia),
                    'cover_image' => asset('storage/' . $coverImage),
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء رفع الصور',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
