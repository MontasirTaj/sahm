# 🔐 Authentication APIs - Complete Guide

## Overview

The system supports **TWO types of users**:

1. **Buyers (المشترين)** - Stored in central database
2. **Tenant Admins (مديري Tenant)** - Stored in tenant-specific databases

---

## 📋 Table of Contents

- [Buyer Authentication](#buyer-authentication)
  - [Register Buyer](#1-register-buyer)
  - [Login Buyer](#2-login-buyer)
- [Tenant Admin Authentication](#tenant-admin-authentication)
  - [Register Tenant Admin](#3-register-tenant-admin)
  - [Login Tenant Admin](#4-login-tenant-admin)
- [Common Endpoints](#common-endpoints)
  - [Logout](#5-logout)
  - [Get Profile](#6-get-profile)
  - [Update Profile](#7-update-profile)

---

## 🛒 Buyer Authentication

Buyers are **global users** who can purchase from any offer.  
They are stored in the **central database** and **don't belong to any tenant**.

### 1. Register Buyer

**Endpoint:** `POST /api/v1/auth/register`

**Description:** Register a new buyer in the central database (no tenant required)

**Authentication:** None (Public)

**Request:**
```json
{
    "name": "John Doe",
    "email": "buyer@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Request Fields:**
- `name` (string, required): Full name
- `email` (string, required): Email address (must be unique in central database)
- `password` (string, required, min:8): Password
- `password_confirmation` (string, required): Password confirmation

**Success Response (201):**
```json
{
    "success": true,
    "message": "تم التسجيل بنجاح",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "buyer@example.com",
            "avatar": null
        },
        "token": "1|abcdefghijk..."
    }
}
```

**Possible Errors:**
```json
// Email already exists (422)
{
    "success": false,
    "message": "خطأ في البيانات المدخلة",
    "errors": {
        "email": ["البريد الإلكتروني مستخدم مسبقاً"]
    }
}

// Validation error (422)
{
    "success": false,
    "message": "خطأ في البيانات المدخلة",
    "errors": {
        "password": ["يجب أن تكون كلمة المرور 8 أحرف على الأقل"]
    }
}
```

**Postman Example:**
```
Method: POST
URL: http://localhost:8000/api/v1/auth/register
Headers:
  Content-Type: application/json
Body (JSON):
{
    "name": "أحمد محمد",
    "email": "buyer@test.com",
    "password": "12345678",
    "password_confirmation": "12345678"
}
```

---

### 2. Login Buyer

**Endpoint:** `POST /api/v1/auth/login`

**Description:** Login for buyers from central database (no tenant required)

**Authentication:** None (Public)

**Request:**
```json
{
    "email": "buyer@example.com",
    "password": "password123"
}
```

**Request Fields:**
- `email` (string, required): Email address
- `password` (string, required): Password

**Success Response (200):**
```json
{
    "success": true,
    "message": "تم تسجيل الدخول بنجاح",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "buyer@example.com",
            "avatar": null
        },
        "token": "2|xyzabcdef..."
    }
}
```

**Possible Errors:**
```json
// Invalid credentials (401)
{
    "success": false,
    "message": "بيانات الدخول غير صحيحة"
}

// Validation error (422)
{
    "success": false,
    "message": "خطأ في البيانات المدخلة",
    "errors": {
        "email": ["البريد الإلكتروني غير صحيح"]
    }
}
```

**Postman Example:**
```
Method: POST
URL: http://localhost:8000/api/v1/auth/login
Headers:
  Content-Type: application/json
Body (JSON):
{
    "email": "buyer@test.com",
    "password": "12345678"
}
```

**Important Note:**
- ✅ No `tenant_domain` required for buyers
- ✅ Token can be used for all buyer operations (purchase, dashboard, etc.)

---

## 👨‍💼 Tenant Admin Authentication

Tenant Admins are users who **manage offers within a specific tenant**.  
They are stored in **tenant-specific databases** and **must specify their tenant domain**.

### 3. Register Tenant Admin

**Endpoint:** `POST /api/v1/auth/tenant-register`

**Description:** Register a new tenant admin in tenant database

**Authentication:** None (Public)

**Request:**
```json
{
    "tenant_domain": "sahm_4",
    "name": "Admin User",
    "email": "admin@test.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Request Fields:**
- `tenant_domain` (string, required): Tenant domain name
- `name` (string, required): Full name
- `email` (string, required): Email address (must be unique in tenant database)
- `password` (string, required, min:8): Password
- `password_confirmation` (string, required): Password confirmation

**Success Response (201):**
```json
{
    "success": true,
    "message": "تم تسجيل المستخدم بنجاح",
    "data": {
        "user": {
            "id": 1,
            "name": "Admin User",
            "email": "admin@test.com",
            "type": "admin",
            "tenant_domain": "sahm_4"
        },
        "token": "3|tenanttoken...",
        "tenant": {
            "id": 4,
            "domain": "sahm_4",
            "database": "sahm_4_db"
        }
    }
}
```

**Possible Errors:**
```json
// Tenant not found (404)
{
    "success": false,
    "message": "النطاق غير موجود"
}

// Email already exists in tenant (422)
{
    "success": false,
    "message": "البريد الإلكتروني مستخدم مسبقاً"
}

// Validation error (422)
{
    "success": false,
    "message": "خطأ في البيانات المدخلة",
    "errors": {
        "tenant_domain": ["حقل النطاق مطلوب"]
    }
}
```

**Postman Example:**
```
Method: POST
URL: http://localhost:8000/api/v1/auth/tenant-register
Headers:
  Content-Type: application/json
Body (JSON):
{
    "tenant_domain": "sahm_4",
    "name": "أحمد محمد",
    "email": "admin@test.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

---

### 4. Login Tenant Admin

**Endpoint:** `POST /api/v1/auth/tenant-login`

**Description:** Login for tenant admins from tenant database

**Authentication:** None (Public)

**Request:**
```json
{
    "tenant_domain": "sahm_4",
    "email": "admin@test.com",
    "password": "password123"
}
```

**Request Fields:**
- `tenant_domain` (string, required): Tenant domain name
- `email` (string, required): Email address
- `password` (string, required): Password

**Success Response (200):**
```json
{
    "success": true,
    "message": "تم تسجيل الدخول بنجاح",
    "data": {
        "user": {
            "id": 1,
            "name": "Admin User",
            "email": "admin@test.com",
            "type": "admin",
            "tenant_domain": "sahm_4"
        },
        "token": "4|admintoken...",
        "tenant": {
            "id": 4,
            "domain": "sahm_4",
            "database": "sahm_4_db"
        }
    }
}
```

**Possible Errors:**
```json
// Tenant not found (404)
{
    "success": false,
    "message": "النطاق غير موجود"
}

// Invalid credentials (401)
{
    "success": false,
    "message": "بيانات الدخول غير صحيحة"
}

// Validation error (422)
{
    "success": false,
    "message": "خطأ في البيانات المدخلة",
    "errors": {
        "tenant_domain": ["حقل النطاق مطلوب"]
    }
}
```

**Postman Example:**
```
Method: POST
URL: http://localhost:8000/api/v1/auth/tenant-login
Headers:
  Content-Type: application/json
Body (JSON):
{
    "tenant_domain": "sahm_4",
    "email": "admin@test.com",
    "password": "password123"
}
```

**Important Note:**
- ✅ `tenant_domain` is **required** for tenant admins
- ✅ Token can be used for tenant operations (add/edit/delete offers)
- ✅ Token has abilities: `['admin', 'tenant:4']`

---

## 🔓 Common Endpoints

These endpoints work for **both buyers and tenant admins**.

### 5. Logout

**Endpoint:** `POST /api/v1/auth/logout`

**Description:** Logout and delete current token

**Authentication:** Required (Bearer Token)

**Request:** No body required

**Success Response (200):**
```json
{
    "success": true,
    "message": "تم تسجيل الخروج بنجاح"
}
```

**Postman Example:**
```
Method: POST
URL: http://localhost:8000/api/v1/auth/logout
Headers:
  Authorization: Bearer YOUR_TOKEN
  Accept: application/json
```

---

### 6. Get Profile

**Endpoint:** `GET /api/v1/auth/profile`

**Description:** Get current user profile

**Authentication:** Required (Bearer Token)

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "user@example.com",
        "avatar": null,
        "created_at": "2026-02-15"
    }
}
```

**Postman Example:**
```
Method: GET
URL: http://localhost:8000/api/v1/auth/profile
Headers:
  Authorization: Bearer YOUR_TOKEN
  Accept: application/json
```

---

### 7. Update Profile

**Endpoint:** `PUT /api/v1/auth/profile`

**Description:** Update user profile (name, avatar, password)

**Authentication:** Required (Bearer Token)

**Request:**
```json
{
    "name": "New Name",
    "current_password": "oldpassword123",
    "new_password": "newpassword456",
    "new_password_confirmation": "newpassword456"
}
```

**Request Fields (all optional):**
- `name` (string): New name
- `avatar` (file): Profile image (max 2MB)
- `current_password` (string): Current password (required if changing password)
- `new_password` (string, min:8): New password
- `new_password_confirmation` (string): New password confirmation

**Success Response (200):**
```json
{
    "success": true,
    "message": "تم تحديث البيانات بنجاح",
    "data": {
        "id": 1,
        "name": "New Name",
        "email": "user@example.com",
        "avatar": "/storage/avatars/xyz.jpg"
    }
}
```

**Possible Errors:**
```json
// Wrong current password (422)
{
    "success": false,
    "message": "كلمة المرور الحالية غير صحيحة"
}
```

---

## 📊 Comparison Table

| Feature | Buyer | Tenant Admin |
|---------|-------|--------------|
| **Register Endpoint** | `/auth/register` | `/auth/tenant-register` |
| **Login Endpoint** | `/auth/login` | `/auth/tenant-login` |
| **Requires tenant_domain** | ❌ No | ✅ Yes |
| **Database** | Central | Tenant-specific |
| **Table** | `central.users` | `{tenant}.users` |
| **Can Purchase** | ✅ Yes | ❌ No |
| **Can Manage Offers** | ❌ No | ✅ Yes |
| **Token Abilities** | `['buyer']` | `['admin', 'tenant:X']` |

---

## 🧪 Testing Workflow

### For Buyers:
1. **Register:** POST `/auth/register` (no tenant_domain)
2. **Login:** POST `/auth/login` (no tenant_domain)
3. **Get Token:** Save token from response
4. **Use Token:** For purchases, dashboard, etc.

### For Tenant Admins:
1. **Register:** POST `/auth/tenant-register` (with tenant_domain)
2. **Login:** POST `/auth/tenant-login` (with tenant_domain)
3. **Get Token:** Save token from response
4. **Use Token:** For managing offers (create/update/delete)

---

## ⚠️ Important Notes

### Security:
- 🔒 Tokens are stored in `personal_access_tokens` table in **central database**
- 🔒 All passwords are hashed with bcrypt
- 🔒 Tokens have different abilities based on user type

### Database Structure:
```
Central Database (central)
├── users (buyers)
├── tenants (tenant list)
├── personal_access_tokens (all tokens)
└── share_offers (all offers - read-only for buyers)

Tenant Database (e.g., sahm_4)
├── users (tenant admins)
└── (tenant-specific data)
```

### Best Practices:
- ✅ Always save the token from registration/login response
- ✅ Include `Authorization: Bearer {token}` header for protected endpoints
- ✅ Buyers should NOT provide tenant_domain
- ✅ Tenant Admins MUST provide tenant_domain
- ✅ Use HTTPS in production to protect tokens

---

## 🆘 Troubleshooting

### Problem: "بيانات الدخول غير صحيحة"
**Solution:**
- Check if you're using the correct endpoint (buyer vs tenant admin)
- Buyers: Use `/auth/login` WITHOUT tenant_domain
- Admins: Use `/auth/tenant-login` WITH tenant_domain
- Verify email and password are correct

### Problem: "النطاق غير موجود"
**Solution:**
- Verify tenant_domain exists in central.tenants table
- Check spelling of tenant_domain
- Confirm tenant database is accessible

### Problem: "البريد الإلكتروني مستخدم مسبقاً"
**Solution:**
- For buyers: Email must be unique in central.users
- For admins: Email must be unique in {tenant}.users
- Same email can exist as both buyer AND admin (different databases)

---

## 📚 Related Documentation

- [API_BUYERS_GUIDE.md](API_BUYERS_GUIDE.md) - Complete guide for buyers
- [API_PURCHASE_GUIDE.md](API_PURCHASE_GUIDE.md) - Purchase operations guide
- [API_TESTING_OFFERS.md](API_TESTING_OFFERS.md) - Offer management guide (for admins)

---

**All authentication endpoints are ready to use!** 🚀
