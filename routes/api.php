<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TourController;
use App\Http\Controllers\Api\TourImageController; // thêm: controller ảnh
use App\Http\Controllers\Api\PromotionController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public Tour API routes (no authentication required)
Route::prefix('tours')->group(function () {
    Route::get('/', [TourController::class, 'index']); // GET /api/tours
    Route::get('/featured', [TourController::class, 'getFeatured']); // GET /api/tours/featured
    Route::get('/location/{location}', [TourController::class, 'getByLocation']); // GET /api/tours/location/hanoi
    Route::get('/{id}', [TourController::class, 'show']); // GET /api/tours/1
});

// Public Promotion API routes (no authentication required)
Route::prefix('promotions')->group(function () {
    Route::get('/', [PromotionController::class, 'index']); // GET /api/promotions
    Route::get('/{code}', [PromotionController::class, 'show']); // GET /api/promotions/WELCOME10
    Route::post('/validate', [PromotionController::class, 'validate']); // POST /api/promotions/validate
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    
    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/users', [AuthController::class, 'getAllUsers']);
        Route::delete('/users/{id}', [AuthController::class, 'deleteUser']);
        
        // Admin Promotion routes
        Route::prefix('admin/promotions')->group(function () {
            Route::get('/', [PromotionController::class, 'adminIndex']); // GET /api/admin/promotions
            Route::post('/', [PromotionController::class, 'store']); // POST /api/admin/promotions
            Route::put('/{promotion}', [PromotionController::class, 'update']); // PUT /api/admin/promotions/1
            Route::delete('/{promotion}', [PromotionController::class, 'destroy']); // DELETE /api/admin/promotions/1
        });

        // ============================================
        // NEW: Tour Images API (chỉ admin mới được dùng)
        // ============================================
        Route::prefix('tours/{tour}')->group(function () {
            // Upload thêm 1..n ảnh (append)
            Route::post('images', [TourImageController::class, 'store']);              // POST /api/tours/{tour}/images
            // Thay toàn bộ ảnh (xóa cũ + up mới) trong transaction
            Route::put('images/replace', [TourImageController::class, 'replaceAll']);  // PUT  /api/tours/{tour}/images/replace
        });

        // Cập nhật 1 ảnh: set cover / sort_order
        Route::patch('tour-images/{image}', [TourImageController::class, 'update']);   // PATCH /api/tour-images/{image}
        // Xóa 1 ảnh
        Route::delete('tour-images/{image}', [TourImageController::class, 'destroy']); // DELETE /api/tour-images/{image}
    });
    });

// Test route
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
