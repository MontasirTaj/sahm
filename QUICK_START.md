# 🚀 دليل الاختبار السريع - تطبيق المشترين

## 📝 معلومات مهمة
✅ **تم تبسيط النظام**: المشترون لا يحتاجون `tenant_domain` بعد الآن!  
✅ **جميع APIs جاهزة**: 18 endpoint مسجلة بنجاح  
✅ **قاعدة البيانات المركزية**: جميع عمليات المشترين في مكان واحد

---

## 🎯 خطوات الاختبار في Postman

### الخطوة 1: تسجيل مشتري جديد

```http
POST http://localhost:8000/api/v1/auth/register
Content-Type: application/json
```

**Body:**
```json
{
    "name": "محمد أحمد",
    "email": "buyer1@test.com",
    "password": "12345678",
    "password_confirmation": "12345678"
}
```

**✅ Expected Result:**
```json
{
    "success": true,
    "message": "تم التسجيل بنجاح",
    "data": {
        "user": {
            "id": 1,
            "name": "محمد أحمد",
            "email": "buyer1@test.com",
            "avatar": null
        },
        "token": "1|xxxxxxxxxxxx"  ← Save this token
    }
}
```

---

### الخطوة 2: تسجيل دخول

```http
POST http://localhost:8000/api/v1/auth/login
Content-Type: application/json
```

**Body:**
```json
{
    "email": "buyer1@test.com",
    "password": "12345678"
}
```

**✅ Expected Result:**
```json
{
    "success": true,
    "message": "تم تسجيل الدخول بنجاح",
    "data": {
        "user": {...},
        "token": "2|yyyyyyyyyyyy"  ← Use this token for next requests
    }
}
```

---

### الخطوة 3: عرض جميع العروض (بدون Token)

```http
GET http://localhost:8000/api/v1/offers?status=active
```

**✅ Expected Result:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "مشروع سكني راقي",
            "city": "الرياض",
            "available_shares": 750,
            "price_per_share": 5000.00,
            "currency": "SAR",
            "status": "active"
        }
    ]
}
```

**⚠️ If list is empty:**
- Check if there's data in `share_offers` table in central database
- You can add a test offer via tenant interface

---

### الخطوة 4: تفاصيل عرض محدد

```http
GET http://localhost:8000/api/v1/offers/1
```

**✅ Expected Result:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "مشروع سكني راقي",
        "description": "مشروع سكني فاخر...",
        "city": "الرياض",
        "total_shares": 1000,
        "available_shares": 750,
        "sold_shares": 250,
        "price_per_share": 5000.00,
        "sold_percentage": 25
    }
}
```

---

### الخطوة 5: شراء أسهم (مع Token)

```http
POST http://localhost:8000/api/v1/purchase
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json
```

**Body:**
```json
{
    "offer_id": 1,
    "shares_count": 5,
    "payment_method": "credit_card"
}
```

**✅ Expected Result:**
```json
{
    "success": true,
    "message": "تم إنشاء طلب الشراء بنجاح. يرجى إكمال الدفع.",
    "data": {
        "operation_id": 1,  ← Save this number
        "external_reference": "OP-65F8A4B2",
        "shares_count": 5,
        "price_per_share": 5000.00,
        "amount_total": 25000.00,
        "status": "pending"
    }
}
```

---

### الخطوة 6: تأكيد الدفع

```http
POST http://localhost:8000/api/v1/purchase/confirm-payment
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json
```

**Body:**
```json
{
    "operation_id": 1,
    "payment_id": "TEST_PAY_123456",
    "payment_status": "completed"
}
```

**✅ Expected Result:**
```json
{
    "success": true,
    "message": "تم إتمام عملية الشراء بنجاح",
    "data": {
        "operation_id": 1,
        "status": "completed",
        "external_reference": "OP-65F8A4B2"
    }
}
```

---

### الخطوة 7: عرض أسهمي المملوكة

```http
GET http://localhost:8000/api/v1/buyer/my-shares
Authorization: Bearer YOUR_TOKEN_HERE
```

**✅ النتيجة المتوقعة:**
```json
{
    "success": true,
    "data": [
        {
            "offer_id": 1,
            "offer_title": "مشروع سكني راقي",
            "offer_city": "الرياض",
            "total_shares": 5,
            "total_invested": 25000.00,
            "average_price": 5000.00,
            "current_price": 5000.00,
            "operations_count": 1
        }
    ],
    "summary": {
        "total_offers": 1,
        "total_shares": 5,
        "total_invested": 25000.00
    }
}
```

---

### الخطوة 8: لوحة التحكم

```http
GET http://localhost:8000/api/v1/buyer/dashboard
Authorization: Bearer YOUR_TOKEN_HERE
```

**✅ Expected Result:**
```json
{
    "success": true,
    "data": {
        "stats": {
            "total_operations": 1,
            "completed_operations": 1,
            "pending_operations": 0,
            "total_shares_owned": 5,
            "total_spent": 25000.00
        },
        "recent_operations": [...]
    }
}
```

---

### الخطوة 9: عرض الملف الشخصي

```http
GET http://localhost:8000/api/v1/auth/profile
Authorization: Bearer YOUR_TOKEN_HERE
```

**✅ Expected Result:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "محمد أحمد",
        "email": "buyer1@test.com",
        "avatar": null,
        "created_at": "2024-02-15"
    }
}
```

---

### الخطوة 10: تحديث الملف الشخصي

```http
PUT http://localhost:8000/api/v1/auth/profile
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json
```

**Body (all fields optional):**
```json
{
    "name": "محمد أحمد السعيد",
    "email": "buyer.new@test.com",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

---

## 🔍 استكشاف الأخطاء

### Problem: "Unauthenticated"
**Solution:**
- Make sure to add Header: `Authorization: Bearer YOUR_TOKEN`
- Check token validity
- Try new login

### Problem: "العرض غير موجود"
**Solution:**
- Check if data exists in `share_offers` table
- Make sure to use correct `offer_id`
- Try GET /api/v1/offers to get offers list

### Problem: "عدد الأسهم المتاحة غير كافٍ"
**Solution:**
- Check `available_shares` in offer details
- Reduce requested shares count

### Problem: Route not found
**Solution:**
```bash
php artisan route:list --path=api
```
Make sure all routes are registered (should see 18+ routes)

---

## 🗄️ التحقق من قاعدة البيانات

### 1. التحقق من المشترين
```sql
SELECT * FROM users WHERE email LIKE '%test.com';
```

### 2. التحقق من العروض
```sql
SELECT id, title, city, available_shares, status FROM share_offers;
```

### 3. التحقق من العمليات
```sql
SELECT id, buyer_id, offer_id, shares_count, status, amount_total 
FROM share_operations 
ORDER BY created_at DESC;
```

### 4. التحقق من Tokens
```sql
SELECT id, tokenable_id, name, created_at, last_used_at 
FROM personal_access_tokens 
ORDER BY id DESC;
```

---

## 📚 ملفات التوثيق الكاملة

1. **API_BUYERS_GUIDE.md** - دليل شامل للمشترين مع جميع الـ endpoints
2. **API_PURCHASE_GUIDE.md** - دليل كامل لعمليات الشراء
3. **API_UPDATES_SUMMARY.md** - ملخص جميع التحديثات
4. **API_DOCUMENTATION.md** - توثيق شامل لجميع الـ APIs
5. **API_TESTING.md** - أمثلة اختبار متقدمة

---

## ✅ Checklist قبل البدء

- [ ] قاعدة البيانات المركزية موجودة وتحتوي على:
  - [ ] جدول `users`
  - [ ] جدول `share_offers` (مع بيانات تجريبية)
  - [ ] جدول `share_operations`
  - [ ] جدول `personal_access_tokens`

- [ ] Laravel يعمل بشكل صحيح
  - [ ] `php artisan serve` يعمل
  - [ ] الاتصال بقاعدة البيانات صحيح

- [ ] APIs مسجلة
  - [ ] `php artisan route:list --path=api` يظهر 18+ routes

- [ ] Postman جاهز
  - [ ] Environment متغيرات: base_url, token
  - [ ] Headers جاهزة

---

## 🎉 مبروك!

إذا اتممت جميع الخطوات بنجاح، فأنت الآن جاهز لتطوير تطبيق Flutter وربطه بالـ APIs.

**Next Steps:**
1. ✅ تكامل مع بوابة دفع حقيقية (Stripe / PayPal / HyperPay)
2. ✅ إضافة صور للعروض
3. ✅ إشعارات Push للمشترين
4. ✅ تقارير ولوحات تحكم متقدمة
5. ✅ نظام تقييم ومراجعات

---

## 🆘 الدعم

إذا واجهت أي مشكلة:
1. تحقق من ملفات logs في `storage/logs/laravel.log`
2. راجع ملفات التوثيق المذكورة أعلاه
3. تأكد من أن جميع الـ migrations قد تم تشغيلها
4. تحقق من الـ console في Postman للأخطاء التفصيلية

**جميع APIs جاهزة للاستخدام! 🚀**
