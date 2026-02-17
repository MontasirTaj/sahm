<?php

/**
 * Script لإصلاح العروض القديمة التي لديها cover_image لكن media = null
 * يجب تشغيله مرة واحدة فقط لإصلاح البيانات القديمة
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔧 بدء إصلاح العروض القديمة...\n\n";

try {
    // إصلاح العروض في قاعدة البيانات المركزية
    echo "📊 فحص قاعدة البيانات المركزية...\n";
    $centralOffers = DB::connection('central')->table('share_offers')
        ->whereNotNull('cover_image')
        ->whereNull('media')
        ->get();
    
    echo "وجدت {$centralOffers->count()} عرض بحاجة إلى إصلاح في القاعدة المركزية\n";
    
    $centralFixed = 0;
    foreach ($centralOffers as $offer) {
        $media = json_encode([$offer->cover_image]);
        DB::connection('central')->table('share_offers')
            ->where('id', $offer->id)
            ->update(['media' => $media]);
        $centralFixed++;
    }
    
    echo "✅ تم إصلاح {$centralFixed} عرض في القاعدة المركزية\n\n";
    
    // إصلاح العروض في قواعد بيانات التينانت
    echo "📊 فحص قواعد بيانات التينانت...\n";
    
    // جلب جميع التينانت
    $tenants = DB::connection('central')->table('tenants')->get();
    echo "وجدت {$tenants->count()} تينانت\n\n";
    
    $totalTenantFixed = 0;
    
    foreach ($tenants as $tenant) {
        $dbName = $tenant->DBName;
        echo "  🔍 فحص تينانت: {$tenant->TenantName} (DB: {$dbName})...\n";
        
        try {
            // تعيين اسم قاعدة البيانات للتينانت
            config(['database.connections.tenant.database' => $dbName]);
            DB::purge('tenant');
            DB::reconnect('tenant');
            
            $tenantOffers = DB::connection('tenant')->table('share_offers')
                ->whereNotNull('cover_image')
                ->whereNull('media')
                ->get();
            
            if ($tenantOffers->count() > 0) {
                echo "    وجدت {$tenantOffers->count()} عرض بحاجة إلى إصلاح\n";
                
                $fixed = 0;
                foreach ($tenantOffers as $offer) {
                    $media = json_encode([$offer->cover_image]);
                    DB::connection('tenant')->table('share_offers')
                        ->where('id', $offer->id)
                        ->update(['media' => $media]);
                    $fixed++;
                }
                
                echo "    ✅ تم إصلاح {$fixed} عرض\n";
                $totalTenantFixed += $fixed;
            } else {
                echo "    ✓ لا توجد عروض بحاجة إلى إصلاح\n";
            }
            
        } catch (\Exception $e) {
            echo "    ❌ خطأ في معالجة تينانت {$tenant->TenantName}: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "✅ اكتمل الإصلاح بنجاح!\n";
    echo "📊 الإحصائيات:\n";
    echo "   - العروض المصلحة في القاعدة المركزية: {$centralFixed}\n";
    echo "   - العروض المصلحة في قواعد التينانت: {$totalTenantFixed}\n";
    echo "   - الإجمالي: " . ($centralFixed + $totalTenantFixed) . " عرض\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
} catch (\Exception $e) {
    echo "\n❌ حدث خطأ: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
