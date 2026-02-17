# 🎯 دليل API للمستثمرين (Investors) - محدّث

## 📋 معلومات مهمة
**المستثمرون لا ينتمون لأي تينانت (tenant)** - جميع المستثمرين موجودون في القاعدة المركزية ويمكنهم شراء وبيع الأسهم من أي عرض.

**🆕 السوق الثانوي:** يمكن للمستثمرين الآن بيع أسهمهم المملوكة لمستثمرين آخرين عبر السوق الثانوي.

---

## 🚀 اختبار API في Postman

### **1️⃣ تسجيل مستثمر جديد (Register Investor)**

يُنشئ حساب جديد في جدول `users` وجدول `buyers` في القاعدة المركزية.

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
    "password_confirmation": "password123",
    "phone": "+966551234567",
    "national_id": "1234567890"
}
```

**الحقول المطلوبة (Required):**
- `name` - الاسم الكامل (string, max:255)
- `email` - البريد الإلكتروني (email, unique)
- `password` - كلمة المرور (string, min:8)
- `password_confirmation` - تأكيد كلمة المرور (must match password)

**الحقول الاختيارية (Optional):**
- `phone` - رقم الجوال (string, max:30) - يفضل كتابته بصيغة دولية
- `national_id` - رقم الهوية الوطنية (string, max:50)

**✅ الاستجابة عند النجاح (201):**
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
        "buyer": {
            "id": 1,
            "full_name": "أحمد محمد",
            "phone": "+966551234567",
            "national_id": "1234567890",
            "kyc_status": "unverified"
        },
        "token": "1|abcdefghijklmno..."
    }
}
```

**⚠️ احفظ الـ Token!**

**❌ رسائل الخطأ الشائعة (422):**
```json
{
    "success": false,
    "message": "خطأ في البيانات المدخلة",
    "errors": {
        "email": ["البريد الإلكتروني مسجل مسبقاً"],
        "password": ["كلمة المرور وتأكيدها غير متطابقين"]
    }
}
```

---

### **2️⃣ تسجيل الدخول (Login)**

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

**✅ الاستجابة عند النجاح (200):**
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

### **3️⃣ عرض جميع العروض (View All Offers)**

لا يتطلب مصادقة - يمكن لأي شخص عرض العروض.

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

### **4️⃣ عرض البروفايل الكامل (View Full Profile)**

يعرض معلومات المشتري كاملة بما في ذلك:
- بيانات المشتري الأساسية
- جميع عمليات الشراء والبيع والتحويل
- الأسهم المملوكة حالياً
- إحصائيات شاملة

```
Method: GET
URL: http://localhost:8000/api/v1/auth/profile
```

**Headers:**
```
Accept: application/json
Authorization: Bearer YOUR_TOKEN
```

**✅ الاستجابة عند النجاح (200):**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "أحمد محمد",
            "email": "buyer@test.com",
            "avatar": null,
            "created_at": "2026-02-16"
        },
        "buyer": {
            "id": 1,
            "full_name": "أحمد محمد",
            "email": "buyer@test.com",
            "phone": "+966551234567",
            "national_id": "1234567890",
            "date_of_birth": "1990-01-01",
            "country": null,
            "city": null,
            "address": null,
            "kyc_status": "unverified",
            "metadata": null,
            "created_at": "2026-02-16 12:14:05"
        },
        "operations": [
            {
                "id": 1,
                "offer_id": 5,
                "offer_title": "عرض أسهم شركة التقنية",
                "offer_cover_image": "http://localhost:8000/storage/offers/image.jpg",
                "type": "purchase",
                "type_ar": "شراء",
                "shares_count": 10,
                "price_per_share": 100.00,
                "amount_total": 1000.00,
                "currency": "USD",
                "status": "completed",
                "status_ar": "مكتملة",
                "created_at": "2026-02-15 14:30:00"
            }
        ],
        "holdings": [
            {
                "id": 1,
                "offer_id": 5,
                "offer_title": "عرض أسهم شركة التقنية",
                "offer_cover_image": "http://localhost:8000/storage/offers/image.jpg",
                "offer_status": "active",
                "shares_owned": 10,
                "avg_price_per_share": 100.00,
                "current_price_per_share": 105.00,
                "currency": "USD",
                "total_investment": 1000.00,
                "current_value": 1050.00,
                "last_transaction_at": "2026-02-15 14:30:00"
            }
        ],
        "statistics": {
            "total_purchases": 1,
            "total_invested": 1000.00,
            "total_shares_owned": 10,
            "total_holdings": 1,
            "total_operations": 1
        }
    }
}
```

**📊 البيانات المتضمنة:**
- `user`: بيانات المستخدم الأساسية
- `buyer`: معلومات المشتري الكاملة (رقم جوال، هوية، حالة التحقق)
- `operations`: جميع العمليات (شراء/بيع/تحويل) مرتبة من الأحدث للأقدم
- `holdings`: الأسهم المملوكة حالياً مع القيمة الحالية والربح/الخسارة المحتمل
- `statistics`: إحصائيات ملخصة عن النشاط الكلي

---

### **5️⃣ تحديث بيانات المشتري (Update Profile)**

يحدث البيانات في **جدولين معاً**:
- جدول `users` - للمصادقة (البريد الإلكتروني وكلمة المرور)
- جدول `buyers` - للمعلومات الإضافية

⚠️ **مهم جداً:** عند تحديث البريد الإلكتروني، يتم تحديثه في الجدولين معاً تلقائياً.

```
Method: PUT
URL: http://localhost:8000/api/v1/auth/profile
```

**Headers:**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer YOUR_TOKEN
```

**Body (JSON) - كل الحقول اختيارية:**
```json
{
    "name": "أحمد عبدالله",
    "email": "new-email@example.com",
    "phone": "+966551234567",
    "national_id": "1234567890",
    "date_of_birth": "1990-01-15",
    "country": "Saudi Arabia",
    "city": "Riyadh",
    "address": "حي النرجس، شارع التحلية"
}
```

**لتحديث كلمة المرور:**
```json
{
    "current_password": "password123",
    "new_password": "newpassword456",
    "new_password_confirmation": "newpassword456"
}
```

**✅ الاستجابة عند النجاح (200):**
```json
{
    "success": true,
    "message": "تم تحديث البيانات بنجاح",
    "data": {
        "user": {
            "id": 1,
            "name": "أحمد عبدالله",
            "email": "new-email@example.com",
            "avatar": null
        },
        "buyer": {
            "id": 1,
            "full_name": "أحمد عبدالله",
            "email": "new-email@example.com",
            "phone": "+966551234567",
            "national_id": "1234567890",
            "date_of_birth": "1990-01-15",
            "country": "Saudi Arabia",
            "city": "Riyadh",
            "address": "حي النرجس، شارع التحلية",
            "kyc_status": "unverified"
        }
    }
}
```

**❌ رسائل الخطأ:**
```json
{
    "success": false,
    "message": "خطأ في البيانات المدخلة",
    "errors": {
        "email": ["البريد الإلكتروني مسجل مسبقاً"],
        "current_password": ["كلمة المرور الحالية غير صحيحة"]
    }
}
```

**الحقول القابلة للتحديث:**
- ✅ `name` - الاسم الكامل (يحدث في users و buyers)
- ✅ `email` - البريد الإلكتروني (يحدث في users و buyers معاً)
- ✅ `phone` - رقم الجوال
- ✅ `national_id` - رقم الهوية الوطنية
- ✅ `date_of_birth` - تاريخ الميلاد
- ✅ `country` - الدولة
- ✅ `city` - المدينة
- ✅ `address` - العنوان
- ✅ `current_password` + `new_password` - لتغيير كلمة المرور

**💡 ملاحظة:** بعد تحديث البريد الإلكتروني، استخدم البريد الجديد لتسجيل الدخول في المرة القادمة.

---

### **6️⃣ Purchase Shares from Any Offer**

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

## 🔄 السوق الثانوي - بيع وشراء الأسهم بين المشترين

### **7️⃣ عرض أسهم للبيع (Create Sale Offer)**

يمكن للمشتري عرض أسهمه المملوكة للبيع في السوق الثانوي.

```
Method: POST
URL: http://localhost:8000/api/v1/secondary-market/sell
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
    "holding_id": 1,
    "shares_count": 5,
    "price_per_share": 1250.00,
    "description": "أسهم في حالة ممتازة، استثمار مربح",
    "expires_in_days": 30
}
```

**الحقول:**
- `holding_id` **(مطلوب)** - ID الممتلكات من `/api/v1/auth/profile` → `holdings[].id`
- `shares_count` **(مطلوب)** - عدد الأسهم المراد بيعها (يجب أن يكون أقل من أو يساوي الأسهم المملوكة)
- `price_per_share` **(مطلوب)** - سعر البيع للسهم الواحد
- `description` **(اختياري)** - وصف للعرض
- `expires_in_days` **(اختياري)** - عدد الأيام حتى انتهاء العرض (افتراضي: لا ينتهي)

**✅ الاستجابة عند النجاح (201):**
```json
{
    "success": true,
    "message": "تم عرض الأسهم للبيع بنجاح",
    "data": {
        "sale_offer_id": 1,
        "shares_count": 5,
        "price_per_share": 1250.00,
        "total_value": 6250.00,
        "currency": "SAR",
        "status": "active",
        "expires_at": "2026-03-18 14:30:00"
    }
}
```

---

### **8️⃣ عروضي المعروضة للبيع (My Sale Offers)**

عرض جميع عروض البيع الخاصة بالمشتري (نشطة، مباعة، ملغية).

```
Method: GET
URL: http://localhost:8000/api/v1/secondary-market/my-sale-offers
```

**Headers:**
```
Accept: application/json
Authorization: Bearer YOUR_TOKEN
```

**✅ الاستجابة (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "offer_title": "فرصة استثمارية في مركز طبي",
            "offer_cover_image": "http://localhost:8000/storage/offers/image.jpg",
            "shares_count": 5,
            "price_per_share": 1250.00,
            "total_value": 6250.00,
            "currency": "SAR",
            "status": "active",
            "status_ar": "نشط",
            "description": "أسهم في حالة ممتازة",
            "expires_at": "2026-03-18 14:30:00",
            "sold_at": null,
            "created_at": "2026-02-16 14:30:00"
        }
    ]
}
```

---

### **9️⃣ إلغاء عرض بيع (Cancel Sale Offer)**

إلغاء عرض بيع نشط.

```
Method: DELETE
URL: http://localhost:8000/api/v1/secondary-market/sale-offers/{saleOfferId}
```

**Headers:**
```
Accept: application/json
Authorization: Bearer YOUR_TOKEN
```

**✅ الاستجابة (200):**
```json
{
    "success": true,
    "message": "تم إلغاء عرض البيع بنجاح"
}
```

---

### **🔟 شراء من السوق الثانوي (Buy from Secondary Market)**

شراء أسهم معروضة للبيع من مشتري آخر.

```
Method: POST
URL: http://localhost:8000/api/v1/secondary-market/buy
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
    "sale_offer_id": 1
}
```

**✅ الاستجابة عند النجاح (200):**
```json
{
    "success": true,
    "message": "تم شراء الأسهم بنجاح من السوق الثانوي",
    "data": {
        "shares_count": 5,
        "price_per_share": 1250.00,
        "total_amount": 6250.00,
        "currency": "SAR",
        "offer_title": "فرصة استثمارية في مركز طبي"
    }
}
```

**📝 ما يحدث عند الشراء:**
1. ✅ تُخصم الأسهم من ممتلكات البائع
2. ✅ تُضاف الأسهم لممتلكات المشتري
3. ✅ تُسجل عملية بيع للبائع (type: `sell`)
4. ✅ تُسجل عملية شراء للمشتري (type: `purchase`)
5. ✅ يتحدث حالة العرض إلى `sold`
6. ✅ يتم تتبع السهم عبر جميع العمليات

---

### **1️⃣1️⃣ البروفايل المحدّث (Updated Profile)**

الآن يتضمن البروفايل:
- ✅ `operations` - جميع عمليات الشراء **والبيع**
- ✅ `holdings` - الأسهم المملوكة حالياً
- ✅ `sale_offers` - عروض البيع النشطة والسابقة
- ✅ `statistics.total_sales` - عدد المبيعات
- ✅ `statistics.total_sales_amount` - إجمالي المبيعات
- ✅ `statistics.active_sale_offers` - عروض البيع النشطة

---

### **6️⃣ Buyer Dashboard**

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

### **7️⃣ Buyer Operations**

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

### **8️⃣ Buyer Shares**

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

## 🔐 Summary of Changes

### ✅ What Has Been Changed:

1. **Registration**: No need for `tenant_domain`
2. **Login**: No need for `tenant_domain`
3. **Data**: Saved in central database
4. **Purchase**: Buyer can purchase from any offer without restrictions

### ❌ What Has Been Removed:

- ❌ `tenant_domain` field from registration
- ❌ `tenant_domain` field from login
- ❌ Tenant verification for buyers
- ❌ Tenant database for buyers

---

## 📊 New Structure:

```
Central Database
├── users (Buyers)
├── share_offers (Offers)
├── share_operations (Purchase operations)
└── tenants (Tenants)

Tenant Database
└── (Used only for tenant to manage their offers)
```

---

## 🎯 Quick Testing Steps:

1. ✅ Register buyer: `POST /auth/register` (without tenant_domain)
2. ✅ Login: `POST /auth/login` (without tenant_domain)
3. ✅ View offers: `GET /offers`
4. ✅ Purchase: `POST /purchase` (with token)

---

## ⚠️ Important Notes:

- Buyer is **global** and doesn't belong to any tenant
- Buyer can **purchase from any offer**
- Offers are **shared** for everyone
- Token is valid for all operations

---

**Now try registration without tenant_domain!** 🎉
