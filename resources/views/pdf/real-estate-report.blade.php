<!DOCTYPE html>
<html dir="rtl" lang="ar">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>تقرير المراجعة العقارية</title>
    <style>
        body {
            font-family: 'dejavusans', sans-serif;
            direction: rtl;
            text-align: right;
            color: #1a1a1a;
            line-height: 1.8;
        }

        .header {
            text-align: center;
            padding: 30px 0;
            border-bottom: 4px solid #1A5F3F;
            margin-bottom: 40px;
        }

        .header h1 {
            color: #1A5F3F;
            font-size: 28px;
            font-weight: bold;
            margin: 0 0 10px 0;
        }

        .header p {
            color: #666;
            font-size: 14px;
            margin: 5px 0;
        }

        .logo {
            max-width: 120px;
            margin-bottom: 20px;
        }

        .section {
            margin: 30px 0;
            padding: 20px;
            background: #f9f9f9;
            border-right: 5px solid #1A5F3F;
            border-radius: 8px;
        }

        .section h2 {
            color: #1A5F3F;
            font-size: 20px;
            font-weight: bold;
            margin: 0 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #ddd;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin: 15px 0;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            padding: 10px 15px 10px 0;
            font-weight: bold;
            color: #444;
            width: 35%;
            background: #f0f0f0;
            border-bottom: 1px solid #ddd;
        }

        .info-value {
            display: table-cell;
            padding: 10px 15px;
            color: #333;
            border-bottom: 1px solid #ddd;
        }

        .checkpoints {
            margin: 20px 0;
        }

        .checkpoint-item {
            padding: 15px;
            margin: 10px 0;
            background: white;
            border: 1px solid #e0e0e0;
            border-right: 4px solid #28a745;
            border-radius: 6px;
            position: relative;
        }

        .checkpoint-number {
            display: inline-block;
            width: 35px;
            height: 35px;
            line-height: 35px;
            text-align: center;
            background: #28a745;
            color: white;
            border-radius: 50%;
            font-weight: bold;
            margin-left: 15px;
            float: right;
        }

        .checkpoint-text {
            padding-right: 55px;
            font-size: 15px;
            line-height: 1.8;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #999;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            background: #28a745;
            color: white;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .stats-box {
            background: linear-gradient(135deg, #1A5F3F 0%, #2d7a56 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: center;
        }

        .stats-box h3 {
            margin: 0 0 10px 0;
            font-size: 18px;
        }

        .stats-box p {
            margin: 5px 0;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>تقرير المراجعة العقارية</h1>
        <p>تقرير معتمد لعرض الأسهم العقارية</p>
        <p><strong>رقم العرض:</strong> #{{ $offer->id }}</p>
        <p><strong>تاريخ الإصدار:</strong> {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <!-- معلومات العرض -->
    <div class="section">
        <h2>معلومات العرض</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">عنوان العرض</div>
                <div class="info-value">{{ $offer->title_ar ?? $offer->title }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">الوصف</div>
                <div class="info-value">{{ $offer->description_ar ?? $offer->description }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">نوع العقار</div>
                <div class="info-value">{{ $offer->property_type ?? 'غير محدد' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">الموقع</div>
                <div class="info-value">{{ $offer->city }}, السعودية</div>
            </div>
            <div class="info-row">
                <div class="info-label">العنوان</div>
                <div class="info-value">{{ $offer->address ?? 'غير محدد' }}</div>
            </div>
        </div>
    </div>

    <!-- معلومات الأسهم -->
    <div class="section">
        <h2>معلومات الأسهم</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">إجمالي الأسهم</div>
                <div class="info-value">{{ number_format($offer->total_shares) }} سهم</div>
            </div>
            <div class="info-row">
                <div class="info-label">الأسهم المتاحة</div>
                <div class="info-value">{{ number_format($offer->available_shares) }} سهم</div>
            </div>
            <div class="info-row">
                <div class="info-label">سعر السهم</div>
                <div class="info-value">{{ number_format($offer->price_per_share, 2) }} {{ $offer->currency }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">القيمة الإجمالية</div>
                <div class="info-value">{{ number_format($offer->total_shares * $offer->price_per_share, 2) }}
                    {{ $offer->currency }}</div>
            </div>
        </div>
    </div>

    <!-- معلومات المشترك -->
    @if ($tenant)
        <div class="section">
            <h2>معلومات المشترك</h2>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">اسم المنشأة</div>
                    <div class="info-value">{{ $tenant->name ?? 'غير محدد' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">النطاق</div>
                    <div class="info-value">{{ $tenant->domain ?? 'غير محدد' }}</div>
                </div>
            </div>
        </div>
    @endif

    <!-- نقاط المراجعة العقارية -->
    <div class="section">
        <h2>نقاط المراجعة العقارية المعتمدة</h2>
        <p style="margin-bottom: 20px; color: #666;">
            <span class="badge">تم التحقق ✓</span>
            تم مراجعة واعتماد العقار بناءً على النقاط التالية:
        </p>

        <div class="checkpoints">
            @foreach ($checkpoints as $index => $checkpoint)
                <div class="checkpoint-item">
                    <span class="checkpoint-number">{{ $index + 1 }}</span>
                    <div class="checkpoint-text">{{ $checkpoint->checkpoint_text }}</div>
                </div>
            @endforeach
        </div>

        <div class="stats-box">
            <h3>الاعتماد النهائي</h3>
            <p>تم اعتماد هذا العرض بعد استيفاء {{ $checkpoints->count() }} نقطة من نقاط المراجعة العقارية</p>
            <p><strong>حالة الاعتماد:</strong> معتمد نهائياً ✓</p>
            <p><strong>تاريخ الاعتماد:</strong> {{ $offer->real_estate_reviewed_at?->format('Y-m-d') ?? 'غير محدد' }}
            </p>
        </div>
    </div>

    <div class="footer">
        <p>هذا التقرير صادر عن نظام إدارة الأسهم العقارية - {{ now()->format('Y') }}</p>
        <p>جميع الحقوق محفوظة</p>
    </div>
</body>

</html>
