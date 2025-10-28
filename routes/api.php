<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TourController;
use App\Http\Controllers\Api\TourImageController; // thêm: controller ảnh
use App\Http\Controllers\Api\PromotionController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\InvoiceTestController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\ReviewController;

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

// Public Banner API routes (no authentication required)
Route::prefix('banners')->group(function () {
    Route::get('/', [BannerController::class, 'index']); // GET /api/banners
    Route::get('/active', [BannerController::class, 'getActive']); // GET /api/banners/active
    Route::get('/stats', [BannerController::class, 'getStats']); // GET /api/banners/stats
    Route::get('/{banner}', [BannerController::class, 'show']); // GET /api/banners/1
    Route::post('/{banner}/view', [BannerController::class, 'trackView']); // POST /api/banners/1/view
    Route::post('/{banner}/click', [BannerController::class, 'trackClick']); // POST /api/banners/1/click
});

// Public Review API routes
Route::prefix('tours/{tour}/reviews')->group(function () {
    Route::get('/', [ReviewController::class, 'index']); // GET /api/tours/1/reviews
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    
    // Check-in/Check-out routes
    Route::prefix('check-in-out')->group(function () {
        Route::post('/', [\App\Http\Controllers\CheckInOutController::class, 'mobileCheckInOut']);
        Route::get('/history', [\App\Http\Controllers\CheckInOutController::class, 'mobileHistory']);
    });

    // Review routes (authenticated users can create reviews)
    Route::post('/tours/{tour}/reviews', [ReviewController::class, 'store']); // POST /api/tours/1/reviews

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

        // Admin Banner routes
        Route::prefix('admin/banners')->group(function () {
            Route::post('/', [BannerController::class, 'store']); // POST /api/admin/banners
            Route::put('/{banner}', [BannerController::class, 'update']); // PUT /api/admin/banners/1
            Route::delete('/{banner}', [BannerController::class, 'destroy']); // DELETE /api/admin/banners/1
            Route::post('/bulk-status', [BannerController::class, 'bulkUpdateStatus']); // POST /api/admin/banners/bulk-status
            Route::post('/reorder', [BannerController::class, 'reorder']); // POST /api/admin/banners/reorder
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

    // ============================================
    // Invoice API routes (authenticated users)
    // ============================================
    Route::prefix('invoices')->group(function () {
        Route::get('/', [InvoiceController::class, 'index']); // GET /api/invoices
        Route::get('/booking/{bookingId}', [InvoiceController::class, 'show']); // GET /api/invoices/booking/1
        Route::get('/booking/{bookingId}/pdf', [InvoiceController::class, 'generatePdf']); // GET /api/invoices/booking/1/pdf
        Route::get('/booking/{bookingId}/download', [InvoiceController::class, 'downloadPdf']); // GET /api/invoices/booking/1/download
    });

    // Admin/Staff only invoice routes
    Route::middleware(['admin', 'staff'])->group(function () {
        Route::post('/invoices', [InvoiceController::class, 'store']); // POST /api/invoices
        Route::put('/invoices/{invoiceId}', [InvoiceController::class, 'update']); // PUT /api/invoices/1
    });
});

// Test routes
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/invoice-api-docs', [InvoiceTestController::class, 'testEndpoints']);
