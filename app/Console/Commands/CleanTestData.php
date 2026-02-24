<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Tenant;

class CleanTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:test-data {--confirm : تأكيد الحذف بدون سؤال}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'حذف جميع العروض والعمليات من قواعد البيانات (مع الاحتفاظ بالمستخدمين والبيانات المرجعية)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('confirm')) {
            if (!$this->confirm('هل أنت متأكد من حذف جميع العروض والعمليات؟ (سيتم الاحتفاظ بالمستخدمين والمدن وأنواع العقارات)')) {
                $this->info('تم الإلغاء.');
                return 0;
            }
        }

        $this->info('🧹 بدء تنظيف البيانات...');
        $this->newLine();

        // تنظيف قاعدة البيانات المركزية
        $this->cleanCentralDatabase();

        // تنظيف قواعد بيانات التينانت
        $this->cleanTenantDatabases();

        $this->newLine();
        $this->info('✅ تم تنظيف البيانات بنجاح!');
        
        return 0;
    }

    /**
     * تنظيف قاعدة البيانات المركزية
     */
    protected function cleanCentralDatabase()
    {
        $this->info('📊 تنظيف قاعدة البيانات المركزية...');

        try {
            // حذف الإشعارات الإدارية
            DB::connection('central')->table('admin_notifications')->truncate();
            $this->line('   ✓ تم حذف الإشعارات الإدارية');

            // حذف نقاط المراجعة العقارية
            DB::connection('central')->table('real_estate_checkpoints')->truncate();
            $this->line('   ✓ تم حذف نقاط المراجعة العقارية');

            // حذف مراجعات العروض
            DB::connection('central')->table('offer_reviews')->truncate();
            $this->line('   ✓ تم حذف مراجعات العروض');

            // حذف حيازات المشترين
            if (Schema::connection('central')->hasTable('buyer_holdings')) {
                DB::connection('central')->table('buyer_holdings')->truncate();
                $this->line('   ✓ تم حذف حيازات المشترين');
            }

            // حذف عمليات الأسهم
            if (Schema::connection('central')->hasTable('share_operations')) {
                DB::connection('central')->table('share_operations')->truncate();
                $this->line('   ✓ تم حذف عمليات الأسهم');
            }

            // حذف عروض الأسهم
            DB::connection('central')->table('share_offers')->truncate();
            $this->line('   ✓ تم حذف عروض الأسهم');

            $this->info('   ✅ اكتملت المركزية');
        } catch (\Exception $e) {
            $this->error('   ❌ خطأ في تنظيف القاعدة المركزية: ' . $e->getMessage());
        }
    }

    /**
     * تنظيف قواعد بيانات التينانت
     */
    protected function cleanTenantDatabases()
    {
        $this->info('📊 تنظيف قواعد بيانات المشتركين...');

        $tenants = Tenant::on('central')->get();
        
        if ($tenants->isEmpty()) {
            $this->warn('   ⚠ لا يوجد مشتركين');
            return;
        }

        $bar = $this->output->createProgressBar($tenants->count());
        $bar->start();

        foreach ($tenants as $tenant) {
            try {
                // تعيين قاعدة البيانات الخاصة بالتينانت
                config(['database.connections.tenant.database' => $tenant->database]);
                DB::purge('tenant');

                // حذف عروض الأسهم
                if (Schema::connection('tenant')->hasTable('share_offers')) {
                    DB::connection('tenant')->table('share_offers')->truncate();
                }

                // حذف عمليات الأسهم
                if (Schema::connection('tenant')->hasTable('share_operations')) {
                    DB::connection('tenant')->table('share_operations')->truncate();
                }

                // حذف حيازات المشترين
                if (Schema::connection('tenant')->hasTable('buyer_holdings')) {
                    DB::connection('tenant')->table('buyer_holdings')->truncate();
                }

                $bar->advance();
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("   ❌ خطأ في تنظيف {$tenant->Subdomain}: " . $e->getMessage());
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info('   ✅ اكتمل تنظيف ' . $tenants->count() . ' مشترك');
    }
}
