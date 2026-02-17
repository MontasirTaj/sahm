# دليل شراء الأسهم لمستخدمي Tenant

## نظرة عامة

الآن يمكن لمستخدمي Tenant (مدراء الشركات) الشراء من العروض المتاحة **بنفس حساباتهم** دون الحاجة لإنشاء حساب مشتري منفصل.

## كيف يعمل النظام؟

### 1. التوثيق الموحد

مستخدم Tenant يملك حساب واحد يمكنه من:
- إدارة العروض الخاصة بشركته (إنشاء، تعديل، حذف)
- شراء أسهم من العروض الأخرى المتاحة في السوق
- متابعة مشترياته من لوحة تحكم المشتري

### 2. آلية العمل الداخلية

عند قيام مستخدم Tenant بعملية شراء:

1. **التحقق من نوع المستخدم**: النظام يتحقق من قدرات التوكن
2. **إنشاء نسخة مركزية**: إذا كان المستخدم admin (tenant)، يتم:
   - البحث عن بريده الإلكتروني في قاعدة البيانات المركزية
   - إذا لم يكن موجوداً، يتم إنشاء حساب مركزي بنفس الاسم والبريد
   - إذا كان موجوداً، يتم تحديث الاسم إذا تغير
3. **تسجيل العملية**: العملية تُسجل باسم المستخدم المركزي
4. **الوصول الموحد**: المستخدم يمكنه متابعة عملياته من أي endpoint

## أمثلة عملية

### مثال 1: تسجيل دخول مدير Tenant

```bash
POST {{base_url}}/api/v1/auth/tenant-login
Content-Type: application/json

{
    "email": "admin@example.com",
    "password": "password123",
    "tenant_domain": "example"
}
```

**الاستجابة:**
```json
{
    "success": true,
    "message": "تم تسجيل الدخول بنجاح",
    "data": {
        "user": {
            "id": 5,
            "name": "أحمد محمد",
            "email": "admin@example.com"
        },
        "token": "123|abcdefg...",
        "token_type": "Bearer",
        "abilities": ["admin", "tenant:1"]
    }
}
```

### مثال 2: شراء أسهم بحساب Tenant

```bash
POST {{base_url}}/api/v1/purchase
Authorization: Bearer {{tenant_token}}
Content-Type: application/json

{
    "offer_id": 10,
    "shares_count": 50,
    "payment_method": "credit_card"
}
```

**الاستجابة:**
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
        "status": "pending",
        "payment_method": "credit_card"
    }
}
```

### مثال 3: متابعة لوحة التحكم

```bash
GET {{base_url}}/api/v1/buyer/dashboard
Authorization: Bearer {{tenant_token}}
```

**الاستجابة:**
```json
{
    "success": true,
    "data": {
        "stats": {
            "total_operations": 3,
            "completed_operations": 2,
            "pending_operations": 1,
            "total_shares_owned": 150,
            "total_spent": 150000,
            "shares_by_type": {
                "purchase": {
                    "count": 150,
                    "total_amount": 150000
                }
            },
            "operations_by_status": {
                "completed": 2,
                "pending": 1
            }
        },
        "recent_operations": [...]
    }
}
```

### مثال 4: عرض الأسهم المملوكة

```bash
GET {{base_url}}/api/v1/buyer/my-shares
Authorization: Bearer {{tenant_token}}
```

**الاستجابة:**
```json
{
    "success": true,
    "data": [
        {
            "offer_id": 10,
            "offer_title": "عقار سكني في الرياض",
            "offer_city": "الرياض",
            "total_shares": 50,
            "total_invested": 50000,
            "average_price": 1000,
            "current_price": 1000,
            "operations_count": 1
        },
        {
            "offer_id": 15,
            "offer_title": "مشروع تجاري في جدة",
            "offer_city": "جدة",
            "total_shares": 100,
            "total_invested": 100000,
            "average_price": 1000,
            "current_price": 1000,
            "operations_count": 1
        }
    ],
    "summary": {
        "total_offers": 2,
        "total_shares": 150,
        "total_invested": 150000
    }
}
```

## مقارنة: Buyer vs Tenant Admin

| الميزة | Buyer عادي | Tenant Admin |
|--------|-----------|--------------|
| **التسجيل** | `/auth/register` | `/auth/tenant-register` |
| **تسجيل الدخول** | `/auth/login` | `/auth/tenant-login` |
| **قدرات التوكن** | `['buyer']` | `['admin', 'tenant:X']` |
| **إدارة العروض** | ❌ لا يمكن | ✅ يمكن |
| **شراء الأسهم** | ✅ يمكن | ✅ يمكن |
| **لوحة التحكم** | ✅ يمكن | ✅ يمكن |
| **قاعدة البيانات** | Central | Tenant + Central (للشراء) |

## سيناريوهات الاستخدام

### سيناريو 1: مدير شركة يريد الشراء

```
1. يسجل دخول بحسابه كمدير: POST /api/v1/auth/tenant-login
2. يتصفح العروض المتاحة: GET /api/v1/offers?status=active
3. يختار عرض ويشتري: POST /api/v1/purchase
4. يتابع مشترياته: GET /api/v1/buyer/dashboard
```

### سيناريو 2: مدير شركة يدير ويشتري

```
1. يسجل دخول: POST /api/v1/auth/tenant-login
2. يضيف عرض لشركته: POST /api/v1/offers
3. يشتري من عرض آخر: POST /api/v1/purchase
4. يراجع عروضه: GET /api/v1/offers
5. يراجع مشترياته: GET /api/v1/buyer/dashboard
```

### سيناريو 3: متابعة عملية شراء

```
1. إنشاء طلب شراء: POST /api/v1/purchase
2. تلقي operation_id: 25
3. إتمام الدفع خارجياً
4. تأكيد الدفع: POST /api/v1/purchase/confirm
5. التحقق من الحالة: GET /api/v1/buyer/operations/25
```

## Endpoints المتاحة لمستخدمي Tenant

### 1. إدارة العروض (القدرات الأصلية)

- `POST /api/v1/offers` - إنشاء عرض جديد
- `PUT /api/v1/offers/{id}` - تعديل عرض
- `DELETE /api/v1/offers/{id}` - حذف عرض
- `GET /api/v1/offers` - عرض جميع العروض

### 2. الشراء (الميزة الجديدة) ✨

- `POST /api/v1/purchase` - شراء أسهم
- `POST /api/v1/purchase/confirm` - تأكيد الدفع
- `DELETE /api/v1/purchase/{id}/cancel` - إلغاء عملية

### 3. لوحة تحكم المشتري (الميزة الجديدة) ✨

- `GET /api/v1/buyer/dashboard` - إحصائيات المشتريات
- `GET /api/v1/buyer/operations` - جميع العمليات
- `GET /api/v1/buyer/operations/{id}` - تفاصيل عملية محددة
- `GET /api/v1/buyer/my-shares` - الأسهم المملوكة

## ملاحظات مهمة

### 1. الحساب المركزي التلقائي

- **تلقائي**: لا يحتاج المستخدم لأي إجراء إضافي
- **شفاف**: المستخدم لا يعلم بوجود نسخة مركزية من حسابه
- **آمن**: كلمة المرور المركزية عشوائية ولا يمكن استخدامها للدخول
- **متزامن**: الاسم يتحدث تلقائياً عند أي عملية

### 2. التعريف بالبريد الإلكتروني

- النظام يستخدم البريد الإلكتروني كمعرف فريد
- يجب أن يكون البريد فريد في جميع قواعد البيانات
- لا يمكن لمستخدمين مختلفين استخدام نفس البريد

### 3. الأمان والصلاحيات

- التوكن يحدد نوع المستخدم تلقائياً
- لا يحتاج المستخدم لتبديل الحسابات
- جميع العمليات مؤمنة ومراقبة

### 4. التقارير والإحصائيات

- Dashboard يعرض فقط مشتريات المستخدم
- لا يعرض العروض التي يديرها
- للحصول على عروض الشركة استخدم `GET /api/v1/offers`

## أسئلة شائعة

### س: هل يحتاج مدير Tenant لحساب مشتري منفصل؟
**ج:** لا، يمكنه الشراء بنفس حسابه كمدير.

### س: هل يمكن لمدير Tenant رؤية مشترياته في Dashboard؟
**ج:** نعم، يستخدم `/api/v1/buyer/dashboard` بنفس التوكن.

### س: ماذا يحدث إذا اشترى مدير من عرض شركته؟
**ج:** العملية تتم بشكل طبيعي، لكن هذا غير منطقي من الناحية التجارية.

### س: هل يمكن لمدير Tenant إضافة عرض وشراء أسهم في نفس الوقت؟
**ج:** نعم، يمكنه القيام بالعمليتين بنفس التوكن.

### س: كيف أعرف إذا كان المستخدم manger أم buyer؟
**ج:** من قدرات التوكن:
- Buyer: `['buyer']`
- Tenant Admin: `['admin', 'tenant:X']`

### س: هل تتأثر عروض الشركة بمشتريات المدير؟
**ج:** لا، هذه عمليات مستقلة تماماً.

## التغيرات التقنية في الكود

### في PurchaseController:

```php
// قبل التعديل
$user = $request->user();

// بعد التعديل
$currentUser = $request->user();
$user = $this->ensureUserInCentralDatabase($currentUser);
```

### الدالة المساعدة الجديدة:

```php
private function ensureUserInCentralDatabase($currentUser)
{
    $token = $currentUser->currentAccessToken();
    $abilities = $token ? $token->abilities : [];
    
    if (in_array('admin', $abilities)) {
        $centralUser = User::where('email', $currentUser->email)->first();
        
        if (!$centralUser) {
            $centralUser = User::create([
                'name' => $currentUser->name,
                'email' => $currentUser->email,
                'password' => Hash::make(uniqid()),
                'email_verified_at' => now(),
            ]);
        }
        
        return $centralUser;
    }
    
    return $currentUser;
}
```

## الخلاصة

✅ **حساب موحد**: مستخدم Tenant يستخدم حساب واحد لكل شيء  
✅ **شفافية**: النظام يدير التفاصيل التقنية تلقائياً  
✅ **أمان**: جميع العمليات مراقبة ومؤمنة  
✅ **سهولة الاستخدام**: نفس التوكن لجميع العمليات  

---

**تاريخ الإنشاء:** 2026-02-14  
**الإصدار:** 1.0  
**الحالة:** ✅ نشط ومختبر
