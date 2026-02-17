<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Central\Buyer;
use App\Models\Central\BuyerHolding;
use App\Models\Central\BuyerSaleOffer;
use App\Models\Central\ShareOperation;
use App\Models\Central\WalletTransaction;

echo "==============================================\n";
echo "فحص سلامة بيانات السوق الثانوي\n";
echo "==============================================\n\n";

// 1. فحص المحافظ
echo "1. المحافظ:\n";
echo "----------------------------------------\n";
$buyers = Buyer::on('central')->with('wallet')->get();
foreach ($buyers as $buyer) {
    $wallet = $buyer->wallet;
    echo "مشتري: {$buyer->name}\n";
    echo "  - الرصيد: " . ($wallet ? number_format($wallet->balance, 2) : '0.00') . " ريال\n";
    echo "  - الرصيد المتاح: " . ($wallet ? number_format($wallet->available_balance, 2) : '0.00') . " ريال\n";
}
echo "\n";

// 2. فحص الممتلكات
echo "2. الممتلكات (Holdings):\n";
echo "----------------------------------------\n";
$holdings = BuyerHolding::on('central')
    ->with(['buyer'])
    ->get();
foreach ($holdings as $holding) {
    echo "مشتري: {$holding->buyer->name}\n";
    echo "  - عدد الأسهم: {$holding->shares_owned}\n";
    echo "  - متوسط السعر: " . number_format($holding->avg_price_per_share, 2) . "\n";
}
echo "\n";

// 3. فحص عروض البيع النشطة
echo "3. عروض البيع النشطة:\n";
echo "----------------------------------------\n";
$activeOffers = BuyerSaleOffer::on('central')
    ->where('status', 'active')
    ->where('shares_count', '>', 0)
    ->with(['seller'])
    ->get();
foreach ($activeOffers as $offer) {
    echo "عرض #{$offer->id} - البائع: {$offer->seller->name}\n";
    echo "  - الأسهم المتاحة: {$offer->shares_count}\n";
    echo "  - السعر: " . number_format($offer->price_per_share, 2) . " {$offer->currency}\n";
}
echo "\n";

// 4. فحص العمليات الأخيرة
echo "4. آخر 5 عمليات:\n";
echo "----------------------------------------\n";
$operations = ShareOperation::on('central')
    ->with(['buyer'])
    ->orderByDesc('created_at')
    ->limit(5)
    ->get();
foreach ($operations as $op) {
    echo "#{$op->id} - {$op->type} - المشتري: {$op->buyer->name}\n";
    echo "  - الأسهم: {$op->shares_count}\n";
    echo "  - المبلغ: " . number_format($op->amount_total, 2) . " {$op->currency}\n";
    echo "  - الحالة: {$op->status}\n";
}
echo "\n";

// 5. فحص المعاملات المالية الأخيرة
echo "5. آخر 5 معاملات مالية:\n";
echo "----------------------------------------\n";
$transactions = WalletTransaction::on('central')
    ->with(['buyer'])
    ->orderByDesc('created_at')
    ->limit(5)
    ->get();
foreach ($transactions as $trans) {
    echo "#{$trans->id} - {$trans->type} - المشتري: {$trans->buyer->name}\n";
    echo "  - المبلغ: " . number_format($trans->amount, 2) . " {$trans->currency}\n";
    echo "  - الرصيد قبل: " . number_format($trans->balance_before, 2) . "\n";
    echo "  - الرصيد بعد: " . number_format($trans->balance_after, 2) . "\n";
    if ($trans->share_operation_id) {
        echo "  - مرتبطة بعملية #{$trans->share_operation_id}\n";
    }
}
echo "\n";

// 6. فحص الاتساق
echo "6. فحص الاتساق:\n";
echo "----------------------------------------\n";
$inconsistencies = 0;

// فحص: هل جميع BuyerSaleOffer النشطة لها holding موجود؟
$activeOffersWithoutHolding = BuyerSaleOffer::on('central')
    ->where('status', 'active')
    ->whereDoesntHave('holding')
    ->count();
if ($activeOffersWithoutHolding > 0) {
    echo "⚠ عروض نشطة بدون holding: {$activeOffersWithoutHolding}\n";
    $inconsistencies++;
}

// فحص: هل جميع المشترين لديهم محافظ؟
$buyersWithoutWallet = Buyer::on('central')->whereDoesntHave('wallet')->count();
if ($buyersWithoutWallet > 0) {
    echo "⚠ مشترين بدون محفظة: {$buyersWithoutWallet}\n";
    $inconsistencies++;
}

if ($inconsistencies == 0) {
    echo "✅ جميع البيانات متسقة!\n";
}

echo "\n==============================================\n";
echo "تم الانتهاء من الفحص\n";
echo "==============================================\n";
