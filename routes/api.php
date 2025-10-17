<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// CHÚ THÍCH: Đã gộp và giữ lại Controller của cả hai bên
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TourController;
use App\Http\Controllers\Api\TourImageController; // thêm: controller ảnh
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\Admin\ReviewController as AdminReviewController;
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

// CHÚ THÍCH: Đã gộp và giữ lại route của cả hai bên

// --- API Public cho chức năng ĐÁNH GIÁ ---
Route::get('/tours/{tour}/reviews', [ReviewController::class, 'index']);

// --- API Public cho chức năng MÃ GIẢM GIÁ ---
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

    // --- API Protected cho người dùng (Đánh giá) ---
    Route::post('/tours/{tour}/reviews', [ReviewController::class, 'store']);
    
    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/users', [AuthController::class, 'getAllUsers']);
        Route::delete('/users/{id}', [AuthController::class, 'deleteUser']);
        
        // --- API Protected cho Admin (Mã giảm giá) ---
        Route::prefix('admin/promotions')->group(function () {
            Route::get('/', [PromotionController::class, 'adminIndex']); // GET /api/admin/promotions
            Route::post('/', [PromotionController::class, 'store']); // POST /api/admin/promotions
            Route::put('/{promotion}', [PromotionController::class, 'update']); // PUT /api/admin/promotions/1
            Route::delete('/{promotion}', [PromotionController::class, 'destroy']); // DELETE /api/admin/promotions/1
        });

        // --- API Protected cho Admin (Tour Images) ---
        Route::prefix('tours/{tour}')->group(function () {
            Route::post('images', [TourImageController::class, 'store']);       // POST /api/tours/{tour}/images
            Route::put('images/replace', [TourImageController::class, 'replaceAll']);   // PUT  /api/tours/{tour}/images/replace
        });
        Route::patch('tour-images/{image}', [TourImageController::class, 'update']);   // PATCH /api/tour-images/{image}
        Route::delete('tour-images/{image}', [TourImageController::class, 'destroy']); // DELETE /api/tour-images/{image}

        // --- API Protected cho Admin (Đánh giá) ---
        Route::apiResource('reviews', AdminReviewController::class)->only(['index', 'update', 'destroy']);
    });
});

// Test route
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');