# اختبار ميزة شراء مديري Tenant

## خطوات الاختبار السريع

### 1. تسجيل مدير Tenant جديد

```bash
POST http://localhost:8000/api/v1/auth/tenant-register
Content-Type: application/json

{
    "tenant_domain": "sahm_4",
    "name": "مدير اختبار الشراء",
    "email": "purchase-test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**الناتج المتوقع:**
```json
{
    "success": true,
    "message": "تم إنشاء الحساب بنجاح",
    "data": {
        "user": {
            "id": 1,
            "name": "مدير اختبار الشراء",
            "email": "purchase-test@example.com"
        },
        "token": "1|abc123...",
        "abilities": ["admin", "tenant:X"]
    }
}
```

✅ احفظ التوكن للخطوات القادمة

---

### 2. التحقق من العروض المتاحة

```bash
GET http://localhost:8000/api/v1/offers?status=active
Authorization: Bearer {YOUR_TENANT_TOKEN}
```

✅ اختر عرض للشراء منه (احفظ offer_id)

---

### 3. شراء أسهم من العرض

```bash
POST http://localhost:8000/api/v1/purchase
Authorization: Bearer {YOUR_TENANT_TOKEN}
Content-Type: application/json

{
    "offer_id": 10,
    "shares_count": 50,
    "payment_method": "credit_card"
}
```

**الناتج المتوقع:**
```json
{
    "success": true,
    "message": "تم إنشاء طلب الشراء بنجاح. يرجى إكمال الدفع.",
    "data": {
        "operation_id": 25,
        "external_reference": "OP-ABC123",
        "shares_count": 50,
        "price_per_share": 1000,
        "amount_total": 50000,
        "currency": "SAR",
        "status": "pending"
    }
}
```

✅ احفظ operation_id للخطوة القادمة

---

### 4. التحقق من قاعدة البيانات المركزية

افتح قاعدة البيانات المركزية وتحقق من:

#### A. جدول users:
```sql
SELECT * FROM users WHERE email = 'purchase-test@example.com';
```

**يجب أن تجد:**
- ✅ مستخدم جديد بنفس البريد والاسم
- ✅ كلمة مرور مشفرة (عشوائية)
- ✅ email_verified_at محدد

#### B. جدول share_operations:
```sql
SELECT * FROM share_operations WHERE buyer_id = {USER_ID_FROM_ABOVE};
```

**يجب أن تجد:**
- ✅ عملية شراء جديدة
- ✅ buyer_id يساوي ID المستخدم المركزي
- ✅ status = 'pending'

---

### 5. تأكيد الدفع

```bash
POST http://localhost:8000/api/v1/purchase/confirm
Authorization: Bearer {YOUR_TENANT_TOKEN}
Content-Type: application/json

{
    "operation_id": 25,
    "payment_id": "PAY-123456",
    "payment_status": "completed"
}
```

**الناتج المتوقع:**
```json
{
    "success": true,
    "message": "تم إتمام عملية الشراء بنجاح",
    "data": {
        "operation_id": 25,
        "status": "completed",
        "external_reference": "OP-ABC123"
    }
}
```

---

### 6. عرض لوحة التحكم

```bash
GET http://localhost:8000/api/v1/buyer/dashboard
Authorization: Bearer {YOUR_TENANT_TOKEN}
```

**الناتج المتوقع:**
```json
{
    "success": true,
    "data": {
        "stats": {
            "total_operations": 1,
            "completed_operations": 1,
            "pending_operations": 0,
            "total_shares_owned": 50,
            "total_spent": 50000
        },
        "recent_operations": [
            {
                "id": 25,
                "type": "purchase",
                "status": "completed",
                "shares_count": 50,
                "amount_total": 50000,
                "created_at": "2026-02-14 10:30:00"
            }
        ]
    }
}
```

---

### 7. عرض الأسهم المملوكة

```bash
GET http://localhost:8000/api/v1/buyer/my-shares
Authorization: Bearer {YOUR_TENANT_TOKEN}
```

**الناتج المتوقع:**
```json
{
    "success": true,
    "data": [
        {
            "offer_id": 10,
            "offer_title": "عقار سكني في الرياض",
            "total_shares": 50,
            "total_invested": 50000,
            "average_price": 1000,
            "operations_count": 1
        }
    ],
    "summary": {
        "total_offers": 1,
        "total_shares": 50,
        "total_invested": 50000
    }
}
```

---

### 8. عرض جميع العمليات

```bash
GET http://localhost:8000/api/v1/buyer/operations
Authorization: Bearer {YOUR_TENANT_TOKEN}
```

---

## ✅ نقاط التحقق

| الخطوة | النقطة | الحالة |
|--------|--------|--------|
| 1 | تسجيل مدير Tenant بنجاح | ⬜ |
| 2 | استلام توكن بقدرات admin | ⬜ |
| 3 | شراء أسهم بنجاح | ⬜ |
| 4 | وجود مستخدم في central.users | ⬜ |
| 5 | وجود عملية في share_operations | ⬜ |
| 6 | تأكيد الدفع بنجاح | ⬜ |
| 7 | عرض Dashboard بنجاح | ⬜ |
| 8 | عرض الأسهم المملوكة بنجاح | ⬜ |

---

## 🧪 اختبار إضافي: شراء آخر

جرب شراء آخر بنفس الحساب:

```bash
POST http://localhost:8000/api/v1/purchase
Authorization: Bearer {YOUR_TENANT_TOKEN}
Content-Type: application/json

{
    "offer_id": 15,
    "shares_count": 100,
    "payment_method": "bank_transfer"
}
```

ثم تأكد من:
- ✅ تحديث الإحصائيات في Dashboard
- ✅ ظهور عمليتين في my-shares
- ✅ **عدم إنشاء مستخدم جديد في central.users** (نفس المستخدم)

---

## 🔍 استكشاف الأخطاء

### خطأ: "Unauthenticated"
- تأكد من وجود التوكن في header
- تأكد من صيغة: `Authorization: Bearer {token}`

### خطأ: "العرض غير موجود"
- تأكد من وجود عرض نشط: `GET /api/v1/offers?status=active`
- استخدم offer_id صحيح

### خطأ: "عدد الأسهم المتاحة غير كافٍ"
- تحقق من available_shares في العرض
- قلل shares_count المطلوب

### لا يظهر شيء في Dashboard
- تأكد من تأكيد الدفع أولاً (status = completed)
- تحقق من buyer_id في share_operations

---

## 📊 توقعات النتائج

### في central.users:
```
id | name                  | email                        | created_at
---|-----------------------|------------------------------|------------
X  | مدير اختبار الشراء   | purchase-test@example.com    | 2026-02-14
```

### في share_operations:
```
id | buyer_id | offer_id | shares_count | amount_total | status
------|----------|----------|--------------|--------------|----------
25    | X        | 10       | 50           | 50000        | completed
```

### في personal_access_tokens:
```
id | tokenable_id | abilities
------|--------------|------------------------
Y     | X            | ["admin", "tenant:Z"]
```

---

**إذا نجحت جميع الخطوات، فالنظام يعمل بشكل صحيح! 🎉**

للأسئلة، راجع: [API_TENANT_PURCHASE_GUIDE.md](API_TENANT_PURCHASE_GUIDE.md)
