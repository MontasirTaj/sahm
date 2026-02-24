<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار Dropdown المدن</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 40px;
            background: #f5f7fa;
        }
        .test-card {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        h2 {
            color: #1A5F3F;
            border-bottom: 3px solid #1A5F3F;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
        .badge-info {
            background: #17a2b8;
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 13px;
            margin-bottom: 15px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="test-card">
        <h2>🏙️ اختبار Dropdown المدن</h2>
        
        <div class="badge-info mb-3">
            <i>هذا مثال حي على كيفية ظهور قائمة المدن في نموذج إنشاء العرض</i>
        </div>

        <form>
            <div class="form-group">
                <label for="city"><strong>المدينة</strong> <span style="color:red">*</span></label>
                <select name="city" id="city" class="form-control" required>
                    <option value="">-- اختر المدينة --</option>
                    <?php
                    require __DIR__ . '/vendor/autoload.php';
                    
                    // Bootstrap Laravel
                    $app = require_once __DIR__ . '/bootstrap/app.php';
                    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
                    
                    // Get cities
                    $cities = \App\Models\City::on('central')->active()->ordered()->get();
                    
                    foreach ($cities as $city) {
                        echo '<option value="' . htmlspecialchars($city->name) . '">';
                        echo htmlspecialchars($city->name);
                        if ($city->region) {
                            echo ' - ' . htmlspecialchars($city->region);
                        }
                        echo '</option>';
                    }
                    ?>
                </select>
                <small class="form-text text-muted">
                    اختر المدينة من القائمة المنسدلة
                </small>
            </div>

            <hr>

            <div class="alert alert-success">
                <h6><strong>✅ معلومات النظام:</strong></h6>
                <ul class="mb-0">
                    <li>عدد المدن المتاحة: <strong><?php echo $cities->count(); ?></strong></li>
                    <li>المدن مرتبة حسب: <strong>الترتيب والاسم</strong></li>
                    <li>المصدر: <strong>قاعدة البيانات المركزية (central)</strong></li>
                    <li>الحالة: <strong>المدن النشطة فقط</strong></li>
                </ul>
            </div>

            <div class="alert alert-info">
                <h6><strong>ℹ️ ملاحظات:</strong></h6>
                <ul class="mb-0">
                    <li>هذه القائمة نفسها ستظهر في نموذج إنشاء/تعديل العروض</li>
                    <li>يمكن للأدمن إضافة مدن جديدة من لوحة التحكم</li>
                    <li>المدن المعطلة لن تظهر في القائمة</li>
                    <li>يمكن تغيير ترتيب المدن من لوحة تحكم الأدمن</li>
                </ul>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('city').addEventListener('change', function() {
            if (this.value) {
                alert('✅ تم اختيار المدينة: ' + this.value);
            }
        });
    </script>
</body>
</html>
