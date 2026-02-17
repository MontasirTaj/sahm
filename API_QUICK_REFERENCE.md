# 🎯 Quick Reference: Buyer vs Tenant Admin

## Two Types of Users

### 🛒 **Buyers (المشترون)**
- Global users who can purchase from any offer
- Stored in **central database**
- **Don't** belong to any tenant

### 👨‍💼 **Tenant Admins (مديرو Tenant)**
- Users who manage offers within a specific tenant
- Stored in **tenant-specific database**
- **Must** specify their tenant domain

---

## API Endpoints

| Action | Buyer | Tenant Admin |
|--------|-------|--------------|
| **Register** | `POST /api/v1/auth/register` | `POST /api/v1/auth/tenant-register` |
| **Login** | `POST /api/v1/auth/login` | `POST /api/v1/auth/tenant-login` |
| **Logout** | `POST /api/v1/auth/logout` | `POST /api/v1/auth/logout` |
| **Profile** | `GET /api/v1/auth/profile` | `GET /api/v1/auth/profile` |
| **Update Profile** | `PUT /api/v1/auth/profile` | `PUT /api/v1/auth/profile` |

---

## Registration Examples

### Buyer Registration
```json
POST /api/v1/auth/register
{
    "name": "John Doe",
    "email": "buyer@test.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```
✅ **No tenant_domain required**

### Tenant Admin Registration
```json
POST /api/v1/auth/tenant-register
{
    "tenant_domain": "sahm_4",
    "name": "Admin User",
    "email": "admin@test.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```
✅ **tenant_domain is required**

---

## Login Examples

### Buyer Login
```json
POST /api/v1/auth/login
{
    "email": "buyer@test.com",
    "password": "password123"
}
```
✅ **No tenant_domain required**

### Tenant Admin Login
```json
POST /api/v1/auth/tenant-login
{
    "tenant_domain": "sahm_4",
    "email": "admin@test.com",
    "password": "password123"
}
```
✅ **tenant_domain is required**

---

## What Can Each User Do?

### 🛒 Buyers Can:
- ✅ View all offers
- ✅ Purchase shares
- ✅ View their dashboard
- ✅ View their operations
- ✅ View their owned shares
- ❌ Cannot add/edit/delete offers

### 👨‍💼 Tenant Admins Can:
- ✅ Add new offers for their tenant
- ✅ Update their tenant's offers
- ✅ Delete their tenant's offers
- ✅ **Purchase shares** (NEW! 🎉)
- ✅ **View buyer dashboard for their purchases** (NEW! 🎉)
- ✅ **View their operations and owned shares** (NEW! 🎉)

---

## Testing in Postman

### For Buyers:
1. Register: `POST /auth/register` (no tenant_domain)
2. Login: `POST /auth/login` (no tenant_domain)
3. Save the token
4. Use token for purchases and dashboard

### For Tenant Admins:
1. Register: `POST /auth/tenant-register` (with tenant_domain)
2. Login: `POST /auth/tenant-login` (with tenant_domain)
3. Save the token
4. Use token for managing offers **AND purchases** ✨

---

## Complete Documentation

📚 **Detailed Guides:**
- [API_AUTH_GUIDE.md](API_AUTH_GUIDE.md) - Complete authentication guide
- [API_BUYERS_GUIDE.md](API_BUYERS_GUIDE.md) - Buyer operations guide
- [API_TESTING_OFFERS.md](API_TESTING_OFFERS.md) - Tenant admin operations guide
- [API_PURCHASE_GUIDE.md](API_PURCHASE_GUIDE.md) - Purchase process guide
- [API_TENANT_PURCHASE_GUIDE.md](API_TENANT_PURCHASE_GUIDE.md) - **New!** Tenant admin purchase guide 🎉

---

**Remember:**
- 🛒 Buyers = No tenant_domain (can only purchase)
- 👨‍💼 Admins = With tenant_domain (can manage offers AND purchase) ✨

**Choose the right endpoint for your user type!** 🚀
