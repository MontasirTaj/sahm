# سهمي API - اختبار سريع

## اختبار الـ API

### 1. تحقق من أن الـ API يعمل

```bash
curl http://localhost:8000/api/health
```

**Expected:**
```json
{
    "status": "ok",
    "message": "API is working",
    "timestamp": "2026-02-15 12:00:00",
    "version": "v1"
}
```

---

### 2. استخدام Postman

#### A. تسجيل مستخدم جديد

**Method:** POST  
**URL:** `http://localhost:8000/api/v1/auth/register`  
**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Body (JSON):**
```json
{
    "tenant_domain": "your-tenant-domain.com",
    "name": "أحمد محمد",
    "email": "ahmed@test.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Save the Token from the Response!**

---

#### B. تسجيل الدخول

**Method:** POST  
**URL:** `http://localhost:8000/api/v1/auth/login`  
**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Body (JSON):**
```json
{
    "tenant_domain": "your-tenant-domain.com",
    "email": "ahmed@test.com",
    "password": "password123"
}
```

---

#### C. الحصول على العروض

**Method:** GET  
**URL:** `http://localhost:8000/api/v1/offers?tenant_domain=your-tenant-domain.com`  
**Headers:**
```
Accept: application/json
X-Tenant-Domain: your-tenant-domain.com
```

---

#### D. شراء أسهم (يتطلب Token)

**Method:** POST  
**URL:** `http://localhost:8000/api/v1/purchase`  
**Headers:**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer YOUR_TOKEN_HERE
```

**Body (JSON):**
```json
{
    "tenant_domain": "your-tenant-domain.com",
    "offer_id": 1,
    "shares_count": 5,
    "payment_method": "credit_card"
}
```

---

#### E. لوحة تحكم المشتري (يتطلب Token)

**Method:** GET  
**URL:** `http://localhost:8000/api/v1/buyer/dashboard`  
**Headers:**
```
Accept: application/json
Authorization: Bearer YOUR_TOKEN_HERE
```

---

### 3. استخدام cURL

#### تسجيل دخول
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"tenant_domain\":\"tenant.com\",\"email\":\"ahmed@test.com\",\"password\":\"password123\"}"
```

#### الحصول على العروض
```bash
curl -X GET "http://localhost:8000/api/v1/offers?tenant_domain=tenant.com" \
  -H "Accept: application/json" \
  -H "X-Tenant-Domain: tenant.com"
```

#### شراء (مع token)
```bash
curl -X POST http://localhost:8000/api/v1/purchase \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d "{\"tenant_domain\":\"tenant.com\",\"offer_id\":1,\"shares_count\":5,\"payment_method\":\"credit_card\"}"
```

---

### 4. استخدام PHP/Guzzle في تطبيق آخر

```php
use GuzzleHttp\Client;

$client = new Client([
    'base_uri' => 'http://localhost:8000/api/',
    'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ]
]);

// Login
$response = $client->post('v1/auth/login', [
    'json' => [
        'tenant_domain' => 'tenant.com',
        'email' => 'ahmed@test.com',
        'password' => 'password123'
    ]
]);

$data = json_decode($response->getBody(), true);
$token = $data['data']['token'];

// Get offers
$response = $client->get('v1/offers', [
    'query' => ['tenant_domain' => 'tenant.com'],
    'headers' => ['X-Tenant-Domain' => 'tenant.com']
]);

$offers = json_decode($response->getBody(), true);

// Purchase
$response = $client->post('v1/purchase', [
    'headers' => ['Authorization' => "Bearer {$token}"],
    'json' => [
        'tenant_domain' => 'tenant.com',
        'offer_id' => 1,
        'shares_count' => 5,
        'payment_method' => 'credit_card'
    ]
]);
```

---

### 5. Flutter/Dart (لتطبيق الموبايل)

```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

class ApiService {
  static const String baseUrl = 'http://your-domain.com/api';
  static String? token;

  // Login
  static Future<Map<String, dynamic>> login(
    String tenantDomain,
    String email,
    String password,
  ) async {
    final response = await http.post(
      Uri.parse('$baseUrl/v1/auth/login'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({
        'tenant_domain': tenantDomain,
        'email': email,
        'password': password,
      }),
    );

    final data = jsonDecode(response.body);
    if (data['success']) {
      token = data['data']['token'];
    }
    return data;
  }

  // Get offers
  static Future<List> getOffers(String tenantDomain) async {
    final response = await http.get(
      Uri.parse('$baseUrl/v1/offers?tenant_domain=$tenantDomain'),
      headers: {
        'Accept': 'application/json',
        'X-Tenant-Domain': tenantDomain,
      },
    );

    final data = jsonDecode(response.body);
    return data['data'];
  }

  // Purchase shares
  static Future<Map<String, dynamic>> purchase(
    String tenantDomain,
    int offerId,
    int sharesCount,
    String paymentMethod,
  ) async {
    final response = await http.post(
      Uri.parse('$baseUrl/v1/purchase'),
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer $token',
      },
      body: jsonEncode({
        'tenant_domain': tenantDomain,
        'offer_id': offerId,
        'shares_count': sharesCount,
        'payment_method': paymentMethod,
      }),
    );

    return jsonDecode(response.body);
  }
}
```

---

### 6. خطوات إضافية مطلوبة

#### Check Tenant Domain
تأكد من أن لديك tenant في قاعدة البيانات:

```sql
-- In central database
SELECT * FROM tenants WHERE domain = 'your-tenant-domain.com';
```

إذا لم يكن موجوداً، أنشئه:
```sql
INSERT INTO tenants (domain, database, status, created_at, updated_at) 
VALUES ('tenant.com', 'tenant_database_name', 'active', NOW(), NOW());
```

#### إنشاء دور buyer في Tenant
```bash
php artisan tinker

# In tinker
DB::connection('tenant')->table('roles')->insert([
    'name' => 'buyer',
    'guard_name' => 'web',
    'created_at' => now(),
    'updated_at' => now(),
]);
```

---

### 7. استكشاف الأخطاء

#### خطأ "tenant_domain مطلوب"
- تأكد من إرسال `tenant_domain` في الـ body أو `X-Tenant-Domain` في الـ header

#### خطأ "Unauthenticated"
- تأكد من إرسال `Authorization: Bearer {token}` في الـ header

#### خطأ "النطاق غير موجود"
- تحقق من وجود tenant في قاعدة البيانات المركزية

#### خطأ "دور buyer غير موجود"
- أنشئ دور buyer في قاعدة بيانات tenant

---

## Endpoints المتاحة

✅ **Public (بدون مصادقة):**
- `GET /api/health` - Health check
- `POST /api/v1/auth/register` - التسجيل
- `POST /api/v1/auth/login` - تسجيل الدخول
- `GET /api/v1/offers` - قائمة العروض
- `GET /api/v1/offers/{id}` - تفاصيل عرض
- `GET /api/v1/offers/meta/cities` - المدن
- `GET /api/v1/offers/meta/statistics` - الإحصائيات

🔒 **Protected (تتطلب مصادقة):**
- `POST /api/v1/auth/logout` - تسجيل الخروج
- `GET /api/v1/auth/profile` - البيانات الشخصية
- `PUT /api/v1/auth/profile` - تحديث البيانات
- `POST /api/v1/purchase` - الشراء
- `POST /api/v1/purchase/confirm-payment` - تأكيد الدفع
- `POST /api/v1/purchase/{id}/cancel` - إلغاء
- `GET /api/v1/buyer/dashboard` - لوحة التحكم
- `GET /api/v1/buyer/operations` - العمليات
- `GET /api/v1/buyer/operations/{id}` - تفاصيل عملية
- `GET /api/v1/buyer/my-shares` - أسهمي

---

للتوثيق الكامل، راجع `API_DOCUMENTATION.md`
