# دليل تشغيل API - سهمي

## نظرة عامة

تم إنشاء APIs كاملة لتطبيق الموبايل منفصلة تماماً عن الموقع الإلكتروني.

---

## الملفات المضافة

### Controllers
```
app/Http/Controllers/Api/
├── AuthController.php          (تسجيل، دخول، خروج، تحديث بيانات)
├── OfferController.php          (عرض وتفاصيل العروض)
├── PurchaseController.php       (شراء وتأكيد الدفع)
└── BuyerController.php          (لوحة التحكم والبيانات)
```

### Resources
```
app/Http/Resources/
├── OfferResource.php           (تنسيق بيانات العروض)
├── OperationResource.php       (تنسيق بيانات العمليات)
└── UserResource.php            (تنسيق بيانات المستخدمين)
```

### Middleware
```
app/Http/Middleware/
└── SetTenantFromHeader.php     (إدارة Tenant context)
```

### Routes
```
routes/
└── api.php                     (جميع API endpoints)
```

### Documentation
```
API_DOCUMENTATION.md            (التوثيق الكامل بالعربية)
```

---

## خطوات التفعيل

### 1. تحديث النماذج
تم إضافة `HasApiTokens` trait لـ:
- `app/Models/TenantUser.php`
- `app/Models/User.php`

### 2. إنشاء جداول Sanctum
```bash
php artisan migrate
```

هذا سينشئ جدول `personal_access_tokens` في قاعدة البيانات.

### 3. تأكد من وجود Sanctum في config/app.php
```php
'providers' => [
    // ...
    Laravel\Sanctum\SanctumServiceProvider::class,
],
```

### 4. نشر ملفات Sanctum (اختياري)
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 5. مسح الـ Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

---

## اختبار الـ API

### 1. Health Check
```bash
curl https://your-domain.com/api/health
```

### 2. تسجيل مستخدم جديد
```bash
curl -X POST https://your-domain.com/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "tenant_domain": "your-tenant-domain.com",
    "name": "Ahmed Test",
    "email": "ahmed@test.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### 3. تسجيل الدخول
```bash
curl -X POST https://your-domain.com/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "tenant_domain": "your-tenant-domain.com",
    "email": "ahmed@test.com",
    "password": "password123"
  }'
```

Save the `token` from the response.

### 4. الحصول على العروض
```bash
curl -X GET "https://your-domain.com/api/v1/offers?tenant_domain=your-tenant-domain.com" \
  -H "Accept: application/json"
```

### 5. شراء أسهم (يتطلب Token)
```bash
curl -X POST https://your-domain.com/api/v1/purchase \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "tenant_domain": "your-tenant-domain.com",
    "offer_id": 1,
    "shares_count": 5,
    "payment_method": "credit_card"
  }'
```

---

## البيئة والإعدادات

### Config في .env
تأكد من وجود:
```env
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,your-domain.com
```

---

## API Base URL

```
Development: http://localhost:8000/api/
Production:  https://your-domain.com/api/
```

---

## الحماية والأمان

1. **CORS**: تأكد من إعداد CORS بشكل صحيح في `config/cors.php`
2. **Rate Limiting**: Laravel يطبق rate limiting افتراضياً (60 requests/minute)
3. **Validation**: جميع الـ inputs تمر بـ validation صارم
4. **Database Transactions**: العمليات المالية تستخدم transactions
5. **Token Security**: استخدم HTTPS في الإنتاج لحماية الـ tokens

---

## Endpoints المتاحة

### Authentication
- POST `/api/v1/auth/register` - التسجيل
- POST `/api/v1/auth/login` - الدخول
- POST `/api/v1/auth/logout` - الخروج ✓
- GET `/api/v1/auth/profile` - البيانات ✓
- PUT `/api/v1/auth/profile` - التحديث ✓

### Offers
- GET `/api/v1/offers` - قائمة العروض
- GET `/api/v1/offers/{id}` - تفاصيل عرض
- GET `/api/v1/offers/meta/cities` - المدن
- GET `/api/v1/offers/meta/statistics` - الإحصائيات

### Purchase
- POST `/api/v1/purchase` - شراء ✓
- POST `/api/v1/purchase/confirm-payment` - تأكيد الدفع ✓
- POST `/api/v1/purchase/{id}/cancel` - إلغاء ✓

### Buyer
- GET `/api/v1/buyer/dashboard` - لوحة التحكم ✓
- GET `/api/v1/buyer/operations` - العمليات ✓
- GET `/api/v1/buyer/operations/{id}` - تفاصيل عملية ✓
- GET `/api/v1/buyer/my-shares` - أسهمي ✓

✓ = يتطلب Authentication

---

## التوثيق الكامل

راجع ملف `API_DOCUMENTATION.md` للحصول على:
- تفاصيل كاملة لكل endpoint
- أمثلة Request/Response
- أكواد الحالة
- معالجة الأخطاء
- أمثلة cURL

---

## الدعم الفني

للمزيد من المساعدة:
1. راجع التوثيق الكامل في `API_DOCUMENTATION.md`
2. تحقق من الـ logs في `storage/logs/laravel.log`
3. استخدم `php artisan route:list` لرؤية جميع الـ routes

---

## ملاحظات مهمة

1. **لا تؤثر على الموقع**: جميع الـ API routes تحت `/api/` ومنفصلة تماماً
2. **Multi-Tenant**: كل طلب يجب أن يحدد `tenant_domain`
3. **Database Connections**: يتم الانتقال التلقائي بين Central و Tenant databases
4. **Testing**: اختبر على بيئة development قبل الإنتاج
5. **Security**: استخدم HTTPS في الإنتاج دائماً

---

## الخطوات التالية

1. ✅ تفعيل Sanctum migrations
2. ✅ اختبار التسجيل وتسجيل الدخول
3. ✅ اختبار عرض العروض
4. ✅ اختبار عملية الشراء
5. ⏭️ دمج مع تطبيق الموبايل
6. ⏭️ إعداد Payment Gateway الفعلي
7. ⏭️ إضافة Notifications (Push/Email)
8. ⏭️ إضافة Analytics و Reporting

---

تم إنشاء هذا النظام بعناية ليكون آمناً، قابلاً للتوسع، وسهل الاستخدام لمطوري تطبيقات الموبايل.
