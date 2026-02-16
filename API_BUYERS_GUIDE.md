# 🎯 دليل API المحدث - المشترون لا ينتمون لأي Tenant

## 📌 معلومة مهمة
**المشتري لا يتبع لأي tenant** - جميع المشترين موجودون في قاعدة البيانات المركزية ويمكنهم الشراء من أي عرض.

---

## 🚀 الاختبار في Postman

### **1️⃣ تسجيل مشتري جديد (بدون tenant)**

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
    "name": "أحمد محمد",
    "email": "buyer@test.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**✅ النتيجة:**
```json
{
    "success": true,
    "message": "تم التسجيل بنجاح",
    "data": {
        "user": {
            "id": 1,
            "name": "أحمد محمد",
            "email": "buyer@test.com",
            "avatar": null
        },
        "token": "1|abcdefghijklmno..."
    }
}
```

**Save the Token!** ←

---

### **2️⃣ تسجيل الدخول (بدون tenant)**

```
Method: POST
URL: http://localhost:8000/api/v1/auth/login
```

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Body (JSON):**
```json
{
    "email": "buyer@test.com",
    "password": "password123"
}
```

**✅ النتيجة:**
```json
{
    "success": true,
    "message": "تم تسجيل الدخول بنجاح",
    "data": {
        "user": {
            "id": 1,
            "name": "أحمد محمد",
            "email": "buyer@test.com",
            "avatar": null
        },
        "token": "2|xyzabcdefgh..."
    }
}
```

---

### **3️⃣ عرض جميع العروض (بدون token)**

```
Method: GET
URL: http://localhost:8000/api/v1/offers
```

**Headers:**
```
Accept: application/json
```

**Result:** List of all offers from central database

---

### **4️⃣ عرض بيانات المشتري الشخصية**

```
Method: GET
URL: http://localhost:8000/api/v1/auth/profile
```

**Headers:**
```
Accept: application/json
Authorization: Bearer YOUR_TOKEN
```

---

### **5️⃣ شراء أسهم من أي عرض**

```
Method: POST
URL: http://localhost:8000/api/v1/purchase
```

**Headers:**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer YOUR_TOKEN
```

**Body (JSON):**
```json
{
    "offer_id": 1,
    "shares_count": 10,
    "payment_method": "credit_card"
}
```

**Note:** لن تحتاج لـ `tenant_domain` للمشتري بعد الآن!

---

### **6️⃣ لوحة تحكم المشتري**

```
Method: GET
URL: http://localhost:8000/api/v1/buyer/dashboard
```

**Headers:**
```
Accept: application/json
Authorization: Bearer YOUR_TOKEN
```

---

### **7️⃣ عمليات المشتري**

```
Method: GET
URL: http://localhost:8000/api/v1/buyer/operations
```

**Headers:**
```
Accept: application/json
Authorization: Bearer YOUR_TOKEN
```

---

### **8️⃣ أسهم المشتري**

```
Method: GET
URL: http://localhost:8000/api/v1/buyer/my-shares
```

**Headers:**
```
Accept: application/json
Authorization: Bearer YOUR_TOKEN
```

---

## 🔐 ملخص التغييرات

### ✅ ما تم تغييره:

1. **التسجيل**: لا يحتاج `tenant_domain`
2. **تسجيل الدخول**: لا يحتاج `tenant_domain`
3. **البيانات**: تُحفظ في قاعدة البيانات المركزية (central)
4. **الشراء**: المشتري يشتري من أي عرض بدون قيود

### ❌ ما تم إزالته:

- ❌ حقل `tenant_domain` من التسجيل
- ❌ حقل `tenant_domain` من تسجيل الدخول
- ❌ التحقق من Tenant للمشترين
- ❌ قاعدة البيانات الفرعية للمشترين

---

## 📊 البنية الجديدة:

```
قاعدة البيانات المركزية (Central)
├── users (المشترون - buyers)
├── share_offers (العروض)
├── share_operations (عمليات الشراء)
└── tenants (الـ tenants)

قاعدة البيانات الفرعية (Tenant)
└── (تستخدم فقط لإدارة Tenant لعروضه)
```

---

## 🎯 الخطوات المختصرة للاختبار:

1. ✅ تسجيل مشتري: `POST /auth/register` (بدون tenant_domain)
2. ✅ تسجيل الدخول: `POST /auth/login` (بدون tenant_domain)
3. ✅ عرض العروض: `GET /offers`
4. ✅ شراء: `POST /purchase` (مع token)

---

## ⚠️ ملاحظات مهمة:

- المشتري **عام** ولا ينتمي لأي tenant
- المشتري يمكنه **الشراء من أي عرض**
- العروض **مشتركة** للجميع
- Token صالح لجميع العمليات

---

**الآن جرب التسجيل بدون tenant_domain!** 🎉
