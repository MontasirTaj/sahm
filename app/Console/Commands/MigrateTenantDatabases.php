<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tenant;

class MigrateTenantDatabases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:migrate {--force : Force migration without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'تطبيق migrations على جميع قواعد بيانات المشتركين';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenants = Tenant::on('central')->get();
        
        if ($tenants->isEmpty()) {
            $this->warn('لا يوجد مشتركين في النظام');
            return 0;
        }

        $this->info("تم العثور على {$tenants->count()} مشترك");
        $this->newLine();

        if (!$this->option('force')) {
            if (!$this->confirm('هل تريد تطبيق الـ migrations على جميع قواعد بيانات المشتركين؟')) {
                $this->info('تم الإلغاء');
                return 0;
            }
        }

        $bar = $this->output->createProgressBar($tenants->count());
        $bar->start();

        $success = 0;
        $failed = 0;

        foreach ($tenants as $tenant) {
            try {
                // تعيين قاعدة البيانات الخاصة بالتينانت
                config(['database.connections.tenant.database' => $tenant->DBName]);
                DB::purge('tenant');

                // تطبيق migrations من مجلد tenant
                $migrationPath = 'database/migrations/tenant';
                Artisan::call('migrate', [
                    '--database' => 'tenant',
                    '--path' => $migrationPath,
                    '--force' => true,
                ]);

                $success++;
                $bar->advance();
            } catch (\Exception $e) {
                $failed++;
                $this->newLine();
                $this->error("فشل التطبيق على {$tenant->Subdomain}: " . $e->getMessage());
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine(2);
        
        $this->info("✅ تم بنجاح: {$success}");
        if ($failed > 0) {
            $this->error("❌ فشل: {$failed}");
        }

        return 0;
    }
}
