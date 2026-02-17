# 🧪 اختبار إضافة العروض في Postman

## 🌐 Base URL

استخدم أحد الخيارين التاليين حسب بيئة العمل:

- **بيئة التطوير المحلية:** `http://sahm.test` أو `http://localhost:8000`
- **Base API Path:** `/api/v1/`

**مثال كامل:** `http://sahm.test/api/v1/offers`

---

## ⚠️ ملاحظة مهمة

**هذا الدليل لمديري Tenant فقط!**

- 👨‍💼 **مديرو Tenant:** يستخدمون `/auth/tenant-register` و `/auth/tenant-login`
- 🛒 **المشترون:** يستخدمون `/auth/register` و `/auth/login` (بدون tenant_domain)

**لمزيد من التفاصيل، راجع:** [API_AUTH_GUIDE.md](API_AUTH_GUIDE.md)

---

## الخطوات بالتفصيل

---

## 📝 الخطوة 1: التسجيل أو تسجيل الدخول

### **A. التسجيل (إذا لم يكن لديك حساب)**

```
Method: POST
URL: http://sahm.test/api/v1/auth/tenant-register
أو
URL: http://localhost:8000/api/v1/auth/tenant-register
```

**📍 Endpoint:** `POST /api/v1/auth/tenant-register`

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
URL: http://sahm.test/api/v1/auth/tenant-login
أو
URL: http://localhost:8000/api/v1/auth/tenant-login
```

**📍 Endpoint:** `POST /api/v1/auth/tenant-login`

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

⚠️ **مهم:** إضافة صورة غلاف **إلزامية** لكل عرض!

### **Setup في Postman:**

```
Method: POST
URL: http://sahm.test/api/v1/offers
أو
URL: http://localhost:8000/api/v1/offers
```

**📍 Endpoint:** `POST /api/v1/offers`

### **Headers:**
```
Accept: application/json
Authorization: Bearer YOUR_TOKEN_HERE
```

⚠️ **مهم:** لا تضيف `Content-Type: application/json` (سيُضاف تلقائياً)

⚠️ **مهم:** لا تضيف `Content-Type: application/json` (سيُضاف تلقائياً)

### **Body (يجب اختيار form-data وليس JSON):**

**❌ لا يمكن استخدام raw JSON** لأن الصورة إلزامية!

في Postman، اختر **Body** → **form-data**، ثم أضف الحقول التالية:

| Key | Type | Value |
|-----|------|-------|
| tenant_domain | Text | sahm_4 |
| title | Text | Luxury Villa Investment |
| title_ar | Text | فرصة استثمارية في فيلا فاخرة |
| description | Text | Premium villa in Riyadh |
| description_ar | Text | فيلا فاخرة في الرياض بموقع مميز |
| country | Text | Saudi Arabia |
| city | Text | الرياض |
| address | Text | حي النرجس، شارع التحلية |
| total_shares | Text | 1000 |
| price_per_share | Text | 500 |
| currency | Text | SAR |
| status | Text | active |
| starts_at | Text | 2026-02-15 |
| ends_at | Text | 2026-12-31 |
| **cover_image** | **File** 📷 | **[اختر ملف صورة]** ← **إلزامي!** |

**💡 لإضافة الصورة:**
1. في صف `cover_image`
2. غيّر النوع من "Text" إلى **"File"**
3. اضغط "Select Files" واختر صورة من جهازك

**💡 ملاحظة:** الصورة **إلزامية** - لا يمكن إضافة عرض بدون صورة

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

إذا كنت تريد **إضافة صورة غلاف** مع العرض مباشرة:

### **Setup في Postman:**

```
Method: POST
URL: http://sahm.test/api/v1/offers
أو
URL: http://localhost:8000/api/v1/offers
```

**📍 Endpoint:** `POST /api/v1/offers`

### **Headers:**
```
Accept: application/json
Authorization: Bearer YOUR_TOKEN_HERE
```

**⚠️ مهم:** لا تضيف `Content-Type: application/json` عند استخدام form-data

### **Body (اختر form-data):**

في Postman، اختر **Body** → **form-data**، ثم أضف الحقول التالية:

| Key | Type | Value |
|-----|------|-------|
| tenant_domain | Text | sahm_4 |
| title | Text | Luxury Villa Investment |
| title_ar | Text | فرصة استثمارية في فيلا فاخرة |
| description | Text | Premium villa in Riyadh |
| description_ar | Text | فيلا فاخرة في الرياض بموقع مميز |
| country | Text | Saudi Arabia |
| city | Text | الرياض |
| address | Text | حي النرجس، شارع التحلية |
| total_shares | Text | 1000 |
| price_per_share | Text | 500 |
| currency | Text | SAR |
| status | Text | active |
| starts_at | Text | 2026-02-15 |
| ends_at | Text | 2026-12-31 |
| **cover_image** | **File** | **[اختر ملف صورة]** |

### ✅ الملفات المقبولة:
- **الصيغ:** JPG, JPEG, PNG, GIF, WEBP
- **الحجم الأقصى:** 5 ميجابايت

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

## �📝 الخطوة 3: التحقق من العرض المضاف

### **عرض جميع العروض:**

```
Method: GET
URL: http://sahm.test/api/v1/offers
أو: http://localhost:8000/api/v1/offers
```

**📍 Endpoint:** `GET /api/v1/offers`

**Headers:** `Accept: application/json`

---

### **عرض التفاصيل:**

```
Method: GET
URL: http://sahm.test/api/v1/offers/{id}
أو: http://localhost:8000/api/v1/offers/{id}
```

**📍 Endpoint:** `GET /api/v1/offers/{id}`

**Headers:** `Accept: application/json`

---

## 🔄 خطوات إضافية (اختياري)

### **رفع صورة لعرض موجود:**

إذا نسيت إضافة صورة عند إنشاء العرض، يمكنك رفعها لاحقاً:

```
Method: POST
URL: http://sahm.test/api/v1/offers/{id}/upload-image
أو: http://localhost:8000/api/v1/offers/{id}/upload-image
```

**📍 Endpoint:** `POST /api/v1/offers/{id}/upload-image`

**Headers:**
```
Accept: application/json
Authorization: Bearer YOUR_TOKEN
```

**Body (form-data):**
| Key | Type | Value |
|-----|------|-------|
| tenant_domain | Text | sahm_4 |
| cover_image | File | [اختر ملف صورة] |

**✅ الاستجابة:**
```json
{
    "success": true,
    "message": "تم رفع الصورة بنجاح",
    "data": {
        "offer_id": 5,
        "cover_image": "http://sahm.test/storage/offers/123456_abc.jpg"
    }
}
```

---

### **تحديث العرض:**

```
Method: PUT
URL: http://sahm.test/api/v1/offers/{id}
أو: http://localhost:8000/api/v1/offers/{id}
```

**📍 Endpoint:** `PUT /api/v1/offers/{id}`

**Headers:**
```
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

**💡 لتحديث الصورة:** استخدم `form-data` بدلاً من `JSON`:
| Key | Type | Value |
|-----|------|-------|
| tenant_domain | Text | sahm_4 |
| title_ar | Text | فرصة استثمارية محدثة |
| cover_image | File | [اختر صورة جديدة] |

---

### **حذف العرض:**

```
Method: DELETE
URL: http://sahm.test/api/v1/offers/{id}
أو: http://localhost:8000/api/v1/offers/{id}
```

**📍 Endpoint:** `DELETE /api/v1/offers/{id}`

**Headers:**
```
Content-Type: application/json
Accept: application/json
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

### خطأ: "صورة الغلاف مطلوبة" أو "The cover image field is required"
**Solution:** 
- تأكد من إضافة حقل `cover_image` في form-data
- تأكد من تغيير نوع الحقل إلى **File** وليس Text
- اختر صورة من جهازك

### خطأ: "العرض يجب أن يحتوي على صورة غلاف واحدة على الأقل"
**Solution:** 
- هذا الخطأ يظهر عند محاولة التحديث بدون صورة إذا لم يكن هناك صورة موجودة
- أضف صورة مع التحديث أو استخدم endpoint رفع الصورة أولاً

---

## 📋 الحقول المطلوبة:

- **مطلوبة (Required):** ⚠️
  - tenant_domain
  - title
  - title_ar
  - city
  - total_shares
  - price_per_share
  - **cover_image** 📷 **(إلزامي - يجب استخدام form-data)**

- **اختيارية (Optional):**
  - description
  - description_ar
  - country
  - address
  - currency (افتراضي: SAR)
  - status (افتراضي: active)
  - starts_at
  - ends_at

### 📸 متطلبات الصورة (إلزامية):
- **الصيغ المقبولة:** JPG, JPEG, PNG, GIF, WEBP
- **الحجم الأقصى:** 5 ميجابايت (5120 KB)
- **النوع:** يجب أن يكون ملف صورة فعلي
- **الحقل:** `cover_image`
- ⚠️ **ملاحظة:** لا يمكن إضافة عرض بدون صورة غلاف

---

## 🔒 كيف يعمل النظام:

1. **التحقق من المستخدم** → عبر Token
2. **التحقق من Tenant** → sahm_4 موجود في قاعدة البيانات المركزية
3. **إضافة في قاعدة البيانات الفرعية** → tenant database (sahm_4)
4. **نسخ إلى قاعدة البيانات المركزية** → للعرض في الموقع الرئيسي
5. **الربط بينهما** → central_offer_id

---

## 📚 جميع Endpoints إدارة العروض

**ملاحظة:** استبدل `{id}` بالرقم الفعلي للعرض (مثال: `/api/v1/offers/5`)

### للمصادقة (Authentication):
| Method | Endpoint | الوصف |
|--------|----------|-------|
| POST | `/api/v1/auth/tenant-register` | تسجيل مدير Tenant جديد |
| POST | `/api/v1/auth/tenant-login` | تسجيل دخول مدير Tenant |
| POST | `/api/v1/auth/logout` | تسجيل الخروج |
| GET | `/api/v1/auth/profile` | عرض الملف الشخصي |
| PUT | `/api/v1/auth/profile` | تحديث الملف الشخصي |

### لإدارة العروض (Offers Management):
| Method | Endpoint | الوصف | يحتاج Token |
|--------|----------|-------|-------------|
| GET | `/api/v1/offers` | عرض جميع العروض | ❌ |
| GET | `/api/v1/offers/{id}` | عرض تفاصيل عرض محدد | ❌ |
| POST | `/api/v1/offers` | إضافة عرض جديد | ✅ |
| PUT | `/api/v1/offers/{id}` | تحديث عرض | ✅ |
| DELETE | `/api/v1/offers/{id}` | حذف عرض | ✅ |
| POST | `/api/v1/offers/{id}/upload-image` | رفع صورة غلاف لعرض موجود 📸 | ✅ |

### للشراء (New! 🎉):
| Method | Endpoint | الوصف | يحتاج Token |
|--------|----------|-------|-------------|
| POST | `/api/v1/purchase` | شراء أسهم | ✅ |
| POST | `/api/v1/purchase/confirm-payment` | تأكيد الدفع | ✅ |
| POST | `/api/v1/purchase/{id}/cancel` | إلغاء عملية شراء | ✅ |

### للوحة التحكم (Dashboard):
| Method | Endpoint | الوصف | يحتاج Token |
|--------|----------|-------|-------------|
| GET | `/api/v1/buyer/dashboard` | إحصائيات المشتريات | ✅ |
| GET | `/api/v1/buyer/operations` | جميع العمليات | ✅ |
| GET | `/api/v1/buyer/operations/{id}` | تفاصيل عملية محددة | ✅ |
| GET | `/api/v1/buyer/my-shares` | الأسهم المملوكة | ✅ |

---

## 📸 دليل كامل: كيفية إضافة صور في Postman

### الطريقة 1️⃣: إضافة صورة مع عرض جديد

**الخطوات في Postman:**

1. افتح Tab جديد
2. اختر Method: **POST**
3. أدخل URL: `http://sahm.test/api/v1/offers`
4. في **Headers**:
   - أضف `Accept: application/json`
   - أضف `Authorization: Bearer YOUR_TOKEN`
   - ❌ **لا تضيف** `Content-Type` (سيُضاف تلقائياً)
5. في **Body**:
   - اختر **form-data** (وليس raw)
   - أضف جميع الحقول كـ **Text**
   - في حقل `cover_image`:
     - غيّر النوع من Text إلى **File**
     - اضغط "Select Files" واختر الصورة
6. اضغط **Send**

### الطريقة 2️⃣: رفع صورة لعرض موجود

**الخطوات في Postman:**

1. افتح Tab جديد
2. اختر Method: **POST**
3. أدخل URL: `http://sahm.test/api/v1/offers/5/upload-image`
   - استبدل `5` برقم العرض الفعلي
4. في **Headers**:
   - أضف `Accept: application/json`
   - أضف `Authorization: Bearer YOUR_TOKEN`
5. في **Body**:
   - اختر **form-data**
   - أضف `tenant_domain` (Text): sahm_4
   - أضف `cover_image` (File): اختر الصورة
6. اضغط **Send**

### الطريقة 3️⃣: تحديث صورة عرض موجود

استخدم نفس طريقة التحديث العادية (`PUT /api/v1/offers/{id}`) لكن:
- استخدم **form-data** بدلاً من raw JSON
- أضف جميع الحقول المراد تحديثها
- أضف `cover_image` كـ File

---

## ❓ أسئلة شائعة عن الصور

### س: لماذا أحصل على خطأ عند رفع الصورة؟
**ج:** تأكد من:
- استخدام **form-data** وليس raw JSON
- حجم الصورة أقل من 5 ميجابايت
- صيغة الصورة (JPG, PNG, GIF, WEBP)
- حقل الصورة اسمه `cover_image` بالضبط
- تغيير نوع الحقل إلى File في Postman

### س: هل يمكن إضافة عرض بدون صورة؟
**ج:** ❌ **لا**، الصورة **إلزامية**. يجب إضافة صورة غلاف واحدة على الأقل مع كل عرض.

### س: هل يمكن حذف صورة العرض؟
**ج:** ❌ **لا**، لا يمكن حذف جميع الصور. يجب أن يحتوي كل عرض على صورة واحدة على الأقل. يمكنك فقط **استبدال** الصورة القديمة بصورة جديدة.

### س: كيف أرى الصورة المرفوعة؟
**ج:** الرد سيحتوي على رابط الصورة:
```json
{
    "cover_image": "http://sahm.test/storage/offers/123456_abc.jpg"
}
```

### س: أين تُحفظ الصور؟
**ج:** في مجلد `storage/app/public/offers/`

### س: ماذا يحدث للصورة القديمة عند رفع صورة جديدة؟
**ج:** يتم حذف الصورة القديمة تلقائياً وحفظ الجديدة

### س: هل يمكن إضافة أكثر من صورة؟
**ج:** حالياً، يدعم النظام صورة غلاف واحدة فقط (`cover_image`)

---

**جرب الآن!** 🚀
