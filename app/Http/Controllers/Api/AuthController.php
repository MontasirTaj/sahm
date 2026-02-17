<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * تسجيل مستخدم جديد (مشتري)
     * المشتري لا يتبع لأي tenant - يسجل في قاعدة البيانات المركزية
     * يتم إنشاء سجل في جدول users وجدول buyers
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:central.users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:30',
            'national_id' => 'nullable|string|max:50',
        ], [
            'name.required' => 'الاسم الكامل مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مسجل مسبقاً',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'كلمة المرور وتأكيدها غير متطابقين',
            'phone.max' => 'رقم الجوال طويل جداً',
            'national_id.max' => 'رقم الهوية طويل جداً',
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
            // إنشاء المستخدم في قاعدة البيانات المركزية
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // إنشاء سجل المشتري في جدول buyers
            $buyer = \App\Models\Central\Buyer::on('central')->create([
                'user_id' => $user->id,
                'full_name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'national_id' => $request->national_id,
                'kyc_status' => 'unverified',
            ]);

            DB::commit();

            // إنشاء Token
            $token = $user->createToken('mobile-app', ['buyer'])->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'تم التسجيل بنجاح',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'avatar' => $user->avatar,
                    ],
                    'buyer' => [
                        'id' => $buyer->id,
                        'full_name' => $buyer->full_name,
                        'phone' => $buyer->phone,
                        'national_id' => $buyer->national_id,
                        'kyc_status' => $buyer->kyc_status,
                    ],
                    'token' => $token,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التسجيل',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * تسجيل الدخول
     * المشتري لا يتبع لأي tenant - تسجيل الدخول من قاعدة البيانات المركزية
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // العثور على المستخدم في قاعدة البيانات المركزية
            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات الدخول غير صحيحة',
                ], 401);
            }

            // إنشاء Token
            $token = $user->createToken('mobile-app', ['buyer'])->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الدخول بنجاح',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'avatar' => $user->avatar,
                    ],
                    'token' => $token,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تسجيل الدخول',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * تسجيل الخروج
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الخروج بنجاح',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تسجيل الخروج',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * الحصول على بيانات المستخدم الحالي
     */
    /**
     * عرض بيانات المستخدم مع profile كامل للمشتري
     */
    public function profile(Request $request)
    {
        try {
            $user = $request->user();

            // جلب بيانات المشتري إذا كان موجوداً
            $buyer = \App\Models\Central\Buyer::on('central')
                ->where('user_id', $user->id)
                ->first();

            // البيانات الأساسية للمستخدم
            $data = [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                    'created_at' => $user->created_at->format('Y-m-d'),
                ],
            ];

            // إذا كان لديه حساب مشتري، نضيف البيانات الكاملة
            if ($buyer) {
                // بيانات المشتري الأساسية
                $data['buyer'] = [
                    'id' => $buyer->id,
                    'full_name' => $buyer->full_name,
                    'email' => $buyer->email,
                    'phone' => $buyer->phone,
                    'national_id' => $buyer->national_id,
                    'date_of_birth' => $buyer->date_of_birth ? $buyer->date_of_birth->format('Y-m-d') : null,
                    'country' => $buyer->country,
                    'city' => $buyer->city,
                    'address' => $buyer->address,
                    'kyc_status' => $buyer->kyc_status,
                    'metadata' => $buyer->metadata,
                    'created_at' => $buyer->created_at->format('Y-m-d H:i:s'),
                ];

                // جلب جميع العمليات (شراء، بيع، تحويل)
                $operations = \App\Models\Central\ShareOperation::on('central')
                    ->where('buyer_id', $buyer->id)
                    ->with('offer:id,title,title_ar,price_per_share,currency,cover_image')
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(function ($op) {
                        return [
                            'id' => $op->id,
                            'offer_id' => $op->offer_id,
                            'offer_title' => $op->offer->title_ar ?? $op->offer->title ?? null,
                            'offer_cover_image' => $op->offer && $op->offer->cover_image 
                                ? asset('storage/' . $op->offer->cover_image) 
                                : null,
                            'type' => $op->type, // purchase, sell, transfer
                            'type_ar' => $op->type === 'purchase' ? 'شراء' : ($op->type === 'sell' ? 'بيع' : 'تحويل'),
                            'shares_count' => $op->shares_count,
                            'price_per_share' => $op->price_per_share,
                            'amount_total' => $op->amount_total,
                            'currency' => $op->currency,
                            'status' => $op->status,
                            'status_ar' => $op->status === 'completed' ? 'مكتملة' : ($op->status === 'pending' ? 'قيد المعالجة' : 'ملغية'),
                            'created_at' => $op->created_at->format('Y-m-d H:i:s'),
                        ];
                    });

                $data['operations'] = $operations;

                // جلب الأسهم المملوكة
                $holdings = \App\Models\Central\BuyerHolding::on('central')
                    ->where('buyer_id', $buyer->id)
                    ->with('offer:id,title,title_ar,price_per_share,currency,cover_image,status')
                    ->get()
                    ->map(function ($holding) {
                        return [
                            'id' => $holding->id,
                            'offer_id' => $holding->offer_id,
                            'offer_title' => $holding->offer->title_ar ?? $holding->offer->title ?? null,
                            'offer_cover_image' => $holding->offer && $holding->offer->cover_image 
                                ? asset('storage/' . $holding->offer->cover_image) 
                                : null,
                            'offer_status' => $holding->offer->status ?? null,
                            'shares_owned' => $holding->shares_owned,
                            'avg_price_per_share' => $holding->avg_price_per_share,
                            'current_price_per_share' => $holding->offer->price_per_share ?? null,
                            'currency' => $holding->offer->currency ?? 'USD',
                            'total_investment' => $holding->shares_owned * $holding->avg_price_per_share,
                            'current_value' => $holding->shares_owned * ($holding->offer->price_per_share ?? $holding->avg_price_per_share),
                            'last_transaction_at' => $holding->last_transaction_at 
                                ? $holding->last_transaction_at->format('Y-m-d H:i:s') 
                                : null,
                        ];
                    });

                $data['holdings'] = $holdings;

                // جلب عروض البيع النشطة (السوق الثانوي)
                $saleOffers = \App\Models\Central\BuyerSaleOffer::on('central')
                    ->where('seller_buyer_id', $buyer->id)
                    ->with('originalOffer:id,title,title_ar,cover_image')
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(function ($saleOffer) {
                        return [
                            'id' => $saleOffer->id,
                            'offer_title' => $saleOffer->originalOffer->title_ar ?? $saleOffer->originalOffer->title,
                            'offer_cover_image' => $saleOffer->originalOffer->cover_image 
                                ? asset('storage/' . $saleOffer->originalOffer->cover_image) 
                                : null,
                            'shares_count' => $saleOffer->shares_count,
                            'price_per_share' => $saleOffer->price_per_share,
                            'total_value' => $saleOffer->shares_count * $saleOffer->price_per_share,
                            'currency' => $saleOffer->currency,
                            'status' => $saleOffer->status,
                            'status_ar' => $saleOffer->status === 'active' ? 'نشط' : 
                                          ($saleOffer->status === 'sold' ? 'تم البيع' : 
                                          ($saleOffer->status === 'cancelled' ? 'ملغي' : 'منتهي')),
                            'expires_at' => $saleOffer->expires_at ? $saleOffer->expires_at->format('Y-m-d H:i:s') : null,
                            'sold_at' => $saleOffer->sold_at ? $saleOffer->sold_at->format('Y-m-d H:i:s') : null,
                            'created_at' => $saleOffer->created_at->format('Y-m-d H:i:s'),
                        ];
                    });

                $data['sale_offers'] = $saleOffers;

                // إحصائيات شاملة
                $totalPurchases = \App\Models\Central\ShareOperation::on('central')
                    ->where('buyer_id', $buyer->id)
                    ->where('type', 'purchase')
                    ->where('status', 'completed')
                    ->count();

                $totalInvested = \App\Models\Central\ShareOperation::on('central')
                    ->where('buyer_id', $buyer->id)
                    ->where('type', 'purchase')
                    ->where('status', 'completed')
                    ->sum('amount_total');

                $totalSales = \App\Models\Central\ShareOperation::on('central')
                    ->where('buyer_id', $buyer->id)
                    ->where('type', 'sell')
                    ->where('status', 'completed')
                    ->count();

                $totalSalesAmount = \App\Models\Central\ShareOperation::on('central')
                    ->where('buyer_id', $buyer->id)
                    ->where('type', 'sell')
                    ->where('status', 'completed')
                    ->sum('amount_total');

                $totalSharesOwned = \App\Models\Central\BuyerHolding::on('central')
                    ->where('buyer_id', $buyer->id)
                    ->sum('shares_owned');

                $activeSaleOffers = \App\Models\Central\BuyerSaleOffer::on('central')
                    ->where('seller_buyer_id', $buyer->id)
                    ->where('status', 'active')
                    ->count();

                $data['statistics'] = [
                    'total_purchases' => $totalPurchases,
                    'total_invested' => round($totalInvested, 2),
                    'total_sales' => $totalSales,
                    'total_sales_amount' => round($totalSalesAmount, 2),
                    'total_shares_owned' => $totalSharesOwned,
                    'total_holdings' => $holdings->count(),
                    'total_operations' => $operations->count(),
                    'active_sale_offers' => $activeSaleOffers,
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $data,
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
     * تحديث بيانات المستخدم
     */
    /**
     * تحديث بيانات المستخدم والمشتري
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:central.users,email,' . $request->user()->id,
            'phone' => 'sometimes|nullable|string|max:30',
            'national_id' => 'sometimes|nullable|string|max:50',
            'date_of_birth' => 'sometimes|nullable|date',
            'country' => 'sometimes|nullable|string|max:100',
            'city' => 'sometimes|nullable|string|max:100',
            'address' => 'sometimes|nullable|string|max:500',
            'avatar' => 'sometimes|image|max:2048',
            'current_password' => 'sometimes|required_with:new_password',
            'new_password' => 'sometimes|string|min:8|confirmed',
        ], [
            'email.unique' => 'البريد الإلكتروني مسجل مسبقاً',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'new_password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'new_password.confirmed' => 'كلمة المرور وتأكيدها غير متطابقين',
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

            // تحديث الاسم
            if ($request->has('name')) {
                $user->name = $request->name;
            }

            // تحديث البريد الإلكتروني في جدول users
            if ($request->has('email')) {
                $user->email = $request->email;
            }

            // تحديث الصورة الشخصية
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $path;
            }

            // تحديث كلمة المرور
            if ($request->filled('new_password')) {
                if (! Hash::check($request->current_password, $user->password)) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'كلمة المرور الحالية غير صحيحة',
                    ], 422);
                }
                $user->password = Hash::make($request->new_password);
            }

            $user->save();

            // تحديث بيانات المشتري إذا كان موجوداً
            $buyer = \App\Models\Central\Buyer::on('central')
                ->where('user_id', $user->id)
                ->first();

            if ($buyer) {
                // تحديث الاسم في جدول buyers أيضاً
                if ($request->has('name')) {
                    $buyer->full_name = $request->name;
                }

                // تحديث البريد في جدول buyers لمزامنة البيانات
                if ($request->has('email')) {
                    $buyer->email = $request->email;
                }

                // تحديث رقم الجوال
                if ($request->has('phone')) {
                    $buyer->phone = $request->phone;
                }

                // تحديث رقم الهوية
                if ($request->has('national_id')) {
                    $buyer->national_id = $request->national_id;
                }

                // تحديث تاريخ الميلاد
                if ($request->has('date_of_birth')) {
                    $buyer->date_of_birth = $request->date_of_birth;
                }

                // تحديث الدولة
                if ($request->has('country')) {
                    $buyer->country = $request->country;
                }

                // تحديث المدينة
                if ($request->has('city')) {
                    $buyer->city = $request->city;
                }

                // تحديث العنوان
                if ($request->has('address')) {
                    $buyer->address = $request->address;
                }

                $buyer->save();
            }

            DB::commit();

            // إعداد البيانات المرجعة
            $responseData = [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                ],
            ];

            // إضافة بيانات المشتري إذا كان موجوداً
            if ($buyer) {
                $responseData['buyer'] = [
                    'id' => $buyer->id,
                    'full_name' => $buyer->full_name,
                    'email' => $buyer->email,
                    'phone' => $buyer->phone,
                    'national_id' => $buyer->national_id,
                    'date_of_birth' => $buyer->date_of_birth ? $buyer->date_of_birth->format('Y-m-d') : null,
                    'country' => $buyer->country,
                    'city' => $buyer->city,
                    'address' => $buyer->address,
                    'kyc_status' => $buyer->kyc_status,
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث البيانات بنجاح',
                'data' => $responseData,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث البيانات',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * تسجيل مستخدم Tenant جديد (مدير)
     * يسجل في قاعدة بيانات Tenant المحددة
     */
    public function registerTenantAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tenant_domain' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $tenantDomain = $request->tenant_domain;

            // العثور على Tenant
            $tenant = DB::connection('central')
                ->table('tenants')
                ->where('Subdomain', $tenantDomain)
                ->first();

            if (! $tenant) {
                return response()->json([
                    'success' => false,
                    'message' => 'النطاق غير موجود',
                ], 404);
            }

            // الاتصال بقاعدة بيانات Tenant
            config(['database.connections.tenant.database' => $tenant->DBName]);
            DB::purge('tenant');
            DB::reconnect('tenant');

            // التحقق من عدم وجود البريد مسبقاً
            $existingUser = DB::connection('tenant')
                ->table('users')
                ->where('email', $request->email)
                ->first();

            if ($existingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'البريد الإلكتروني مستخدم مسبقاً',
                ], 422);
            }

            // إنشاء المستخدم في قاعدة بيانات Tenant
            $userId = DB::connection('tenant')->table('users')->insertGetId([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // الحصول على بيانات المستخدم
            $tenantUser = DB::connection('tenant')
                ->table('users')
                ->where('id', $userId)
                ->first();

            // إنشاء Token (استخدام User model مؤقت)
            $user = new User();
            $user->id = $tenantUser->id;
            $user->name = $tenantUser->name;
            $user->email = $tenantUser->email;
            $user->exists = true;
            
            $token = $user->createToken('tenant-admin', ['admin', 'tenant:' . $tenant->TenantID])->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل المستخدم بنجاح',
                'data' => [
                    'user' => [
                        'id' => $tenantUser->id,
                        'name' => $tenantUser->name,
                        'email' => $tenantUser->email,
                        'type' => 'admin',
                        'tenant_domain' => $tenantDomain,
                    ],
                    'token' => $token,
                    'tenant' => [
                        'id' => $tenant->TenantID,
                        'domain' => $tenant->Subdomain,
                        'database' => $tenant->DBName,
                    ],
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التسجيل',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * تسجيل دخول مستخدم Tenant (مدير)
     * يسجل الدخول من قاعدة بيانات Tenant المحددة
     */
    public function loginTenantAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tenant_domain' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $tenantDomain = $request->tenant_domain;

            // العثور على Tenant
            $tenant = DB::connection('central')
                ->table('tenants')
                ->where('Subdomain', $tenantDomain)
                ->first();

            if (! $tenant) {
                return response()->json([
                    'success' => false,
                    'message' => 'النطاق غير موجود',
                ], 404);
            }

            // الاتصال بقاعدة بيانات Tenant
            config(['database.connections.tenant.database' => $tenant->DBName]);
            DB::purge('tenant');
            DB::reconnect('tenant');

            // البحث عن المستخدم في قاعدة بيانات Tenant
            $tenantUser = DB::connection('tenant')
                ->table('users')
                ->where('email', $request->email)
                ->first();

            if (! $tenantUser || ! Hash::check($request->password, $tenantUser->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات الدخول غير صحيحة',
                ], 401);
            }

            // إنشاء Token (استخدام User model مؤقت)
            $user = new User();
            $user->id = $tenantUser->id;
            $user->name = $tenantUser->name;
            $user->email = $tenantUser->email;
            $user->exists = true;
            
            $token = $user->createToken('tenant-admin', ['admin', 'tenant:' . $tenant->TenantID])->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الدخول بنجاح',
                'data' => [
                    'user' => [
                        'id' => $tenantUser->id,
                        'name' => $tenantUser->name,
                        'email' => $tenantUser->email,
                        'type' => 'admin',
                        'tenant_domain' => $tenantDomain,
                    ],
                    'token' => $token,
                    'tenant' => [
                        'id' => $tenant->TenantID,
                        'domain' => $tenant->Subdomain,
                        'database' => $tenant->DBName,
                    ],
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تسجيل الدخول',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
