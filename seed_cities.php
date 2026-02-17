<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "إضافة المدن الافتراضية للمملكة العربية السعودية...\n\n";

$cities = [
    ['name_ar' => 'الرياض', 'name_en' => 'Riyadh', 'region' => 'منطقة الرياض', 'sort_order' => 1],
    ['name_ar' => 'جدة', 'name_en' => 'Jeddah', 'region' => 'منطقة مكة المكرمة', 'sort_order' => 2],
    ['name_ar' => 'مكة المكرمة', 'name_en' => 'Makkah', 'region' => 'منطقة مكة المكرمة', 'sort_order' => 3],
    ['name_ar' => 'المدينة المنورة', 'name_en' => 'Madinah', 'region' => 'منطقة المدينة المنورة', 'sort_order' => 4],
    ['name_ar' => 'الدمام', 'name_en' => 'Dammam', 'region' => 'المنطقة الشرقية', 'sort_order' => 5],
    ['name_ar' => 'الخبر', 'name_en' => 'Khobar', 'region' => 'المنطقة الشرقية', 'sort_order' => 6],
    ['name_ar' => 'الظهران', 'name_en' => 'Dhahran', 'region' => 'المنطقة الشرقية', 'sort_order' => 7],
    ['name_ar' => 'الطائف', 'name_en' => 'Taif', 'region' => 'منطقة مكة المكرمة', 'sort_order' => 8],
    ['name_ar' => 'بريدة', 'name_en' => 'Buraidah', 'region' => 'منطقة القصيم', 'sort_order' => 9],
    ['name_ar' => 'تبوك', 'name_en' => 'Tabuk', 'region' => 'منطقة تبوك', 'sort_order' => 10],
    ['name_ar' => 'أبها', 'name_en' => 'Abha', 'region' => 'منطقة عسير', 'sort_order' => 11],
    ['name_ar' => 'خميس مشيط', 'name_en' => 'Khamis Mushait', 'region' => 'منطقة عسير', 'sort_order' => 12],
    ['name_ar' => 'حائل', 'name_en' => 'Hail', 'region' => 'منطقة حائل', 'sort_order' => 13],
    ['name_ar' => 'نجران', 'name_en' => 'Najran', 'region' => 'منطقة نجران', 'sort_order' => 14],
    ['name_ar' => 'جازان', 'name_en' => 'Jazan', 'region' => 'منطقة جازان', 'sort_order' => 15],
    ['name_ar' => 'ينبع', 'name_en' => 'Yanbu', 'region' => 'منطقة المدينة المنورة', 'sort_order' => 16],
    ['name_ar' => 'الجبيل', 'name_en' => 'Jubail', 'region' => 'المنطقة الشرقية', 'sort_order' => 17],
    ['name_ar' => 'الأحساء', 'name_en' => 'Al-Ahsa', 'region' => 'المنطقة الشرقية', 'sort_order' => 18],
    ['name_ar' => 'القطيف', 'name_en' => 'Qatif', 'region' => 'المنطقة الشرقية', 'sort_order' => 19],
    ['name_ar' => 'عرعر', 'name_en' => 'Arar', 'region' => 'منطقة الحدود الشمالية', 'sort_order' => 20],
];

foreach ($cities as $city) {
    $city['is_active'] = true;
    
    // تحقق من عدم وجود المدينة مسبقاً
    $exists = DB::connection('central')->table('cities')
        ->where('name_ar', $city['name_ar'])
        ->exists();
    
    if (!$exists) {
        DB::connection('central')->table('cities')->insert(array_merge($city, [
            'created_at' => now(),
            'updated_at' => now(),
        ]));
        echo "✓ تمت إضافة: {$city['name_ar']}\n";
    } else {
        echo "- المدينة موجودة مسبقاً: {$city['name_ar']}\n";
    }
}

echo "\n✅ تم إضافة " . count($cities) . " مدينة بنجاح!\n";
