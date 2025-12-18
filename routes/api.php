<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TourController;
use App\Http\Controllers\Api\TourImageController; // thêm: controller ảnh
use App\Http\Controllers\Api\PromotionController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\InvoiceTestController;
use App\Http\Controllers\Api\Admin\GuideController as AdminGuideController;
use App\Http\Controllers\Api\Admin\GuideCategoryController;
use App\Http\Controllers\Api\Admin\TourOperationController;
use App\Http\Controllers\Api\Admin\OperationStaffController;
use App\Http\Controllers\Api\Admin\OperationServiceController;
use App\Http\Controllers\TourScheduleController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public Tour API routes (no authentication required)
Route::prefix('tours')->group(function () {
    Route::get('/', [TourController::class, 'index']); // GET /api/tours
    Route::get('/featured', [TourController::class, 'getFeatured']); // GET /api/tours/featured
    Route::get('/location/{location}', [TourController::class, 'getByLocation']); // GET /api/tours/location/hanoi
    Route::get('/{id}', [TourController::class, 'show']); // GET /api/tours/1
    
    // Tour schedule routes (public - khách có thể xem lịch trình)
    Route::get('/{tourId}/schedules', [TourScheduleController::class, 'getScheduleDetails']); // GET /api/tours/1/schedules
});

// Public Guides API (for dropdown selection)
Route::get('/guides/available', [TourScheduleController::class, 'getAvailableGuides']); // GET /api/guides/available

// Departure management endpoints
Route::get('/departures/{id}', [TourScheduleController::class, 'getDeparture']);
Route::put('/departures/{id}', [TourScheduleController::class, 'updateDeparture']);
Route::put('/departures/{id}/draft', [TourScheduleController::class, 'saveDepartureAsDraft']);
Route::post('/departures/create', [TourScheduleController::class, 'createDeparture']);
Route::get('/tours/{tourId}/departures', [TourScheduleController::class, 'getTourDepartures']);
Route::get('/departures/by-date', [TourScheduleController::class, 'getDeparturesByDate']);

// Test route
Route::post('/test-schedule', function(Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'Test route works',
        'data' => $request->all()
    ]);
});

// Schedule management endpoints (public for testing)
Route::post('/schedule-create/{tourId}', [TourScheduleController::class, 'createSchedule']);
Route::put('/schedule-update/{tourId}/{scheduleId}', [TourScheduleController::class, 'updateSchedule']);
Route::delete('/schedule-delete/{tourId}/{scheduleId}', [TourScheduleController::class, 'deleteSchedule']);

// Notification endpoints
Route::get('/notifications/recent', [TourScheduleController::class, 'getRecentNotifications']);
Route::get('/notifications/new', [TourScheduleController::class, 'getNewNotifications']);
Route::post('/notifications/mark-all-read', [TourScheduleController::class, 'markAllNotificationsAsRead']);

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
    
    // Check-in/Check-out routes
    Route::prefix('check-in-out')->group(function () {
        Route::post('/', [\App\Http\Controllers\CheckInOutController::class, 'mobileCheckInOut']);
        Route::get('/history', [\App\Http\Controllers\CheckInOutController::class, 'mobileHistory']);
    });

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

        // Guides & operations
        Route::apiResource('guides', AdminGuideController::class);
        Route::apiResource('guide-categories', GuideCategoryController::class)->except(['create', 'edit']);
        Route::apiResource('tour-operations', TourOperationController::class)->except(['create', 'edit']);

        Route::post('tour-operations/{tour_operation}/staff', [OperationStaffController::class, 'store']);
        Route::put('operation-staff/{assignment}', [OperationStaffController::class, 'update']);
        Route::delete('operation-staff/{assignment}', [OperationStaffController::class, 'destroy']);

        Route::post('tour-operations/{tour_operation}/services', [OperationServiceController::class, 'store']);
        Route::put('operation-services/{operation_service}', [OperationServiceController::class, 'update']);
        Route::delete('operation-services/{operation_service}', [OperationServiceController::class, 'destroy']);

        // Tour Schedule Management (Admin only)
        Route::prefix('tours/{tourId}/schedules')->group(function () {
            Route::post('/', [TourScheduleController::class, 'createSchedule']); // POST /api/tours/1/schedules
            Route::put('/{scheduleId}', [TourScheduleController::class, 'updateSchedule']); // PUT /api/tours/1/schedules/1
            Route::delete('/{scheduleId}', [TourScheduleController::class, 'deleteSchedule']); // DELETE /api/tours/1/schedules/1
        });

        // Tour Departure Management (Admin only)
        Route::prefix('departures')->group(function () {
            Route::put('/{departureId}', [TourScheduleController::class, 'updateDeparture']); // PUT /api/departures/1
        });

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
