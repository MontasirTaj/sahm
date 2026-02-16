<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class TenantHelper
{
    /**
     * Get tenant and setup database connection
     * 
     * @param string $tenantDomain
     * @return object|null
     */
    public static function setupTenantConnection($tenantDomain)
    {
        if (!$tenantDomain) {
            return null;
        }

        // العثور على Tenant من قاعدة البيانات المركزية
        $tenant = DB::connection('central')->table('tenants')
            ->where('Subdomain', $tenantDomain)
            ->orWhere('DBName', $tenantDomain)
            ->first();

        if (!$tenant) {
            return null;
        }

        // إعداد الاتصال بقاعدة بيانات Tenant
        config(['database.connections.tenant.database' => $tenant->DBName]);
        DB::purge('tenant');
        DB::reconnect('tenant');

        return $tenant;
    }
}
