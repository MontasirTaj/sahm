# 🧪 دليل اختبار رفع الصور للعروض

## ⚠️ تحديث مهم: الصورة أصبحت إلزامية!

النظام تم تحديثه:
- ✅ **لا يمكن إضافة عرض بدون صورة غلاف**
- ✅ **لا يمكن حذف جميع الصور من العرض**
- ✅ **يجب أن يحتوي كل عرض على صورة واحدة على الأقل**

---

## ✅ الإعداد الأولي

النظام جاهز الآن! تم إضافة:
- ✅ دعم رفع الصور في Controller
- ✅ Route جديد لرفع الصور
- ✅ Storage link جاهز
- ✅ التوثيق محدّث
- ✅ **الصورة إلزامية** عند إضافة عرض جديد

---

## 🎯 3 طرق لإضافة صور

### الطريقة 1️⃣: إضافة عرض مع صورة مباشرة

**الخطوات في Postman:**

1. **افتح Tab جديد**

2. **اختر Method:** `POST`

3. **أدخل URL:**
   ```
   http://sahm.test/api/v1/offers
   ```

4. **في Headers:**
   ```
   Accept: application/json
   Authorization: Bearer YOUR_TOKEN_HERE
   ```
   ⚠️ **لا تضيف** `Content-Type` (سيُضاف تلقائياً)

5. **في Body:**
   - اختر **form-data** (📌 مهم جداً!)
   - أضف الحقول التالية:

   | Key | Type | Value |
   |-----|------|-------|
   | tenant_domain | Text | sahm_4 |
   | title | Text | Test Villa with Image |
   | title_ar | Text | فيلا تجريبية مع صورة |
   | description | Text | Testing image upload |
   | description_ar | Text | اختبار رفع الصورة |
   | city | Text | الرياض |
   | total_shares | Text | 100 |
   | price_per_share | Text | 1000 |
   | currency | Text | SAR |
   | status | Text | active |
   | **cover_image** | **File** 📷 | **[Select Files]** |

6. **لإضافة الصورة:**
   - في صف `cover_image`
   - غيّر النوع من "Text" إلى **"File"** من القائمة المنسدلة
   - اضغط "Select Files"
   - اختر صورة من جهازك (JPG, PNG, GIF, WEBP)

7. **اضغط Send** 🚀

### ✅ النتيجة المتوقعة:
```json
{
    "success": true,
    "message": "تم إضافة العرض بنجاح",
    "data": {
        "tenant_offer_id": 5,
        "central_offer_id": 12,
        "title": "فيلا تجريبية مع صورة",
        "city": "الرياض",
        "total_shares": 100,
        "price_per_share": 1000
    }
}
```

---

### الطريقة 2️⃣: رفع صورة لعرض موجود

إذا نسيت إضافة صورة، يمكنك رفعها لاحقاً:

1. **Method:** `POST`

2. **URL:**
   ```
   http://sahm.test/api/v1/offers/5/upload-image
   ```
   ⚠️ استبدل `5` برقم العرض الفعلي

3. **Headers:**
   ```
   Accept: application/json
   Authorization: Bearer YOUR_TOKEN
   ```

4. **Body (form-data):**
   | Key | Type | Value |
   |-----|------|-------|
   | tenant_domain | Text | sahm_4 |
   | cover_image | File | [اختر الصورة] |

5. **Send** 🚀

### ✅ النتيجة المتوقعة:
```json
{
    "success": true,
    "message": "تم رفع الصورة بنجاح",
    "data": {
        "offer_id": 5,
        "cover_image": "http://sahm.test/storage/offers/1739746123_abc123.jpg"
    }
}
```

**💡 يمكنك فتح رابط الصورة في المتصفح للتحقق منها!**

---

### الطريقة 3️⃣: تحديث صورة موجودة

1. **Method:** `PUT`

2. **URL:**
   ```
   http://sahm.test/api/v1/offers/5
   ```

3. **Headers:**
   ```
   Accept: application/json
   Authorization: Bearer YOUR_TOKEN
   ```

4. **Body (form-data):**
   | Key | Type | Value |
   |-----|------|-------|
   | tenant_domain | Text | sahm_4 |
   | cover_image | File | [صورة جديدة] |

   يمكنك إضافة حقول أخرى للتحديث مثل:
   - title_ar
   - price_per_share
   - status

5. **Send** 🚀

---

## 📸 متطلبات الصورة

✅ **الصيغ المقبولة:**
- JPEG / JPG
- PNG
- GIF
- WEBP

✅ **الحجم الأقصى:** 5 ميجابايت (5120 KB)

❌ **غير مقبول:**
- PDF
- BMP
- SVG
- ملفات أكبر من 5 ميجابايت

---

## 🔍 التحقق من الصورة المرفوعة

### الطريقة 1: عبر API
```
GET http://sahm.test/api/v1/offers/5
```

**الاستجابة ستحتوي على:**
```json
{
    "data": {
        "id": 5,
        "title_ar": "فيلا تجريبية مع صورة",
        "cover_image": "storage/offers/1739746123_abc123.jpg",
        ...
    }
}
```

### الطريقة 2: عبر المتصفح
افتح الرابط مباشرة:
```
http://sahm.test/storage/offers/1739746123_abc123.jpg
```

### الطريقة 3: عبر مجلدات النظام
```
C:\laragon\www\sahm\storage\app\public\offers\
```

---

## ❌ استكشاف الأخطاء

### خطأ: "The cover image must be an image"
**الحل:**
- تأكد من اختيار **File** وليس Text في Postman
- تأكد من صيغة الصورة (JPG, PNG, GIF, WEBP)

### خطأ: "The cover image may not be greater than 5120 kilobytes"
**الحل:**
- قلل حجم الصورة إلى أقل من 5 ميجابايت
- استخدم أداة ضغط الصور أونلاين

### خطأ: "Unauthenticated"
**الحل:**
- تأكد من إضافة Token في Headers
- تأكد من صيغة: `Authorization: Bearer {token}`

### خطأ: "النطاق غير موجود"
**الحل:**
- تأكد من وجود tenant بـ Subdomain = "sahm_4"
- استخدم Subdomain الصحيح من قاعدة البيانات

### الصورة لا تظهر في المتصفح
**الحل:**
- تحقق من أن `php artisan storage:link` تم تنفيذه
- تحقق من وجود المجلد: `public/storage/offers/`

---

## 🎬 سيناريو اختبار كامل

### الخطوة 1: تسجيل الدخول
```
POST http://sahm.test/api/v1/auth/tenant-login

Body (JSON):
{
    "tenant_domain": "sahm_4",
    "email": "admin@test.com",
    "password": "password123"
}
```
**احفظ التوكن!** ✅

### الخطوة 2: إضافة عرض مع صورة
```
POST http://sahm.test/api/v1/offers

Headers:
Authorization: Bearer {token}
Accept: application/json

Body (form-data):
- tenant_domain: sahm_4
- title: Villa Test
- title_ar: فيلا اختبار
- city: الرياض
- total_shares: 100
- price_per_share: 1000
- cover_image: [اختر صورة.jpg]
```

### الخطوة 3: التحقق من العرض
```
GET http://sahm.test/api/v1/offers
```

### الخطوة 4: فتح الصورة في المتصفح
```
http://sahm.test/storage/offers/YOUR_IMAGE_NAME.jpg
```

---

## 💾 مسار حفظ الصور

**في النظام:**
```
C:\laragon\www\sahm\storage\app\public\offers\
```

**عبر الويب:**
```
http://sahm.test/storage/offers/filename.jpg
```

**في قاعدة البيانات:**
```
storage/offers/filename.jpg
```

---

## 📝 ملاحظات مهمة

1. ✅ عند إضافة صورة جديدة، الصورة القديمة تُحذف تلقائياً
2. ⚠️ **لا يمكن إضافة عرض بدون صورة (الصورة إلزامية)**
3. ⚠️ **لا يمكن حذف جميع الصور من العرض (يجب وجود صورة واحدة على الأقل)**
4. ✅ الصورة تُضاف في قاعدة البيانات الفرعية والمركزية
5. ✅ استخدم form-data دائماً عند رفع ملفات
6. ✅ لا تستخدم raw JSON مع الصور

---

## 🎯 Endpoints الصور

| Method | Endpoint | الوصف |
|--------|----------|-------|
| POST | `/api/v1/offers` | إضافة عرض (مع/بدون صورة) |
| POST | `/api/v1/offers/{id}/upload-image` | رفع صورة لعرض موجود |
| PUT | `/api/v1/offers/{id}` | تحديث عرض (مع/بدون صورة) |

---

## ✨ جاهز للتجربة!

اتبع **الطريقة 1** أعلاه لإضافة أول عرض مع صورة الآن! 🚀

**ستجد التفاصيل الكاملة في:** [API_TESTING_OFFERS.md](API_TESTING_OFFERS.md)
