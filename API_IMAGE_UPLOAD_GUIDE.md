# دليل رفع الصور للعروض - API Guide

## 📋 نظرة عامة

هذا الدليل يشرح كيفية استخدام الـ APIs لإضافة صور للعروض. يتوفر ثلاث طرق:
1. **إضافة عرض مع صورة أو صور متعددة** (صورة واحدة على الأقل إجبارية)
2. **إضافة صورة واحدة لعرض موجود** (استبدال صورة الغلاف)
3. **إضافة صور متعددة لعرض موجود** (حتى 15 صورة إجمالي)

### ⭐ ميزة جديدة: صور متعددة عند الإنشاء
الآن يمكنك إضافة **حتى 15 صورة** مباشرة عند إنشاء عرض جديد:
- `cover_image` (إجبارية) - صورة الغلاف الرئيسية
- `images[]` (اختيارية) - حتى 14 صورة إضافية
- **المجموع الأقصى**: 15 صورة (1 غلاف + 14 إضافية)

---

## 🔐 المصادقة (Authentication)

جميع الطلبات تتطلب Bearer Token في الـ Header:

```
Authorization: Bearer {your_token}
```

---

## 1️⃣ إضافة عرض جديد مع صورة إجبارية

### Endpoint
```
POST /api/v1/offers
```

### Headers
```
Authorization: Bearer {TOKEN}
Content-Type: multipart/form-data
Accept: application/json
```

### Request Body (Form Data)

**⚠️ الصورة إجبارية - لا يمكن إنشاء عرض بدون صورة**
**⭐ جديد: يمكنك الآن إضافة صور متعددة مباشرة عند الإنشاء**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `tenant_domain` | string | ✅ Yes | نطاق المستأجر (مثال: sahm_4) |
| `title` | string | ✅ Yes | عنوان العرض |
| `title_ar` | string | ✅ Yes | العنوان بالعربية |
| `description` | string | ❌ No | وصف العرض |
| `description_ar` | string | ❌ No | الوصف بالعربية |
| `country` | string | ❌ No | الدولة (افتراضي: Saudi Arabia) |
| `city` | string | ✅ Yes | المدينة |
| `address` | string | ❌ No | العنوان التفصيلي |
| `total_shares` | integer | ✅ Yes | إجمالي عدد الأسهم (min: 1) |
| `price_per_share` | numeric | ✅ Yes | سعر السهم (min: 0) |
| `currency` | string | ❌ No | العملة (افتراضي: SAR) |
| `status` | string | ❌ No | الحالة: active, inactive, pending |
| `starts_at` | date | ❌ No | تاريخ البداية (YYYY-MM-DD) |
| `ends_at` | date | ❌ No | تاريخ النهاية (YYYY-MM-DD) |
| `cover_image` | file | ✅ **Yes** | **صورة الغلاف (إجبارية)** |
| `images[]` | file[] | ❌ **No** | **⭐ صور إضافية (حتى 14 صورة)** |

### صيغ الصور المدعومة
- jpg, jpeg, png, gif, webp
- الحد الأقصى: 5 MB لكل صورة

### مثال: cURL (صورة واحدة)

```bash
curl --location 'http://localhost/api/v1/offers' \
--header 'Authorization: Bearer 4|wPxJhT9qKxBz1RMvCN2mLpHnYkFrGsQdXcVbZaWe123abc' \
--header 'Accept: application/json' \
--form 'tenant_domain="sahm_4"' \
--form 'title="فرصة استثمارية في برج تجاري"' \
--form 'title_ar="فرصة استثمارية في برج تجاري"' \
--form 'description="Commercial tower investment opportunity"' \
--form 'description_ar="برج تجاري حديث في جدة"' \
--form 'country="Saudi Arabia"' \
--form 'city="جدة"' \
--form 'address="طريق الملك عبدالعزيز"' \
--form 'total_shares="1500"' \
--form 'price_per_share="750"' \
--form 'currency="SAR"' \
--form 'status="active"' \
--form 'starts_at="2026-03-01"' \
--form 'ends_at="2027-01-31"' \
--form 'cover_image=@"/path/to/tower-image.jpg"'
```

### ⭐ مثال: cURL (صور متعددة)

```bash
curl --location 'http://localhost/api/v1/offers' \
--header 'Authorization: Bearer 4|wPxJhT9qKxBz1RMvCN2mLpHnYkFrGsQdXcVbZaWe123abc' \
--header 'Accept: application/json' \
--form 'tenant_domain="sahm_4"' \
--form 'title="فرصة استثمارية في برج تجاري"' \
--form 'title_ar="فرصة استثمارية في برج تجاري"' \
--form 'city="جدة"' \
--form 'total_shares="1500"' \
--form 'price_per_share="750"' \
--form 'cover_image=@"/path/to/cover.jpg"' \
--form 'images[]=@"/path/to/image1.jpg"' \
--form 'images[]=@"/path/to/image2.jpg"' \
--form 'images[]=@"/path/to/image3.jpg"'
```
**ملاحظة**: يمكنك إضافة حتى 14 صورة إضافية باستخدام `images[]`

### مثال: JavaScript (Fetch API) - صورة واحدة

```javascript
const formData = new FormData();
formData.append('tenant_domain', 'sahm_4');
formData.append('title', 'فرصة استثمارية في برج تجاري');
formData.append('title_ar', 'فرصة استثمارية في برج تجاري');
formData.append('description', 'Commercial tower investment opportunity');
formData.append('description_ar', 'برج تجاري حديث في جدة');
formData.append('city', 'جدة');
formData.append('total_shares', '1500');
formData.append('price_per_share', '750');
formData.append('currency', 'SAR');
formData.append('status', 'active');

// إضافة الصورة الرئيسية (إجبارية)
const fileInput = document.querySelector('input[type="file"]');
formData.append('cover_image', fileInput.files[0]);

fetch('http://localhost/api/v1/offers', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer YOUR_TOKEN',
    'Accept': 'application/json',
  },
  body: formData
})
.then(response => response.json())
.then(data => console.log(data));
```

### ⭐ مثال: JavaScript (Fetch API) - صور متعددة

```javascript
const formData = new FormData();
formData.append('tenant_domain', 'sahm_4');
formData.append('title', 'فرصة استثمارية في برج تجاري');
formData.append('title_ar', 'فرصة استثمارية في برج تجاري');
formData.append('city', 'جدة');
formData.append('total_shares', '1500');
formData.append('price_per_share', '750');

// إضافة صورة الغلاف (إجبارية)
const coverInput = document.querySelector('#cover_image');
formData.append('cover_image', coverInput.files[0]);

// إضافة صور إضافية (اختيارية)
const imagesInput = document.querySelector('#additional_images');
for (let i = 0; i < imagesInput.files.length; i++) {
  formData.append('images[]', imagesInput.files[i]);
}

fetch('http://localhost/api/v1/offers', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer YOUR_TOKEN',
    'Accept': 'application/json',
  },
  body: formData
})
.then(response => response.json())
.then(data => {
  console.log('تم رفع', data.data.total_images_uploaded, 'صورة');
  console.log('جميع الصور:', data.data.all_images);
});
```
```

### Response Success (201)

```json
{
    "success": true,
    "message": "تم إضافة العرض بنجاح",
    "data": {
        "tenant_offer_id": 45,
        "central_offer_id": 123,
        "title": "فرصة استثمارية في برج تجاري",
        "city": "جدة",
        "total_shares": 1500,
        "price_per_share": "750",
        "cover_image": "http://localhost/storage/offers/1708092345_abc123.jpg",
        "total_images_uploaded": 4,
        "all_images": [
            "http://localhost/storage/offers/1708092345_abc123.jpg",
            "http://localhost/storage/offers/1708092346_def456.jpg",
            "http://localhost/storage/offers/1708092347_ghi789.jpg",
            "http://localhost/storage/offers/1708092348_jkl012.jpg"
        ]
    }
}
```

### Response Error - بدون صورة (422)

```json
{
    "success": false,
    "message": "خطأ في البيانات المدخلة",
    "errors": {
        "cover_image": [
            "صورة الغلاف مطلوبة"
        ]
    }
}
```

---

## 2️⃣ إضافة صورة واحدة لعرض موجود

استخدم هذا الـ endpoint لإضافة أو استبدال صورة الغلاف لعرض موجود.

### Endpoint
```
POST /api/v1/offers/{offer_id}/upload-image
```

### Headers
```
Authorization: Bearer {TOKEN}
Content-Type: multipart/form-data
Accept: application/json
```

### Request Body (Form Data)

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `tenant_domain` | string | ✅ Yes | نطاق المستأجر |
| `cover_image` | file | ✅ Yes | الصورة الجديدة |

### مثال: cURL

```bash
curl --location 'http://localhost/api/v1/offers/123/upload-image' \
--header 'Authorization: Bearer YOUR_TOKEN' \
--header 'Accept: application/json' \
--form 'tenant_domain="sahm_4"' \
--form 'cover_image=@"/path/to/new-image.jpg"'
```

### مثال: JavaScript

```javascript
const formData = new FormData();
formData.append('tenant_domain', 'sahm_4');
formData.append('cover_image', fileInput.files[0]);

fetch('http://localhost/api/v1/offers/123/upload-image', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer YOUR_TOKEN',
    'Accept': 'application/json',
  },
  body: formData
})
.then(response => response.json())
.then(data => console.log(data));
```

### Response Success (200)

```json
{
    "success": true,
    "message": "تم رفع الصورة بنجاح",
    "data": {
        "offer_id": 123,
        "cover_image": "http://localhost/storage/offers/1708092456_xyz789.jpg"
    }
}
```

### ملاحظات
- ⚠️ تستبدل الصورة القديمة تلقائياً
- ✅ يتم حذف الصورة القديمة من التخزين
- ✅ يتم التحديث في كل من قاعدة البيانات الفرعية والمركزية

---

## 3️⃣ إضافة صور متعددة لعرض موجود

استخدم هذا الـ endpoint لإضافة صور متعددة (حتى 15 صورة) لعرض موجود.

### Endpoint
```
POST /api/v1/offers/{offer_id}/upload-images
```

### Headers
```
Authorization: Bearer {TOKEN}
Content-Type: multipart/form-data
Accept: application/json
```

### Request Body (Form Data)

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `tenant_domain` | string | ✅ Yes | نطاق المستأجر |
| `images[]` | file | ✅ Yes | صور متعددة (1-15 صورة) |
| `images[]` | file | ✅ Yes | صورة ثانية |
| `images[]` | file | ❌ No | صورة ثالثة... إلخ |

### مثال: cURL - 3 صور

```bash
curl --location 'http://localhost/api/v1/offers/123/upload-images' \
--header 'Authorization: Bearer YOUR_TOKEN' \
--header 'Accept: application/json' \
--form 'tenant_domain="sahm_4"' \
--form 'images[]=@"/path/to/image1.jpg"' \
--form 'images[]=@"/path/to/image2.jpg"' \
--form 'images[]=@"/path/to/image3.jpg"'
```

### مثال: JavaScript - رفع صور متعددة

```javascript
const formData = new FormData();
formData.append('tenant_domain', 'sahm_4');

// إضافة صور متعددة
const fileInput = document.querySelector('input[type="file"][multiple]');
for (let i = 0; i < fileInput.files.length; i++) {
  formData.append('images[]', fileInput.files[i]);
}

fetch('http://localhost/api/v1/offers/123/upload-images', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer YOUR_TOKEN',
    'Accept': 'application/json',
  },
  body: formData
})
.then(response => response.json())
.then(data => console.log(data));
```

### مثال: Postman

1. اختر `POST`
2. URL: `http://localhost/api/v1/offers/123/upload-images`
3. Headers:
   - `Authorization: Bearer YOUR_TOKEN`
   - `Accept: application/json`
4. Body → form-data:
   - Key: `tenant_domain`, Value: `sahm_4`
   - Key: `images[]`, Type: File, Value: اختر ملف
   - Key: `images[]`, Type: File, Value: اختر ملف آخر
   - Key: `images[]`, Type: File, Value: اختر ملف ثالث

### Response Success (200)

```json
{
    "success": true,
    "message": "تم رفع الصور بنجاح",
    "data": {
        "offer_id": 123,
        "uploaded_count": 3,
        "total_images": 5,
        "images": [
            "http://localhost/storage/offers/old_image1.jpg",
            "http://localhost/storage/offers/old_image2.jpg",
            "http://localhost/storage/offers/new_image1.jpg",
            "http://localhost/storage/offers/new_image2.jpg",
            "http://localhost/storage/offers/new_image3.jpg"
        ],
        "cover_image": "http://localhost/storage/offers/old_image1.jpg"
    }
}
```

### ملاحظات
- ✅ تُضاف الصور الجديدة إلى الصور الموجودة (لا تستبدلها)
- ✅ الحد الأقصى: 15 صورة إجمالية للعرض
- ✅ إذا لم يكن العرض يحتوي على `cover_image`، تُستخدم أول صورة كصورة غلاف
- ✅ يتم التحديث في كل من قاعدة البيانات الفرعية والمركزية

---

## ❌ الأخطاء الشائعة

### 1. عدم إرسال الصورة في الطلب الأول

**الخطأ:**
```json
{
    "tenant_domain": "sahm_4",
    "title": "عرض جديد",
    ...
}
```

**الحل:**
استخدم `Content-Type: multipart/form-data` وأرفق الصورة في `cover_image`

---

### 2. نسيان Content-Type

**الخطأ:**
```
Content-Type: application/json
```

**الحل:**
```
Content-Type: multipart/form-data
```

---

### 3. استخدام JSON بدلاً من Form Data

**خطأ:**
```javascript
fetch('/api/v1/offers', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json', // ❌ خطأ
  },
  body: JSON.stringify({...}) // ❌ لن يعمل مع الصور
})
```

**صحيح:**
```javascript
const formData = new FormData();
formData.append('cover_image', file); // ✅ صحيح

fetch('/api/v1/offers', {
  method: 'POST',
  body: formData // ✅ بدون Content-Type header
})
```

---

### 4. تجاوز حد الحجم

**الخطأ:**
```json
{
    "success": false,
    "errors": {
        "cover_image": ["حجم الصورة يجب أن لا يتجاوز 5 ميجابايت"]
    }
}
```

**الحل:**
ضغط الصورة أو تصغيرها لتكون أقل من 5 MB

---

## 📊 ملخص الـ Endpoints

| Endpoint | Method | Purpose | Image Required |
|----------|--------|---------|----------------|
| `/api/v1/offers` | POST | إنشاء عرض جديد | ✅ **إجبارية** |
| `/api/v1/offers/{id}/upload-image` | POST | استبدال صورة الغلاف | ✅ إجبارية |
| `/api/v1/offers/{id}/upload-images` | POST | إضافة صور متعددة | ✅ إجبارية (1-15) |

---

## 🔧 اختبار الـ API

### باستخدام Postman

1. **إنشاء Collection جديدة**: `Offer Image Upload`
2. **إضافة Environment Variables**:
   - `BASE_URL`: `http://localhost/api/v1`
   - `TOKEN`: `{your_bearer_token}`
3. **إضافة Request جديد**:
   - Method: `POST`
   - URL: `{{BASE_URL}}/offers`
   - Headers:
     - `Authorization`: `Bearer {{TOKEN}}`
     - `Accept`: `application/json`
   - Body → form-data:
     - إضافة جميع الحقول
     - `cover_image`: اختيار ملف

### باستخدام Laravel Tinker

```php
// اختبار upload image
php artisan tinker

$token = \Laravel\Sanctum\PersonalAccessToken::findToken('4|...');
$user = $token->tokenable;
```

---

## 🎯 اختبار Postman: إضافة عرض مع صور متعددة

### الطريقة 1: صورة واحدة فقط

1. اختر `POST`
2. URL: `http://localhost/api/v1/offers`
3. Headers:
   - `Authorization: Bearer YOUR_TOKEN`
   - `Accept: application/json`
4. Body → **form-data**:
   - `tenant_domain` (Text): `sahm_4`
   - `title` (Text): `برج سكني`
   - `title_ar` (Text): `برج سكني`
   - `city` (Text): `الرياض`
   - `total_shares` (Text): `100`
   - `price_per_share` (Text): `5000`
   - `cover_image` (File): اختر ملف الصورة

### ⭐ الطريقة 2: صور متعددة (حتى 15 صورة)

1. اختر `POST`
2. URL: `http://localhost/api/v1/offers`
3. Headers:
   - `Authorization: Bearer YOUR_TOKEN`
   - `Accept: application/json`
4. Body → **form-data**:
   - `tenant_domain` (Text): `sahm_4`
   - `title` (Text): `برج سكني`
   - `title_ar` (Text): `برج سكني`
   - `city` (Text): `الرياض`
   - `total_shares` (Text): `100`
   - `price_per_share` (Text): `5000`
   - `cover_image` (File): **اختر صورة الغلاف الرئيسية** ✅ إجباري
   - `images[]` (File): اختر صورة إضافية 1 (اختياري)
   - `images[]` (File): اختر صورة إضافية 2 (اختياري)
   - `images[]` (File): اختر صورة إضافية 3 (اختياري)
   - ... (حتى 14 صورة إضافية)

### 💡 نصائح مهمة في Postman:
- ✅ استخدم النوع **`form-data`** وليس `raw` أو `JSON`
- ✅ لإضافة عدة صور بنفس المفتاح `images[]`، اضغط على سطر جديد بنفس المفتاح
- ✅ اختر **File** من القائمة المنسدلة بجانب كل حقل صورة
- ✅ `cover_image` إجباري، `images[]` اختياري
- ✅ المجموع الأقصى: 15 صورة (1 غلاف + 14 إضافية)

### الاستجابة المتوقعة:

```json
{
    "success": true,
    "message": "تم إضافة العرض بنجاح",
    "data": {
        "tenant_offer_id": 25,
        "central_offer_id": 25,
        "title": "برج سكني",
        "city": "الرياض",
        "total_shares": 100,
        "price_per_share": "5000",
        "cover_image": "http://localhost/storage/offers/1708092345_abc123.jpg",
        "total_images_uploaded": 4,
        "all_images": [
            "http://localhost/storage/offers/1708092345_abc123.jpg",
            "http://localhost/storage/offers/1708092346_def456.jpg",
            "http://localhost/storage/offers/1708092347_ghi789.jpg",
            "http://localhost/storage/offers/1708092348_jkl012.jpg"
        ]
    }
}
```

---

## 📝 ملاحظات مهمة

1. **⭐ جديد: يمكن إضافة صور متعددة عند الإنشاء** - cover_image (إجباري) + images[] (حتى 14 صورة إضافية)
2. **الصورة إجبارية عند إنشاء عرض جديد** - لا يمكن إنشاء عرض بدون صورة cover_image
3. **استخدم `multipart/form-data`** - وليس `application/json`
4. **لا تضف `Content-Type` يدوياً** - المتصفح يضيفه تلقائياً مع FormData
5. **الحد الأقصى للصور**: 15 صورة لكل عرض (1 غلاف + 14 إضافية)
6. **حجم الصورة**: 5 MB كحد أقصى لكل صورة
7. **صيغ مدعومة**: JPG, JPEG, PNG, GIF, WEBP
8. **جميع الصور تُحفظ في حقل `media`** وتظهر في صفحة التعديل في لوحة التحكم

---

## 🔗 روابط ذات صلة

- [API_TESTING_OFFERS.md](./API_TESTING_OFFERS.md) - دليل اختبار العروض الشامل
- [API_QUICK_REFERENCE.md](./API_QUICK_REFERENCE.md) - مرجع سريع للـ API

---

## 📞 الدعم

في حال واجهت أي مشاكل:
1. تحقق من صيغة الصورة وحجمها
2. تأكد من استخدام `multipart/form-data`
3. راجع log files: `storage/logs/laravel.log`
4. تحقق من `Authorization` token

---

**آخر تحديث:** فبراير 2026
