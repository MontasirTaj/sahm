<?php
// Check if MDI CSS file is accessible
$cssPath = __DIR__ . '/public/assets/plugins/@mdi/font/css/materialdesignicons.min.css';

echo "==============================================\n";
echo "فحص Material Design Icons\n";
echo "==============================================\n\n";

echo "1. ファイル الملف:\n";
echo "المسار: $cssPath\n";
echo "موجود: " . (file_exists($cssPath) ? 'نعم ✅' : 'لا ❌') . "\n";
if (file_exists($cssPath)) {
    echo "الحجم: " . number_format(filesize($cssPath) / 1024, 2) . " KB\n";
}

echo "\n2. فحص classes الأيقونات:\n";
if (file_exists($cssPath)) {
    $content = file_get_contents($cssPath);
    
    $icons = [
        'mdi-cart-arrow-down' => 'شراء',
        'mdi-cash-multiple' => 'بيع',
        'mdi-swap-horizontal' => 'تحويل',
        'mdi-cash-plus' => 'أرباح',
    ];
    
    foreach ($icons as $icon => $label) {
        $found = strpos($content, $icon) !== false;
        echo "$label ($icon): " . ($found ? '✅ موجود' : '❌ غير موجود') . "\n";
    }
}

echo "\n3. فحص URL في المتصفح:\n";
echo "http://sahm.test/assets/plugins/@mdi/font/css/materialdesignicons.min.css\n";
echo "\n";
