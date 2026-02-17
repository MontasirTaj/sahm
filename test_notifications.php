<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Central\Buyer;
use App\Models\Central\BuyerNotification;
use App\Models\Central\BuyerSaleOffer;
use App\Models\Central\ShareOperation;
use App\Models\Central\WalletTransaction;

echo "==============================================\n";
echo "إنشاء تنبيهات تجريبية\n";
echo "==============================================\n\n";

try {
    // الحصول على أول مشتري
    $buyer = Buyer::on('central')->first();
    
    if (!$buyer) {
        echo "❌ لا يوجد مشترين في النظام!\n";
        exit(1);
    }
    
    echo "إنشاء تنبيهات للمشتري: {$buyer->name} (ID: {$buyer->id})\n";
    echo "----------------------------------------\n\n";
    
    // إنشاء تنبيه بيع كامل
    $notification1 = BuyerNotification::on('central')->create([
        'buyer_id' => $buyer->id,
        'type' => 'sale_completed',
        'title' => '✅ تم بيع جميع أسهمك!',
        'message' => 'تم بيع جميع الأسهم (10 سهم) من عرضك بسعر 15,000.00 ريال للسهم. إجمالي المبلغ: 150,000.00 ريال',
        'metadata' => [
            'shares_sold' => 10,
            'price_per_share' => 15000,
            'total_amount' => 150000,
        ],
    ]);
    
    echo "✓ تنبيه 1: {$notification1->title}\n";
    
    // إنشاء تنبيه بيع جزئي
    $notification2 = BuyerNotification::on('central')->create([
        'buyer_id' => $buyer->id,
        'type' => 'partial_sale',
        'title' => '🔔 تم بيع جزء من أسهمك!',
        'message' => 'تم بيع 5 سهم من أصل 15 من عرضك بسعر 12,000.00 ريال للسهم. المبلغ: 60,000.00 ريال. تبقى 10 سهم',
        'metadata' => [
            'shares_sold' => 5,
            'price_per_share' => 12000,
            'total_amount' => 60000,
        ],
    ]);
    
    echo "✓ تنبيه 2: {$notification2->title}\n";
    
    // إنشاء تنبيه قديم ومقروء
    $notification3 = BuyerNotification::on('central')->create([
        'buyer_id' => $buyer->id,
        'type' => 'sale_completed',
        'title' => '✅ تم بيع أسهمك',
        'message' => 'تنبيه قديم - تم بيع أسهمك بنجاح',
        'is_read' => true,
        'read_at' => now()->subDays(2),
        'created_at' => now()->subDays(3),
    ]);
    
    echo "✓ تنبيه 3: {$notification3->title} (مقروء)\n\n";
    
    // إحصائيات
    $totalNotifications = BuyerNotification::on('central')->where('buyer_id', $buyer->id)->count();
    $unreadNotifications = BuyerNotification::on('central')->where('buyer_id', $buyer->id)->unread()->count();
    
    echo "==============================================\n";
    echo "✅ تم إنشاء التنبيهات بنجاح!\n";
    echo "إجمالي التنبيهات: {$totalNotifications}\n";
    echo "التنبيهات غير المقروءة: {$unreadNotifications}\n";
    echo "==============================================\n";
    
} catch (\Exception $e) {
    echo "\n❌ خطأ: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
