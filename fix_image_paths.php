<?php

/**
 * Script لإصلاح مسارات الصور في العروض المضافة عن طريق API القديم
 * يزيل 'storage/' من بداية المسار ليتوافق مع Web Controller
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔧 بدء إصلاح مسارات الصور...\n\n";

try {
    // إصلاح العروض في قاعدة البيانات المركزية
    echo "📊 فحص قاعدة البيانات المركزية...\n";
    $centralOffers = DB::connection('central')
        ->table('share_offers')
        ->whereNotNull('media')
        ->get();
    
    $centralFixed = 0;
    foreach ($centralOffers as $offer) {
        $media = json_decode($offer->media, true);
        if (is_array($media)) {
            $fixed = false;
            $newMedia = [];
            
            foreach ($media as $img) {
                // إذا كان المسار يبدأ بـ storage/، نحذفها
                if (strpos($img, 'storage/') === 0) {
                    $newMedia[] = substr($img, 8); // إزالة 'storage/'
                    $fixed = true;
                } else {
                    $newMedia[] = $img;
                }
            }
            
            if ($fixed) {
                // تحديث cover_image أيضاً إذا كان يحتوي على storage/
                $coverImage = $offer->cover_image;
                if ($coverImage && strpos($coverImage, 'storage/') === 0) {
                    $coverImage = substr($coverImage, 8);
                }
                
                DB::connection('central')
                    ->table('share_offers')
                    ->where('id', $offer->id)
                    ->update([
                        'media' => json_encode($newMedia),
                        'cover_image' => $coverImage
                    ]);
                $centralFixed++;
                echo "  ✅ تم إصلاح العرض #{$offer->id}\n";
            }
        }
    }
    
    echo "✅ تم إصلاح {$centralFixed} عرض في القاعدة المركزية\n\n";
    
    // إصلاح العروض في قواعد بيانات التينانت
    echo "📊 فحص قواعد بيانات التينانت...\n";
    
    $tenants = DB::connection('central')->table('tenants')->get();
    echo "وجدت {$tenants->count()} تينانت\n\n";
    
    $totalTenantFixed = 0;
    
    foreach ($tenants as $tenant) {
        $dbName = $tenant->DBName;
        echo "  🔍 فحص تينانت: {$tenant->TenantName} (DB: {$dbName})...\n";
        
        try {
            config(['database.connections.tenant.database' => $dbName]);
            DB::purge('tenant');
            DB::reconnect('tenant');
            
            $tenantOffers = DB::connection('tenant')
                ->table('share_offers')
                ->whereNotNull('media')
                ->get();
            
            $fixed = 0;
            foreach ($tenantOffers as $offer) {
                $media = json_decode($offer->media, true);
                if (is_array($media)) {
                    $needsFix = false;
                    $newMedia = [];
                    
                    foreach ($media as $img) {
                        if (strpos($img, 'storage/') === 0) {
                            $newMedia[] = substr($img, 8);
                            $needsFix = true;
                        } else {
                            $newMedia[] = $img;
                        }
                    }
                    
                    if ($needsFix) {
                        $coverImage = $offer->cover_image;
                        if ($coverImage && strpos($coverImage, 'storage/') === 0) {
                            $coverImage = substr($coverImage, 8);
                        }
                        
                        DB::connection('tenant')
                            ->table('share_offers')
                            ->where('id', $offer->id)
                            ->update([
                                'media' => json_encode($newMedia),
                                'cover_image' => $coverImage
                            ]);
                        $fixed++;
                    }
                }
            }
            
            if ($fixed > 0) {
                echo "    ✅ تم إصلاح {$fixed} عرض\n";
                $totalTenantFixed += $fixed;
            } else {
                echo "    ✓ لا توجد عروض بحاجة إلى إصلاح\n";
            }
            
        } catch (\Exception $e) {
            echo "    ❌ خطأ: " . $e->getMessage() . "\n";
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
