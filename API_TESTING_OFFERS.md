# 🧪 اختبار إضافة العروض في Postman

## الخطوات بالتفصيل

---

## 📝 الخطوة 1: التسجيل أو تسجيل الدخول

### **A. التسجيل (إذا لم يكن لديك حساب)**

```
Method: POST
URL: http://localhost:8000/api/v1/auth/register
```

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Body (JSON):**
```json
{
    "tenant_domain": "sahm_4",
    "name": "أحمد محمد",
    "email": "admin@test.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**أو B. تسجيل الدخول (إذا كان لديك حساب)**

```
Method: POST
URL: http://localhost:8000/api/v1/auth/login
```

**Body (JSON):**
```json
{
    "tenant_domain": "sahm_4",
    "email": "admin@test.com",
    "password": "password123"
}
```

### ✅ Save the Token from Response:
```json
{
    "success": true,
    "data": {
        "token": "1|abcdefghijklmnopqrstuvwxyz",  ← Save this!
        ...
    }
}
```

---

## 📝 الخطوة 2: إضافة عرض جديد

### **Setup في Postman:**

```
Method: POST
URL: http://localhost:8000/api/v1/offers
```

### **Headers:**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer YOUR_TOKEN_HERE  ← Put token here!
```

### **Body (اختر raw → JSON):**
```json
{
    "tenant_domain": "sahm_4",
    "title": "Luxury Villa Investment",
    "title_ar": "فرصة استثمارية في فيلا فاخرة",
    "description": "Premium villa in Riyadh",
    "description_ar": "فيلا فاخرة في الرياض بموقع مميز",
    "country": "Saudi Arabia",
    "city": "الرياض",
    "address": "حي النرجس، شارع التحلية",
    "total_shares": 1000,
    "price_per_share": 500,
    "currency": "SAR",
    "status": "active",
    "starts_at": "2026-02-15",
    "ends_at": "2026-12-31"
}
```

### اضغط **Send** 🚀

### ✅ النتيجة المتوقعة:
```json
{
    "success": true,
    "message": "تم إضافة العرض بنجاح",
    "data": {
        "tenant_offer_id": 5,
        "central_offer_id": 12,
        "title": "فرصة استثمارية في فيلا فاخرة",
        "city": "الرياض",
        "total_shares": 1000,
        "price_per_share": 500
    }
}
```

---

## 📝 الخطوة 3: التحقق من العرض المضاف

### **عرض جميع العروض:**

```
Method: GET
URL: http://localhost:8000/api/v1/offers
Headers: Accept: application/json
```

### **عرض التفاصيل:**

```
Method: GET
URL: http://localhost:8000/api/v1/offers/5
Headers: Accept: application/json
```

---

## 🔄 خطوات إضافية (اختياري)

### **تحديث العرض:**

```
Method: PUT
URL: http://localhost:8000/api/v1/offers/5
Headers:
   Content-Type: application/json
   Accept: application/json
   Authorization: Bearer YOUR_TOKEN
```

**Body:**
```json
{
    "tenant_domain": "sahm_4",
    "title_ar": "فرصة استثمارية محدثة",
    "price_per_share": 550,
    "status": "active"
}
```

### **حذف العرض:**

```
Method: DELETE
URL: http://localhost:8000/api/v1/offers/5
Headers:
   Content-Type: application/json
   Authorization: Bearer YOUR_TOKEN
```

**Body:**
```json
{
    "tenant_domain": "sahm_4"
}
```

---

## 🎯 ملخص الخطوات:

1. ✅ **تسجيل الدخول** → احصل على Token
2. ✅ **أضف Token في Headers** → Authorization: Bearer {token}
3. ✅ **حدد tenant_domain** → sahm_4
4. ✅ **أرسل البيانات** → أضف العرض
5. ✅ **تحقق** → استعرض العروض

---

## ❌ الأخطاء المحتملة:

### خطأ: "Unauthenticated"
**Solution:** تأكد من إضافة Token في الـ Headers

### خطأ: "النطاق غير موجود"
**Solution:** استخدم `sahm_4` كـ tenant_domain

### خطأ: "validation errors"
**Solution:** تحقق من أن جميع الحقول المطلوبة موجودة

---

## 📋 الحقول المطلوبة:

- **مطلوبة (Required):**
  - tenant_domain
  - title
  - title_ar
  - city
  - total_shares
  - price_per_share

- **اختيارية (Optional):**
  - description
  - description_ar
  - country
  - address
  - currency (افتراضي: SAR)
  - status (افتراضي: active)
  - starts_at
  - ends_at

---

## 🔒 كيف يعمل النظام:

1. **التحقق من المستخدم** → عبر Token
2. **التحقق من Tenant** → sahm_4 موجود في قاعدة البيانات المركزية
3. **إضافة في قاعدة البيانات الفرعية** → tenant database (sahm_4)
4. **نسخ إلى قاعدة البيانات المركزية** → للعرض في الموقع الرئيسي
5. **الربط بينهما** → central_offer_id

---

**جرب الآن!** 🚀
