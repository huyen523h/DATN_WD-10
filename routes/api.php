<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TourController;
use App\Http\Controllers\Api\BannerController;

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

// Public Banner API routes (no authentication required)
Route::prefix('banners')->group(function () {
    Route::get('/active', [BannerController::class, 'getActive']); // GET /api/banners/active
    Route::post('/{banner}/track-view', [BannerController::class, 'trackView']); // POST /api/banners/1/track-view
    Route::post('/{banner}/track-click', [BannerController::class, 'trackClick']); // POST /api/banners/1/track-click
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
    });

    // API User Management
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'apiIndex']);
        Route::post('/', [UserController::class, 'apiStore']);
        Route::get('/{user}', [UserController::class, 'apiShow']);
        Route::put('/{user}', [UserController::class, 'apiUpdate']);
        Route::delete('/{user}', [UserController::class, 'apiDestroy']);
    });

    // API Banner Management (Admin only)
    Route::middleware('admin')->prefix('banners')->group(function () {
        Route::get('/', [BannerController::class, 'index']); // GET /api/banners
        Route::post('/', [BannerController::class, 'store']); // POST /api/banners
        Route::get('/stats', [BannerController::class, 'getStats']); // GET /api/banners/stats
        Route::post('/bulk-update-status', [BannerController::class, 'bulkUpdateStatus']); // POST /api/banners/bulk-update-status
        Route::post('/reorder', [BannerController::class, 'reorder']); // POST /api/banners/reorder
        Route::get('/{banner}', [BannerController::class, 'show']); // GET /api/banners/1
        Route::put('/{banner}', [BannerController::class, 'update']); // PUT /api/banners/1
        Route::delete('/{banner}', [BannerController::class, 'destroy']); // DELETE /api/banners/1
    });
});

// Test route
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
