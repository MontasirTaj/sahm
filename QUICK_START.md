# 🚀 Quick Testing Guide - Buyer App

## 📋 Important Information
✅ **System Simplified**: Buyers no longer need `tenant_domain`!  
✅ **All APIs Ready**: 18 endpoints registered successfully  
✅ **Central Database**: All buyer operations in one place

---

## 🎯 Testing Steps in Postman

### Step 1: Register New Buyer

```http
POST http://localhost:8000/api/v1/auth/register
Content-Type: application/json
```

**Body:**
```json
{
    "name": "محمد أحمد",
    "email": "buyer1@test.com",
    "password": "12345678",
    "password_confirmation": "12345678"
}
```

**✅ Expected Result:**
```json
{
    "success": true,
    "message": "تم التسجيل بنجاح",
    "data": {
        "user": {
            "id": 1,
            "name": "محمد أحمد",
            "email": "buyer1@test.com",
            "avatar": null
        },
        "token": "1|xxxxxxxxxxxx"  ← Save this token
    }
}
```

---

### Step 2: Login

```http
POST http://localhost:8000/api/v1/auth/login
Content-Type: application/json
```

**Body:**
```json
{
    "email": "buyer1@test.com",
    "password": "12345678"
}
```

**✅ Expected Result:**
```json
{
    "success": true,
    "message": "تم تسجيل الدخول بنجاح",
    "data": {
        "user": {...},
        "token": "2|yyyyyyyyyyyy"  ← Use this token for next requests
    }
}
```

---

### Step 3: View All Offers (Without Token)

```http
GET http://localhost:8000/api/v1/offers?status=active
```

**✅ Expected Result:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "مشروع سكني راقي",
            "city": "الرياض",
            "available_shares": 750,
            "price_per_share": 5000.00,
            "currency": "SAR",
            "status": "active"
        }
    ]
}
```

**⚠️ If list is empty:**
- Check if there's data in `share_offers` table in central database
- You can add a test offer via tenant interface

---

### Step 4: View Specific Offer Details

```http
GET http://localhost:8000/api/v1/offers/1
```

**✅ Expected Result:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "مشروع سكني راقي",
        "description": "مشروع سكني فاخر...",
        "city": "الرياض",
        "total_shares": 1000,
        "available_shares": 750,
        "sold_shares": 250,
        "price_per_share": 5000.00,
        "sold_percentage": 25
    }
}
```

---

### Step 5: Purchase Shares (With Token)

```http
POST http://localhost:8000/api/v1/purchase
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json
```

**Body:**
```json
{
    "offer_id": 1,
    "shares_count": 5,
    "payment_method": "credit_card"
}
```

**✅ Expected Result:**
```json
{
    "success": true,
    "message": "تم إنشاء طلب الشراء بنجاح. يرجى إكمال الدفع.",
    "data": {
        "operation_id": 1,  ← Save this number
        "external_reference": "OP-65F8A4B2",
        "shares_count": 5,
        "price_per_share": 5000.00,
        "amount_total": 25000.00,
        "status": "pending"
    }
}
```

---

### Step 6: Confirm Payment

```http
POST http://localhost:8000/api/v1/purchase/confirm-payment
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json
```

**Body:**
```json
{
    "operation_id": 1,
    "payment_id": "TEST_PAY_123456",
    "payment_status": "completed"
}
```

**✅ Expected Result:**
```json
{
    "success": true,
    "message": "تم إتمام عملية الشراء بنجاح",
    "data": {
        "operation_id": 1,
        "status": "completed",
        "external_reference": "OP-65F8A4B2"
    }
}
```

---

### Step 7: View My Owned Shares

```http
GET http://localhost:8000/api/v1/buyer/my-shares
Authorization: Bearer YOUR_TOKEN_HERE
```

**✅ Expected Result:**
```json
{
    "success": true,
    "data": [
        {
            "offer_id": 1,
            "offer_title": "مشروع سكني راقي",
            "offer_city": "الرياض",
            "total_shares": 5,
            "total_invested": 25000.00,
            "average_price": 5000.00,
            "current_price": 5000.00,
            "operations_count": 1
        }
    ],
    "summary": {
        "total_offers": 1,
        "total_shares": 5,
        "total_invested": 25000.00
    }
}
```

---

### Step 8: Dashboard

```http
GET http://localhost:8000/api/v1/buyer/dashboard
Authorization: Bearer YOUR_TOKEN_HERE
```

**✅ Expected Result:**
```json
{
    "success": true,
    "data": {
        "stats": {
            "total_operations": 1,
            "completed_operations": 1,
            "pending_operations": 0,
            "total_shares_owned": 5,
            "total_spent": 25000.00
        },
        "recent_operations": [...]
    }
}
```

---

### Step 9: View Profile

```http
GET http://localhost:8000/api/v1/auth/profile
Authorization: Bearer YOUR_TOKEN_HERE
```

**✅ Expected Result:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "محمد أحمد",
        "email": "buyer1@test.com",
        "avatar": null,
        "created_at": "2024-02-15"
    }
}
```

---

### Step 10: Update Profile

```http
PUT http://localhost:8000/api/v1/auth/profile
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json
```

**Body (all fields optional):**
```json
{
    "name": "محمد أحمد السعيد",
    "email": "buyer.new@test.com",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

---

## 🔍 Troubleshooting

### Problem: "Unauthenticated"
**Solution:**
- Make sure to add Header: `Authorization: Bearer YOUR_TOKEN`
- Check token validity
- Try new login

### Problem: "Offer not found"
**Solution:**
- Check if data exists in `share_offers` table
- Make sure to use correct `offer_id`
- Try GET /api/v1/offers to get offers list

### Problem: "Not enough available shares"
**Solution:**
- Check `available_shares` in offer details
- Reduce requested shares count

### Problem: Route not found
**Solution:**
```bash
php artisan route:list --path=api
```
Make sure all routes are registered (should see 18+ routes)

---

## 🗄️ Database Verification

### 1. Check Buyers
```sql
SELECT * FROM users WHERE email LIKE '%test.com';
```

### 2. Check Offers
```sql
SELECT id, title, city, available_shares, status FROM share_offers;
```

### 3. Check Operations
```sql
SELECT id, buyer_id, offer_id, shares_count, status, amount_total 
FROM share_operations 
ORDER BY created_at DESC;
```

### 4. Check Tokens
```sql
SELECT id, tokenable_id, name, created_at, last_used_at 
FROM personal_access_tokens 
ORDER BY id DESC;
```

---

## 📚 Complete Documentation Files

1. **API_BUYERS_GUIDE.md** - Comprehensive guide for buyers with all endpoints
2. **API_PURCHASE_GUIDE.md** - Complete guide for purchase operations
3. **API_UPDATES_SUMMARY.md** - Summary of all updates
4. **API_DOCUMENTATION.md** - Comprehensive documentation for all APIs
5. **API_TESTING.md** - Advanced testing examples

---

## ✅ Checklist Before Starting

- [ ] Central database exists and contains:
  - [ ] `users` table
  - [ ] `share_offers` table (with test data)
  - [ ] `share_operations` table
  - [ ] `personal_access_tokens` table

- [ ] Laravel is running properly
  - [ ] `php artisan serve` is working
  - [ ] Database connection is correct

- [ ] APIs are registered
  - [ ] `php artisan route:list --path=api` shows 18+ routes

- [ ] Postman is ready
  - [ ] Environment variables: base_url, token
  - [ ] Headers are ready

---

## 🎉 Congratulations!

If you completed all steps successfully, you are now ready to develop a Flutter app and connect it to the APIs.

**Next Steps:**
1. ✅ Integration with real payment gateway (Stripe / PayPal / HyperPay)
2. ✅ Add images for offers
3. ✅ Push notifications for buyers
4. ✅ Advanced reports and dashboards
5. ✅ Rating and review system

---

## 🆘 Support

If you encounter any problems:
1. Check log files in `storage/logs/laravel.log`
2. Review the documentation files mentioned above
3. Make sure all migrations have been run
4. Check the console in Postman for detailed errors

**All APIs are ready to use! 🚀**
