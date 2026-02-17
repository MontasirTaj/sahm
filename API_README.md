# API Setup Guide - Sahm

## Overview

Complete APIs have been created for the mobile application, completely separate from the website.

---

## Added Files

### Controllers
```
app/Http/Controllers/Api/
├── AuthController.php          (Register, login, logout, profile update)
├── OfferController.php          (Display and view offer details)
├── PurchaseController.php       (Purchase and payment confirmation)
└── BuyerController.php          (Dashboard and buyer data)
```

### Resources
```
app/Http/Resources/
├── OfferResource.php           (Format offer data)
├── OperationResource.php       (Format operation data)
└── UserResource.php            (Format user data)
```

### Middleware
```
app/Http/Middleware/
└── SetTenantFromHeader.php     (Manage tenant context)
```

### Routes
```
routes/
└── api.php                     (All API endpoints)
```

### Documentation
```
API_DOCUMENTATION.md            (Complete API documentation)
```

---

## Setup Steps

### 1. Update Models
`HasApiTokens` trait has been added to:
- `app/Models/TenantUser.php`
- `app/Models/User.php`

### 2. Create Sanctum Tables
```bash
php artisan migrate
```

This will create the `personal_access_tokens` table in the database.

### 3. Make sure Sanctum is in config/app.php
```php
'providers' => [
    // ...
    Laravel\Sanctum\SanctumServiceProvider::class,
],
```

### 4. Publish Sanctum Files (Optional)
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 5. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

---

## Testing the API

### 1. Health Check
```bash
curl https://your-domain.com/api/health
```

### 2. Register New User
```bash
curl -X POST https://your-domain.com/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "tenant_domain": "your-tenant-domain.com",
    "name": "Ahmed Test",
    "email": "ahmed@test.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### 3. Login
```bash
curl -X POST https://your-domain.com/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "tenant_domain": "your-tenant-domain.com",
    "email": "ahmed@test.com",
    "password": "password123"
  }'
```

Save the `token` from the response.

### 4. Get Offers
```bash
curl -X GET "https://your-domain.com/api/v1/offers?tenant_domain=your-tenant-domain.com" \
  -H "Accept: application/json"
```

### 5. Purchase Shares (Requires Token)
```bash
curl -X POST https://your-domain.com/api/v1/purchase \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "tenant_domain": "your-tenant-domain.com",
    "offer_id": 1,
    "shares_count": 5,
    "payment_method": "credit_card"
  }'
```

---

## Environment and Settings

### Config in .env
Make sure you have:
```env
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,your-domain.com
```

---

## API Base URL

```
Development: http://localhost:8000/api/
Production:  https://your-domain.com/api/
```

---

## Security and Protection

1. **CORS**: Make sure CORS is properly configured in `config/cors.php`
2. **Rate Limiting**: Laravel applies rate limiting by default (60 requests/minute)
3. **Validation**: All inputs go through strict validation
4. **Database Transactions**: Financial operations use transactions
5. **Token Security**: Use HTTPS in production to protect tokens

---

## Available Endpoints

### Authentication
**Buyers (no tenant required):**
- POST `/api/v1/auth/register` - Register Buyer
- POST `/api/v1/auth/login` - Login Buyer

**Tenant Admins (tenant required):**
- POST `/api/v1/auth/tenant-register` - Register Admin
- POST `/api/v1/auth/tenant-login` - Login Admin

**Common:**
- POST `/api/v1/auth/logout` - Logout ✓
- GET `/api/v1/auth/profile` - Get Profile ✓
- PUT `/api/v1/auth/profile` - Update Profile ✓

### Offers
- GET `/api/v1/offers` - List Offers
- GET `/api/v1/offers/{id}` - Offer Details
- GET `/api/v1/offers/meta/cities` - Cities List
- GET `/api/v1/offers/meta/statistics` - Statistics

### Offer Management (Tenant Admins Only)
- POST `/api/v1/offers` - Create Offer ✓
- PUT `/api/v1/offers/{id}` - Update Offer ✓
- DELETE `/api/v1/offers/{id}` - Delete Offer ✓

### Purchase (Buyers Only)
- POST `/api/v1/purchase` - Purchase ✓
- POST `/api/v1/purchase/confirm-payment` - Confirm Payment ✓
- POST `/api/v1/purchase/{id}/cancel` - Cancel ✓

### Buyer Dashboard
- GET `/api/v1/buyer/dashboard` - Dashboard ✓
- GET `/api/v1/buyer/operations` - Operations ✓
- GET `/api/v1/buyer/operations/{id}` - Operation Details ✓
- GET `/api/v1/buyer/my-shares` - My Shares ✓

✓ = Requires Authentication

---

## Complete Documentation

Refer to `API_DOCUMENTATION.md` file for:
- Complete details for each endpoint
- Request/Response examples
- Status codes
- Error handling
- cURL examples

---

## Technical Support

For more help:
1. Review the complete documentation in `API_DOCUMENTATION.md`
2. Check logs in `storage/logs/laravel.log`
3. Use `php artisan route:list` to see all routes

---

## Important Notes

1. **Does not affect the website**: All API routes are under `/api/` and completely separate
2. **Multi-Tenant**: Each request must specify `tenant_domain`
3. **Database Connections**: Automatic switching between Central and Tenant databases
4. **Testing**: Test on development environment before production
5. **Security**: Always use HTTPS in production

---

## Next Steps

1. ✅ Enable Sanctum migrations
2. ✅ Test registration and login
3. ✅ Test displaying offers
4. ✅ Test purchase process
5. ⏭️ Integrate with mobile application
6. ⏭️ Setup actual Payment Gateway
7. ⏭️ Add Notifications (Push/Email)
8. ⏭️ Add Analytics and Reporting

---

This system has been carefully created to be secure, scalable, and easy to use for mobile app developers.
