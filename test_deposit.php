<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Central\Buyer;
use Illuminate\Support\Facades\DB;

echo "==============================================\n";
echo "اختبار عملية الإيداع\n";
echo "==============================================\n\n";

DB::connection('central')->beginTransaction();

try {
    // اختيار أول مشتري للاختبار
    $buyer = Buyer::on('central')->first();
    
    if (!$buyer) {
        echo "❌ لا يوجد مشترين في النظام!\n";
        exit(1);
    }
    
    echo "المشتري: {$buyer->name} (ID: {$buyer->id})\n";
    echo "----------------------------------------\n\n";
    
    // الحصول على المحفظة
    $wallet = $buyer->getOrCreateWallet();
    
    echo "الرصيد قبل الإيداع: " . number_format($wallet->balance, 2) . " ريال\n";
    
    // عملية الإيداع
    $depositAmount = 5000.00;
    echo "محاولة إيداع: " . number_format($depositAmount, 2) . " ريال\n\n";
    
    $wallet->deposit(
        $depositAmount,
        "إيداع تجريبي - تحويل بنكي",
        [
            'payment_method' => 'bank_transfer',
            'reference' => 'TEST-' . time(),
        ]
    );
    
    // تحديث المحفظة
    $wallet->refresh();
    
    echo "✅ تم الإيداع بنجاح!\n\n";
    echo "الرصيد بعد الإيداع: " . number_format($wallet->balance, 2) . " ريال\n";
    echo "إجمالي الإيداعات: " . number_format($wallet->total_deposits, 2) . " ريال\n";
    echo "عدد المعاملات: " . $wallet->transactions()->count() . "\n\n";
    
    // عرض آخر معاملة
    $lastTransaction = $wallet->transactions()->latest()->first();
    echo "آخر معاملة:\n";
    echo "  - النوع: {$lastTransaction->type}\n";
    echo "  - المبلغ: " . number_format($lastTransaction->amount, 2) . " ريال\n";
    echo "  - الرصيد قبل: " . number_format($lastTransaction->balance_before, 2) . "\n";
    echo "  - الرصيد بعد: " . number_format($lastTransaction->balance_after, 2) . "\n";
    echo "  - الحالة: {$lastTransaction->status}\n";
    
    DB::connection('central')->commit();
    
    echo "\n==============================================\n";
    echo "✅ الاختبار نجح!\n";
    echo "==============================================\n";
    
} catch (\Exception $e) {
    DB::connection('central')->rollBack();
    echo "\n❌ خطأ: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
