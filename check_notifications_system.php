<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Central\Buyer;
use App\Models\Central\BuyerNotification;
use App\Models\Central\ShareOperation;

echo "==============================================\n";
echo "فحص نظام التنبيهات والترجمات\n";
echo "==============================================\n\n";

// 1. فحص التنبيهات
echo "1. التنبيهات:\n";
echo "----------------------------------------\n";
$buyers = Buyer::on('central')->with('wallet')->get();
foreach ($buyers as $buyer) {
    $unreadCount = BuyerNotification::on('central')
        ->where('buyer_id', $buyer->id)
        ->unread()
        ->count();

    $totalCount = BuyerNotification::on('central')
        ->where('buyer_id', $buyer->id)
        ->count();

    if ($totalCount > 0) {
        echo "مشتري: {$buyer->name}\n";
        echo "  - إجمالي التنبيهات: {$totalCount}\n";
        echo "  - غير المقروءة: {$unreadCount}\n";
    }
}
echo "\n";

// 2. فحص الترجمات
echo "2. ترجمات العمليات:\n";
echo "----------------------------------------\n";
$operations = ShareOperation::on('central')->limit(5)->get();
foreach ($operations as $op) {
    echo "عملية #{$op->id}:\n";
    echo "  - النوع (type): {$op->type}\n";
    echo "  - الاسم المترجم (type_name): {$op->type_name}\n";
    echo "  - اللون (color): {$op->color}\n";
    echo "  - الأيقونة (icon): {$op->icon}\n";
}
echo "\n";

// 3. فحص آخر تنبيه
echo "3. آخر تنبيه:\n";
echo "----------------------------------------\n";
$lastNotification = BuyerNotification::on('central')->latest()->first();
if ($lastNotification) {
    echo "العنوان: {$lastNotification->title}\n";
    echo "الرسالة: {$lastNotification->message}\n";
    echo "النوع: {$lastNotification->type}\n";
    echo 'الحالة: '.($lastNotification->is_read ? 'مقروء' : 'غير مقروء')."\n";
    echo "اللون: {$lastNotification->color}\n";
    echo "الأيقونة: {$lastNotification->icon}\n";
}
echo "\n";

echo "==============================================\n";
echo "✅ الفحص مكتمل!\n";
echo "==============================================\n";
