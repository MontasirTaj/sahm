<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class SetTenantFromHeader
{
    /**
     * Handle an incoming request.
     * يتحقق من وجود tenant_domain في الـ header أو request ويعد الاتصال بقاعدة البيانات
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // الحصول على tenant_domain من header أو request parameters
        $tenantDomain = $request->header('X-Tenant-Domain') 
                     ?? $request->input('tenant_domain');

        if (!$tenantDomain) {
            return response()->json([
                'success' => false,
                'message' => 'يجب تحديد النطاق (tenant_domain) في الـ header أو parameters',
                'hint' => 'أضف X-Tenant-Domain في الـ header أو tenant_domain في الـ parameters'
            ], 422);
        }

        // العثور على Tenant
        $tenant = Tenant::where('domain', $tenantDomain)->first();

        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'النطاق المحدد غير موجود أو غير صالح'
            ], 404);
        }

        // التحقق من حالة Tenant
        if ($tenant->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'هذا النطاق غير نشط حالياً'
            ], 403);
        }

        // إعداد الاتصال بقاعدة بيانات Tenant
        try {
            config(['database.connections.tenant.database' => $tenant->database]);
            DB::purge('tenant');
            DB::reconnect('tenant');

            // إضافة tenant للـ request لاستخدامه لاحقاً
            $request->merge(['_tenant' => $tenant]);
            $request->attributes->set('tenant', $tenant);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في الاتصال بقاعدة البيانات',
                'error' => config('app.debug') ? $e->getMessage() : 'Database connection failed'
            ], 500);
        }

        return $next($request);
    }
}
