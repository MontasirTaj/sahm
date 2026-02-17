<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "بدء حذف الإيداعات التجريبية...\n\n";

// جلب جميع الإيداعات التجريبية
$testDeposits = DB::connection('central')->table('wallet_transactions')
    ->where('description', 'like', '%إيداع تجريبي%')
    ->get();

echo "عدد الإيداعات التجريبية: " . $testDeposits->count() . "\n\n";

foreach ($testDeposits as $deposit) {
    // تحديث رصيد المحفظة
    DB::connection('central')->table('buyer_wallets')
        ->where('id', $deposit->wallet_id)
        ->decrement('balance', $deposit->amount);
    
    echo "✓ تم خصم {$deposit->amount} SAR من المحفظة #{$deposit->wallet_id}\n";
}

// حذف الإيداعات التجريبية
$deleted = DB::connection('central')->table('wallet_transactions')
    ->where('description', 'like', '%إيداع تجريبي%')
    ->delete();

echo "\n✅ تم حذف {$deleted} إيداع تجريبي بنجاح!\n";

// عرض الأرصدة الحالية
echo "\n--- الأرصدة الحالية ---\n";
$wallets = DB::connection('central')->table('buyer_wallets')->get();
foreach ($wallets as $wallet) {
    echo "المحفظة #{$wallet->id}: {$wallet->balance} SAR\n";
}
