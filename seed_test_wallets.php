<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Central\Buyer;
use App\Models\Central\BuyerWallet;
use Illuminate\Support\Facades\DB;

echo "==============================================\n";
echo "إنشاء بيانات تجريبية للمحافظ\n";
echo "==============================================\n\n";

DB::connection('central')->beginTransaction();

try {
    // 1. إنشاء محافظ لجميع المشترين
    $buyers = Buyer::on('central')->get();
    
    foreach ($buyers as $buyer) {
        $wallet = $buyer->getOrCreateWallet();
        echo "✓ محفظة للمشتري: {$buyer->name} (ID: {$buyer->id})\n";
    }
    
    echo "\n";
    
    // 2. إضافة رصيد تجريبي لبعض المحافظ
    echo "إضافة رصيد تجريبي:\n";
    echo "----------------------------------------\n";
    
    $testBuyers = Buyer::on('central')->take(3)->get();
    
    foreach ($testBuyers as $buyer) {
        $wallet = $buyer->wallet;
        $amount = rand(10000, 50000);
        
        $wallet->deposit(
            $amount,
            'إيداع تجريبي للاختبار'
        );
        
        echo "✓ {$buyer->name}: " . number_format($amount, 2) . " ريال\n";
    }
    
    DB::connection('central')->commit();
    
    echo "\n==============================================\n";
    echo "✅ تم إنشاء البيانات التجريبية بنجاح!\n";
    echo "==============================================\n";
    
} catch (\Exception $e) {
    DB::connection('central')->rollBack();
    echo "\n❌ خطأ: " . $e->getMessage() . "\n";
}
