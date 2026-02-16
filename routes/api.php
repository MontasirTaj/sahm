<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\OfferManagementController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\BuyerController;

/*
|--------------------------------------------------------------------------
| API Routes - Mobile Application
|--------------------------------------------------------------------------
|
| هذه Routes خاصة بتطبيق الموبايل ولن تؤثر على الموقع الإلكتروني
|
*/

// Public routes (بدون مصادقة)
Route::prefix('v1')->group(function () {
    
    // المصادقة (Authentication)
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    // العروض (Public Access)
    Route::prefix('offers')->group(function () {
        Route::get('/', [OfferController::class, 'index']);
        Route::get('/{id}', [OfferController::class, 'show']);
        Route::get('/meta/cities', [OfferController::class, 'cities']);
        Route::get('/meta/statistics', [OfferController::class, 'statistics']);
    });
});

// Protected routes (تتطلب مصادقة)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    
    // المصادقة (Authenticated User)
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
    });

    // الشراء (Purchase Operations)
    Route::prefix('purchase')->group(function () {
        Route::post('/', [PurchaseController::class, 'purchase']);
        Route::post('/confirm-payment', [PurchaseController::class, 'confirmPayment']);
        Route::post('/{operationId}/cancel', [PurchaseController::class, 'cancel']);
    });

    // بيانات المشتري (Buyer Dashboard & Data)
    Route::prefix('buyer')->group(function () {
        Route::get('/dashboard', [BuyerController::class, 'dashboard']);
        Route::get('/operations', [BuyerController::class, 'operations']);
        Route::get('/operations/{operationId}', [BuyerController::class, 'operationDetails']);
        Route::get('/my-shares', [BuyerController::class, 'myShares']);
    });

    // إدارة العروض (Offer Management - للمسؤولين)
    Route::prefix('offers')->group(function () {
        Route::post('/', [OfferManagementController::class, 'store']);
        Route::put('/{id}', [OfferManagementController::class, 'update']);
        Route::delete('/{id}', [OfferManagementController::class, 'destroy']);
    });
});

// Health Check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API is working',
        'timestamp' => now()->format('Y-m-d H:i:s'),
        'version' => 'v1',
    ]);
});

// 404 for undefined API routes
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'الـ API endpoint غير موجود',
        'available_endpoints' => [
            'auth' => '/api/v1/auth/*',
            'offers' => '/api/v1/offers/*',
            'purchase' => '/api/v1/purchase/*',
            'buyer' => '/api/v1/buyer/*',
        ]
    ], 404);
});
