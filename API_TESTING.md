# Sahm API - Quick Testing

## Testing the API

### 1. Verify API is Working

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

### 2. Using Postman

#### A. Register New User

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

#### B. Login

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

#### C. Get Offers

**Method:** GET  
**URL:** `http://localhost:8000/api/v1/offers?tenant_domain=your-tenant-domain.com`  
**Headers:**
```
Accept: application/json
X-Tenant-Domain: your-tenant-domain.com
```

---

#### D. Purchase Shares (Requires Token)

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

#### E. Buyer Dashboard (Requires Token)

**Method:** GET  
**URL:** `http://localhost:8000/api/v1/buyer/dashboard`  
**Headers:**
```
Accept: application/json
Authorization: Bearer YOUR_TOKEN_HERE
```

---

### 3. Using cURL

#### Login
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"tenant_domain\":\"tenant.com\",\"email\":\"ahmed@test.com\",\"password\":\"password123\"}"
```

#### Get Offers
```bash
curl -X GET "http://localhost:8000/api/v1/offers?tenant_domain=tenant.com" \
  -H "Accept: application/json" \
  -H "X-Tenant-Domain: tenant.com"
```

#### Purchase (with token)
```bash
curl -X POST http://localhost:8000/api/v1/purchase \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d "{\"tenant_domain\":\"tenant.com\",\"offer_id\":1,\"shares_count\":5,\"payment_method\":\"credit_card\"}"
```

---

### 4. Using PHP/Guzzle in another application

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

### 5. Flutter/Dart (for mobile application)

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

### 6. Additional Required Steps

#### Check Tenant Domain
Make sure you have a tenant in the database:

```sql
-- In central database
SELECT * FROM tenants WHERE domain = 'your-tenant-domain.com';
```

If not found, create it:
```sql
INSERT INTO tenants (domain, database, status, created_at, updated_at) 
VALUES ('tenant.com', 'tenant_database_name', 'active', NOW(), NOW());
```

#### Create buyer role in Tenant
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

### 7. Troubleshooting

#### Error "tenant_domain is required"
- Make sure to send `tenant_domain` in body or `X-Tenant-Domain` in header

#### Error "Unauthenticated"
- Make sure to send `Authorization: Bearer {token}` in header

#### Error "Domain not found"
- Verify tenant exists in central database

#### Error "buyer role not found"
- Create buyer role in tenant database

---

## Available Endpoints

✅ **Public (without authentication):**
- `GET /api/health` - Health check
- `POST /api/v1/auth/register` - Register
- `POST /api/v1/auth/login` - Login
- `GET /api/v1/offers` - Offers list
- `GET /api/v1/offers/{id}` - Offer details
- `GET /api/v1/offers/meta/cities` - Cities
- `GET /api/v1/offers/meta/statistics` - Statistics

🔒 **Protected (requires authentication):**
- `POST /api/v1/auth/logout` - Logout
- `GET /api/v1/auth/profile` - Profile
- `PUT /api/v1/auth/profile` - Update profile
- `POST /api/v1/purchase` - Purchase
- `POST /api/v1/purchase/confirm-payment` - Confirm payment
- `POST /api/v1/purchase/{id}/cancel` - Cancel
- `GET /api/v1/buyer/dashboard` - Dashboard
- `GET /api/v1/buyer/operations` - Operations
- `GET /api/v1/buyer/operations/{id}` - Operation details
- `GET /api/v1/buyer/my-shares` - My shares

---

For complete documentation, see `API_DOCUMENTATION.md`
