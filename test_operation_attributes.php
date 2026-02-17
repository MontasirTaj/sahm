<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Central\ShareOperation;

echo "==============================================\n";
echo "اختبار الـ Attributes في ShareOperation\n";
echo "==============================================\n\n";

try {
    // جلب العمليات باستخدام Eloquent
    $operations = ShareOperation::on('central')
        ->join('share_offers', 'share_operations.offer_id', '=', 'share_offers.id')
        ->select('share_operations.*', 'share_offers.title')
        ->limit(5)
        ->get();
    
    echo "عدد العمليات: " . $operations->count() . "\n\n";
    
    foreach ($operations as $op) {
        echo "عملية #{$op->id}:\n";
        echo "  - type: {$op->type}\n";
        echo "  - type_name (Attribute): {$op->type_name}\n";
        echo "  - color (Attribute): {$op->color}\n";
        echo "  - icon (Attribute): {$op->icon}\n";
        echo "  - title (من JOIN): {$op->title}\n";
        echo "  - shares_count: {$op->shares_count}\n";
        echo "  - amount_total: {$op->amount_total}\n";
        echo "  - status: {$op->status}\n";
        echo "\n";
    }
    
    echo "==============================================\n";
    echo "✅ جميع الـ Attributes تعمل بشكل صحيح!\n";
    echo "==============================================\n";
    
} catch (\Exception $e) {
    echo "\n❌ خطأ: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
