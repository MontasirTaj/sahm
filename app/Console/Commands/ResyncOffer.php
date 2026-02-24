<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TenantShareOffer;
use App\Models\Tenant;
use App\Services\ShareOfferSyncService;
use Illuminate\Support\Facades\DB;

class ResyncOffer extends Command
{
    protected $signature = 'offer:resync {tenant_offer_id} {subdomain}';
    protected $description = 'إعادة مزامنة عرض من قاعدة التينانت إلى القاعدة المركزية';

    public function handle(ShareOfferSyncService $sync)
    {
        $tenantOfferId = $this->argument('tenant_offer_id');
        $subdomain = $this->argument('subdomain');

        $tenant = Tenant::on('central')->where('Subdomain', $subdomain)->first();
        
        if (!$tenant) {
            $this->error("التينانت {$subdomain} غير موجود");
            return 1;
        }

        config(['database.connections.tenant.database' => $tenant->DBName]);
        DB::purge('tenant');

        $tenantOffer = TenantShareOffer::on('tenant')->find($tenantOfferId);
        
        if (!$tenantOffer) {
            $this->error("العرض {$tenantOfferId} غير موجود في قاعدة بيانات التينانت");
            return 1;
        }

        $this->info("إعادة مزامنة العرض {$tenantOfferId} من {$subdomain}...");
        $this->info("property_type في التينانت: " . ($tenantOffer->property_type ?? 'NULL'));

        $central = $sync->upsertToCentral($tenantOffer, $tenant);

        $this->info("تمت المزامنة بنجاح!");
        $this->info("ID المركزي: {$central->id}");
        $this->info("property_type في المركزية: " . ($central->property_type ?? 'NULL'));

        return 0;
    }
}
