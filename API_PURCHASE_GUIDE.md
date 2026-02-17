# Purchase APIs Guide

## Overview

Purchase operations have been simplified to work directly with the central database without needing to specify `tenant_domain`. Buyers can purchase from any offer easily.

---

## 1. Purchase Shares (Create Purchase)

**Endpoint:** `POST /api/v1/purchase`

**Description:** إنشاء طلب شراء أسهم من عرض محدد. يتم حجز الأسهم مؤقتًا حتى إتمام عملية الدفع.

**Authentication:** Required (Bearer Token)

**Parameters:**

```json
{
    "offer_id": 1,
    "shares_count": 10,
    "payment_method": "credit_card"
}
```

**Request Fields:**
- `offer_id` (integer, required): Offer ID from central database
- `shares_count` (integer, required): Number of shares to purchase (minimum 1)
- `payment_method` (string, required): Payment method (`credit_card`, `bank_transfer`, `wallet`)

**Postman Example:**

```bash
POST http://localhost:8000/api/v1/purchase
Content-Type: application/json
Authorization: Bearer YOUR_TOKEN_HERE

{
    "offer_id": 1,
    "shares_count": 5,
    "payment_method": "credit_card"
}
```

**Success Response (201):**

```json
{
    "success": true,
    "message": "تم إنشاء طلب الشراء بنجاح. يرجى إكمال الدفع.",
    "data": {
        "operation_id": 123,
        "external_reference": "OP-65F8A4B2C1D3E",
        "shares_count": 5,
        "price_per_share": 1000.00,
        "amount_total": 5000.00,
        "currency": "SAR",
        "status": "pending",
        "payment_method": "credit_card",
        "offer": {
            "id": 1,
            "title": "مشروع سكني راقي",
            "city": "الرياض"
        }
    }
}
```

**Possible Errors:**

```json
// Offer not found (404)
{
    "success": false,
    "message": "العرض غير موجود"
}

// Not enough available shares (422)
{
    "success": false,
    "message": "عدد الأسهم المتاحة غير كافٍ",
    "available_shares": 3
}

// Offer is not active (422)
{
    "success": false,
    "message": "العرض غير نشط حالياً"
}
```

---

## 2. Confirm Payment

**Endpoint:** `POST /api/v1/purchase/confirm-payment`

**Description:** تأكيد إتمام أو فشل عملية الدفع. إذا تم الدفع بنجاح، يتم نقل الأسهم للمشتري. إذا فشل الدفع، يتم إلغاء الحجز وإرجاع الأسهم للعرض.

**Authentication:** Required (Bearer Token)

**Parameters:**

```json
{
    "operation_id": 123,
    "payment_id": "PAY_STRIPE_ABC123XYZ",
    "payment_status": "completed"
}
```

**Request Fields:**
- `operation_id` (integer, required): Purchase operation ID
- `payment_id` (string, required): Payment ID from payment gateway (Stripe, PayPal, etc)
- `payment_status` (string, required): Payment status (`completed` or `failed`)

**Postman Example (Successful Payment):**

```bash
POST http://localhost:8000/api/v1/purchase/confirm-payment
Content-Type: application/json
Authorization: Bearer YOUR_TOKEN_HERE

{
    "operation_id": 123,
    "payment_id": "PAY_123456",
    "payment_status": "completed"
}
```

**Success Response (200):**

```json
{
    "success": true,
    "message": "تم إتمام عملية الشراء بنجاح",
    "data": {
        "operation_id": 123,
        "status": "completed",
        "external_reference": "OP-65F8A4B2C1D3E"
    }
}
```

**Postman Example (Failed Payment):**

```bash
POST http://localhost:8000/api/v1/purchase/confirm-payment
Content-Type: application/json
Authorization: Bearer YOUR_TOKEN_HERE

{
    "operation_id": 123,
    "payment_id": "PAY_123456_FAILED",
    "payment_status": "failed"
}
```

**Failed Payment Response (200):**

```json
{
    "success": false,
    "message": "فشلت عملية الدفع",
    "data": {
        "operation_id": 123,
        "status": "failed",
        "external_reference": "OP-65F8A4B2C1D3E"
    }
}
```

**Possible Errors:**

```json
// Operation not found (404)
{
    "success": false,
    "message": "العملية غير موجودة"
}

// Unauthorized (403)
{
    "success": false,
    "message": "غير مصرح لك بهذه العملية"
}
```

---

## 3. Cancel Purchase Operation

**Endpoint:** `POST /api/v1/purchase/{operationId}/cancel`

**Description:** إلغاء عملية شراء في حالة `pending` وإرجاع الأسهم المحجوزة للعرض.

**Authentication:** Required (Bearer Token)

**Postman Example:**

```bash
POST http://localhost:8000/api/v1/purchase/123/cancel
Content-Type: application/json
Authorization: Bearer YOUR_TOKEN_HERE
```

**Success Response (200):**

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

**Possible Errors:**

```json
// Cannot cancel (422)
{
    "success": false,
    "message": "لا يمكن إلغاء هذه العملية. الحالة: completed"
}

// Operation not found (404)
{
    "success": false,
    "message": "العملية غير موجودة"
}

// Unauthorized (403)
{
    "success": false,
    "message": "غير مصرح لك بهذه العملية"
}
```

---

## Automatic Operations

### When creating a purchase operation:
1. ✅ التحقق من وجود العرض في قاعدة البيانات المركزية
2. ✅ التحقق من توفر الأسهم المطلوبة
3. ✅ التحقق من أن العرض نشط (`status = 'active'`)
4. ✅ حساب المبلغ الإجمالي
5. ✅ إنشاء سجل في `share_operations` بحالة `pending`
6. ✅ خصم الأسهم من `available_shares` (حجز مؤقت)

### When confirming payment (completed):
1. ✅ تحديث حالة العملية إلى `completed`
2. ✅ حفظ `payment_id`
3. ✅ إضافة الأسهم إلى `sold_shares`

### When payment fails (failed):
1. ✅ تحديث حالة العملية إلى `failed`
2. ✅ إرجاع الأسهم إلى `available_shares`

### When canceling operation:
1. ✅ التحقق من أن الحالة `pending`
2. ✅ تحديث الحالة إلى `cancelled`
3. ✅ إرجاع الأسهم إلى `available_shares`

---

## حالات العملية (Operation Status)

| الحالة | الوصف |
|--------|-------|
| `pending` | في انتظار الدفع |
| `completed` | تمت بنجاح |
| `failed` | فشلت |
| `cancelled` | ملغاة |

---

## طرق الدفع المدعومة (Payment Methods)

| القيمة | الوصف |
|--------|-------|
| `credit_card` | بطاقة ائتمان |
| `bank_transfer` | تحويل بنكي |
| `wallet` | محفظة إلكترونية |

---

## Complete Testing Scenario

### 1. Authentication
```bash
POST /api/v1/auth/login
{
    "email": "buyer@example.com",
    "password": "password123"
}
```

### 2. View Offers List
```bash
GET /api/v1/offers
```

### 3. View Specific Offer Details
```bash
GET /api/v1/offers/1
```

### 4. Purchase Shares
```bash
POST /api/v1/purchase
Authorization: Bearer YOUR_TOKEN
{
    "offer_id": 1,
    "shares_count": 5,
    "payment_method": "credit_card"
}
```

### 5. Payment Process (External - Stripe/PayPal/etc)
*Here user is redirected to payment gateway to complete the transaction*

### 6. Confirm Payment
```bash
POST /api/v1/purchase/confirm-payment
Authorization: Bearer YOUR_TOKEN
{
    "operation_id": 123,
    "payment_id": "PAY_FROM_GATEWAY",
    "payment_status": "completed"
}
```

### 7. Verify Operation
```bash
GET /api/v1/buyer/operations/123
Authorization: Bearer YOUR_TOKEN
```

### 8. View All Owned Shares
```bash
GET /api/v1/buyer/my-shares
Authorization: Bearer YOUR_TOKEN
```

---

## Important Notes

### ✅ New Simplifications:
- **No need for tenant_domain**: This requirement has been removed from all purchase APIs
- **Single database**: All operations occur in the central database only
- **Single identifier**: Only `offer_id` from central database is needed
- **Automatic tenant_id**: Obtained from the offer itself automatically

### 🔒 Security:
- Buyer can only cancel their own operations
- Cannot cancel a completed or failed operation
- All operations are protected with Bearer Token

### 📊 Data Tracking:
- Each operation has a unique `external_reference`
- `metadata` is saved for each operation
- Operation is linked to buyer (`buyer_id`), offer (`offer_id`), and tenant (`tenant_id`)

---

## Difference Between Old and New Version

### ❌ Old (Complex):
```json
{
    "tenant_domain": "company1",
    "offer_id": 5,  // from tenant database
    "shares_count": 10,
    "payment_method": "credit_card"
}
```

### ✅ New (Simplified):
```json
{
    "offer_id": 1,  // from central database directly
    "shares_count": 10,
    "payment_method": "credit_card"
}
```

---

## Integration مع Flutter

```dart
// Purchase shares
Future<Map<String, dynamic>> purchaseShares({
  required int offerId,
  required int sharesCount,
  required String paymentMethod,
}) async {
  final response = await http.post(
    Uri.parse('$baseUrl/api/v1/purchase'),
    headers: {
      'Authorization': 'Bearer $token',
      'Content-Type': 'application/json',
    },
    body: jsonEncode({
      'offer_id': offerId,
      'shares_count': sharesCount,
      'payment_method': paymentMethod,
    }),
  );
  
  return jsonDecode(response.body);
}

// Confirm payment
Future<Map<String, dynamic>> confirmPayment({
  required int operationId,
  required String paymentId,
  required String paymentStatus,
}) async {
  final response = await http.post(
    Uri.parse('$baseUrl/api/v1/purchase/confirm-payment'),
    headers: {
      'Authorization': 'Bearer $token',
      'Content-Type': 'application/json',
    },
    body: jsonEncode({
      'operation_id': operationId,
      'payment_id': paymentId,
      'payment_status': paymentStatus,
    }),
  );
  
  return jsonDecode(response.body);
}

// Cancel operation
Future<Map<String, dynamic>> cancelOperation(int operationId) async {
  final response = await http.post(
    Uri.parse('$baseUrl/api/v1/purchase/$operationId/cancel'),
    headers: {
      'Authorization': 'Bearer $token',
      'Content-Type': 'application/json',
    },
  );
  
  return jsonDecode(response.body);
}
```

---

## Troubleshooting

### Problem: "Offer not found"
**Solution:** Make sure to use `offer_id` from central database (`share_offers` table)

### Problem: "Not enough available shares"
**Solution:** Query `available_shares` from offer API before purchase:
```bash
GET /api/v1/offers/{id}
```

### Problem: "Offer is not currently active"
**Solution:** Check offer `status`. Must be `active`

### Problem: "Cannot cancel this operation"
**Solution:** Can only cancel operations with `pending` status

---

## Conclusion

The purchase system has been greatly simplified to work directly with the central database, making it easier to use in mobile applications and reducing programming complexity.
