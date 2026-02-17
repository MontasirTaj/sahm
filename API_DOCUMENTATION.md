# Sahm - API Documentation for Mobile Application

## Overview

This API is specifically designed for the Sahm real estate investment platform mobile application.
All endpoints are completely separate from the website and will not affect it.

**Base URL:** `https://your-domain.com/api/`

**Version:** `v1`

---

## User Types

The system supports **TWO types of users**:

### 🛒 Buyers (المشترون)
- Global users stored in **central database**
- Can purchase from any offer
- **Don't require** `tenant_domain` for authentication
- Endpoints: `/auth/register`, `/auth/login`

### 👨‍💼 Tenant Admins (مديرو Tenant)
- Users stored in **tenant-specific database**
- Can manage offers for their tenant
- **Require** `tenant_domain` for authentication
- Endpoints: `/auth/tenant-register`, `/auth/tenant-login`

**📚 For detailed authentication guide, see:** [API_AUTH_GUIDE.md](API_AUTH_GUIDE.md)

---

## Authentication

The API uses Laravel Sanctum system for authentication via API Tokens.

### Required Headers

```
Content-Type: application/json
Accept: application/json
X-Tenant-Domain: tenant-domain.com
Authorization: Bearer {token}  (للـ endpoints المحمية)
```

---

## 1. Register New User

**Endpoint:** `POST /api/v1/auth/register`

**Description:** تسجيل مشتري جديد في النظام

**Parameters:**
```json
{
    "tenant_domain": "tenant-domain.com",
    "name": "أحمد محمد",
    "email": "ahmed@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response (Success - 201):**
```json
{
    "success": true,
    "message": "تم التسجيل بنجاح",
    "data": {
        "user": {
            "id": 1,
            "name": "أحمد محمد",
            "email": "ahmed@example.com",
            "avatar": null
        },
        "token": "1|abcdefghijklmnopqrstuvwxyz",
        "tenant_domain": "tenant-domain.com"
    }
}
```

---

## 2. Login

**Endpoint:** `POST /api/v1/auth/login`

**Description:** تسجيل دخول المستخدم والحصول على API Token

**Parameters:**
```json
{
    "tenant_domain": "tenant-domain.com",
    "email": "ahmed@example.com",
    "password": "password123"
}
```

**Response (Success - 200):**
```json
{
    "success": true,
    "message": "تم تسجيل الدخول بنجاح",
    "data": {
        "user": {
            "id": 1,
            "name": "أحمد محمد",
            "email": "ahmed@example.com",
            "avatar": null,
            "roles": ["buyer"]
        },
        "token": "2|xyzabcdefghijklmnopqrstuvw",
        "tenant_domain": "tenant-domain.com"
    }
}
```

---

## 3. Logout

**Endpoint:** `POST /api/v1/auth/logout`

**Description:** تسجيل خروج المستخدم وحذف الـ Token الحالي

**Headers:** `Authorization: Bearer {token}`

**Response (Success - 200):**
```json
{
    "success": true,
    "message": "تم تسجيل الخروج بنجاح"
}
```

---

## 4. Get User Data

**Endpoint:** `GET /api/v1/auth/profile`

**Description:** الحصول على بيانات المستخدم الحالي

**Headers:** `Authorization: Bearer {token}`

**Response (Success - 200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "أحمد محمد",
        "email": "ahmed@example.com",
        "avatar": null,
        "roles": ["buyer"],
        "permissions": []
    }
}
```

---

## 5. Update User Data

**Endpoint:** `PUT /api/v1/auth/profile`

**Description:** تحديث الاسم، الصورة الشخصية، أو كلمة المرور

**Headers:** `Authorization: Bearer {token}`

**Parameters (Form Data):**
```json
{
    "name": "أحمد محمد الجديد",
    "avatar": "file",
    "current_password": "password123",
    "new_password": "newpassword123",
    "new_password_confirmation": "newpassword123"
}
```

**Response (Success - 200):**
```json
{
    "success": true,
    "message": "تم تحديث البيانات بنجاح",
    "data": {
        "id": 1,
        "name": "أحمد محمد الجديد",
        "email": "ahmed@example.com",
        "avatar": "/storage/avatars/xyz.jpg"
    }
}
```

---

## 6. View Offers List

**Endpoint:** `GET /api/v1/offers`

**Description:** الحصول على قائمة العروض مع إمكانية الفلترة والبحث

**Headers:** `X-Tenant-Domain: tenant-domain.com`

**Query Parameters:**
- `tenant_domain` (string) - النطاق
- `city` (string) - تصفية حسب المدينة
- `status` (string) - حالة العرض (active, inactive, etc.)
- `availability` (string) - available أو sold_out
- `min_price` (number) - الحد الأدنى للسعر
- `max_price` (number) - الحد الأقصى للسعر
- `search` (string) - البحث في العنوان والمدينة والعنوان
- `sort_by` (string) - الترتيب حسب (created_at, price_per_share, etc.)
- `sort_order` (string) - asc أو desc
- `per_page` (number) - عدد النتائج في الصفحة (default: 15)
- `page` (number) - رقم الصفحة

**Example:**
```
GET /api/v1/offers?tenant_domain=tenant.com&city=الرياض&availability=available&per_page=20&page=1
```

**Response (Success - 200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Investment Opportunity",
            "title_ar": "فرصة استثمارية",
            "city": "الرياض",
            "price_per_share": 1000,
            "available_shares": 500,
            "total_shares": 1000,
            "status": "active"
        }
    ],
    "pagination": {
        "total": 50,
        "per_page": 20,
        "current_page": 1,
        "last_page": 3,
        "from": 1,
        "to": 20
    },
    "stats": {
        "total_offers": 50,
        "total_cities": 5,
        "average_price": 1500
    }
}
```

---

## 7. Specific Offer Details

**Endpoint:** `GET /api/v1/offers/{id}`

**Description:** الحصول على تفاصيل عرض معين

**Headers:** `X-Tenant-Domain: tenant-domain.com`

**Response (Success - 200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Investment Opportunity",
        "title_ar": "فرصة استثمارية مميزة",
        "description": "Description",
        "description_ar": "وصف تفصيلي للعرض...",
        "country": "Saudi Arabia",
        "city": "الرياض",
        "address": "حي النرجس، شارع التحلية",
        "total_shares": 1000,
        "available_shares": 500,
        "sold_shares": 500,
        "sold_percentage": 50,
        "price_per_share": 1000,
        "currency": "SAR",
        "status": "active",
        "is_available": true,
        "is_active": true,
        "cover_image": "/storage/offers/image.jpg",
        "media": [],
        "metadata": {},
        "starts_at": "2026-01-01 00:00:00",
        "ends_at": "2026-12-31 23:59:59"
    }
}
```

---

## 8. Get Available Cities

**Endpoint:** `GET /api/v1/offers/meta/cities`

**Description:** الحصول على قائمة المدن المتاحة في العروض

**Headers:** `X-Tenant-Domain: tenant-domain.com`

**Response (Success - 200):**
```json
{
    "success": true,
    "data": ["الرياض", "جدة", "الدمام", "مكة", "المدينة"]
}
```

---

## 9. Offers Statistics

**Endpoint:** `GET /api/v1/offers/meta/statistics`

**Description:** الحصول على إحصائيات عامة عن العروض

**Headers:** `X-Tenant-Domain: tenant-domain.com`

**Response (Success - 200):**
```json
{
    "success": true,
    "data": {
        "total_offers": 50,
        "active_offers": 45,
        "available_offers": 40,
        "total_cities": 5,
        "average_price": 1500,
        "min_price": 500,
        "max_price": 5000,
        "total_shares": 50000,
        "sold_shares": 25000,
        "available_shares": 25000
    }
}
```

---

## 10. Purchase Shares

**Endpoint:** `POST /api/v1/purchase`

**Description:** إنشاء طلب شراء أسهم من عرض محدد

**Headers:** `Authorization: Bearer {token}`

**Parameters:**
```json
{
    "tenant_domain": "tenant-domain.com",
    "offer_id": 1,
    "shares_count": 10,
    "payment_method": "credit_card"
}
```

**Response (Success - 201):**
```json
{
    "success": true,
    "message": "تم إنشاء طلب الشراء بنجاح. يرجى إكمال الدفع.",
    "data": {
        "operation_id": 123,
        "external_reference": "OP-ABC123XYZ",
        "shares_count": 10,
        "price_per_share": 1000,
        "amount_total": 10000,
        "currency": "SAR",
        "status": "pending",
        "payment_method": "credit_card",
        "offer": {
            "id": 1,
            "title": "فرصة استثمارية مميزة",
            "city": "الرياض"
        }
    }
}
```

---

## 11. Confirm Payment

**Endpoint:** `POST /api/v1/purchase/confirm-payment`

**Description:** تأكيد عملية الدفع بعد إتمامها

**Headers:** `Authorization: Bearer {token}`

**Parameters:**
```json
{
    "operation_id": 123,
    "payment_id": "PAY-XYZ123ABC",
    "payment_status": "completed"
}
```

**Response (Success - 200):**
```json
{
    "success": true,
    "message": "تم إتمام عملية الشراء بنجاح",
    "data": {
        "operation_id": 123,
        "status": "completed",
        "external_reference": "OP-ABC123XYZ"
    }
}
```

---

## 12. Cancel Purchase Operation

**Endpoint:** `POST /api/v1/purchase/{operationId}/cancel`

**Description:** إلغاء عملية شراء في حالة pending

**Headers:** `Authorization: Bearer {token}`

**Response (Success - 200):**
```json
{
    "success": true,
    "message": "تم إلغاء العملية بنجاح",
    "data": {
        "operation_id": 123,
        "status": "cancelled"
    }
}
```

---

## 13. Buyer Dashboard

**Endpoint:** `GET /api/v1/buyer/dashboard`

**Description:** الحصول على إحصائيات وبيانات لوحة تحكم المشتري

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `tenant_id` (optional) - تصفية حسب Tenant معين

**Response (Success - 200):**
```json
{
    "success": true,
    "data": {
        "stats": {
            "total_operations": 25,
            "completed_operations": 20,
            "pending_operations": 3,
            "total_shares_owned": 150,
            "total_spent": 150000,
            "shares_by_type": {
                "purchase": {
                    "count": 150,
                    "total_amount": 150000
                }
            },
            "operations_by_status": {
                "completed": 20,
                "pending": 3,
                "cancelled": 2
            }
        },
        "recent_operations": [
            {
                "id": 123,
                "type": "purchase",
                "status": "completed",
                "shares_count": 10,
                "amount_total": 10000,
                "currency": "SAR",
                "external_reference": "OP-ABC123",
                "created_at": "2026-02-01 10:30:00",
                "offer_title": "فرصة استثمارية"
            }
        ]
    }
}
```

---

## 14. Buyer Operations

**Endpoint:** `GET /api/v1/buyer/operations`

**Description:** الحصول على قائمة جميع عمليات المشتري مع الفلترة

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `tenant_id` (optional)
- `type` (string) - purchase, sale, transfer
- `status` (string) - completed, pending, cancelled, failed
- `from_date` (date) - التاريخ من
- `to_date` (date) - التاريخ إلى
- `sort_by` (string) - الترتيب حسب
- `sort_order` (string) - asc أو desc
- `per_page` (number) - عدد النتائج
- `page` (number) - رقم الصفحة

**Response (Success - 200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 123,
            "type": "purchase",
            "status": "completed",
            "shares_count": 10,
            "price_per_share": 1000,
            "amount_total": 10000,
            "currency": "SAR",
            "external_reference": "OP-ABC123",
            "payment_id": "PAY-XYZ456",
            "created_at": "2026-02-01 10:30:00",
            "offer": {
                "id": 1,
                "title": "فرصة استثمارية",
                "city": "الرياض",
                "status": "active"
            }
        }
    ],
    "pagination": {
        "total": 25,
        "per_page": 15,
        "current_page": 1,
        "last_page": 2,
        "from": 1,
        "to": 15
    }
}
```

---

## 15. Specific Operation Details

**Endpoint:** `GET /api/v1/buyer/operations/{operationId}`

**Description:** الحصول على تفاصيل عملية معينة

**Headers:** `Authorization: Bearer {token}`

**Response (Success - 200):**
```json
{
    "success": true,
    "data": {
        "id": 123,
        "type": "purchase",
        "status": "completed",
        "shares_count": 10,
        "price_per_share": 1000,
        "amount_total": 10000,
        "currency": "SAR",
        "external_reference": "OP-ABC123",
        "payment_id": "PAY-XYZ456",
        "metadata": {
            "payment_method": "credit_card",
            "buyer_name": "أحمد محمد",
            "offer_title": "فرصة استثمارية"
        },
        "created_at": "2026-02-01 10:30:00",
        "updated_at": "2026-02-01 10:35:00",
        "offer": {
            "id": 1,
            "title": "فرصة استثمارية مميزة",
            "description": "وصف تفصيلي...",
            "city": "الرياض",
            "address": "حي النرجس",
            "status": "active",
            "price_per_share": 1000,
            "cover_image": "/storage/offers/image.jpg"
        }
    }
}
```

---

## 16. Owned Shares

**Endpoint:** `GET /api/v1/buyer/my-shares`

**Description:** الحصول على الأسهم المملوكة للمشتري مجمعة حسب العرض

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `tenant_id` (optional)

**Response (Success - 200):**
```json
{
    "success": true,
    "data": [
        {
            "offer_id": 1,
            "offer_title": "فرصة استثمارية مميزة",
            "offer_city": "الرياض",
            "total_shares": 25,
            "total_invested": 25000,
            "average_price": 1000,
            "current_price": 1100,
            "operations_count": 3
        }
    ],
    "summary": {
        "total_offers": 3,
        "total_shares": 150,
        "total_invested": 150000
    }
}
```

---

## 17. Health Check

**Endpoint:** `GET /api/health`

**Description:** التحقق من أن الـ API يعمل

**Response (Success - 200):**
```json
{
    "status": "ok",
    "message": "API is working",
    "timestamp": "2026-02-15 10:30:00",
    "version": "v1"
}
```

---

## Status Codes

- `200` - نجح الطلب
- `201` - تم الإنشاء بنجاح
- `400` - طلب غير صحيح
- `401` - غير مصرح (تسجيل دخول مطلوب)
- `403` - ممنوع (ليس لديك صلاحية)
- `404` - غير موجود
- `422` - خطأ في التحقق من البيانات
- `500` - خطأ في الخادم

---

## تنسيق الأخطاء

جميع الأخطاء تأتي بالتنسيق التالي:

```json
{
    "success": false,
    "message": "رسالة الخطأ بالعربية",
    "errors": {
        "field_name": ["رسالة الخطأ"]
    }
}
```

---

## ملاحظات مهمة

1. **Tenant Domain**: يجب إرسال `tenant_domain` أو `X-Tenant-Domain` في كل طلب
2. **Authorization**: الـ endpoints المحمية تتطلب `Bearer Token`
3. **Content-Type**: استخدم `application/json` لجميع الطلبات
4. **Pagination**: جميع القوائم تدعم التصفح (pagination)
5. **Filtering**: معظم القوائم تدعم الفلترة والبحث
6. **Date Format**: التواريخ بتنسيق `Y-m-d H:i:s`

---

## أمثلة باستخدام cURL

### تسجيل الدخول
```bash
curl -X POST https://your-domain.com/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "tenant_domain": "tenant.com",
    "email": "ahmed@example.com",
    "password": "password123"
  }'
```

### الحصول على العروض
```bash
curl -X GET "https://your-domain.com/api/v1/offers?tenant_domain=tenant.com&city=الرياض" \
  -H "Accept: application/json" \
  -H "X-Tenant-Domain: tenant.com"
```

### شراء أسهم
```bash
curl -X POST https://your-domain.com/api/v1/purchase \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "tenant_domain": "tenant.com",
    "offer_id": 1,
    "shares_count": 10,
    "payment_method": "credit_card"
  }'
```

---

## دعم فني

للمزيد من المعلومات أو في حالة وجود مشاكل، يرجى التواصل مع الفريق التقني.
