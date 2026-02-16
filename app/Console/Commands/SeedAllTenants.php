<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class SeedAllTenants extends Command
{
    protected $signature = 'tenants:seed-all';
    protected $description = 'تشغيل Seeder التيننت لجميع قواعد بيانات التيننتات';

    public function handle()
    {
        $tenants = Tenant::on('central')->get();
        $this->info('عدد التيننتات: ' . $tenants->count());
        foreach ($tenants as $tenant) {
            $db = $tenant->DBName;
            config(['database.connections.tenant.database' => $db]);
            DB::purge('tenant');
            DB::reconnect('tenant');
            $this->info("تشغيل Seeder للتيننت: $db ...");
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\Tenant\\TenantDatabaseSeeder',
                '--database' => 'tenant',
                '--force' => true,
            ]);
            $this->info(Artisan::output());
        }
        $this->info('تم تطبيق Seeder على جميع التيننتات بنجاح.');
    }
}
