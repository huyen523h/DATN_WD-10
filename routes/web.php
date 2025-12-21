<?php

use App\Http\Controllers\Admin\DepartureController;
use App\Http\Controllers\Admin\TourScheduleController;
use App\Http\Controllers\Admin\GuideWebController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Api\WishlistsController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CheckInOutController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\EmployeeAuthController;

use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\GroupTourController;
use App\Http\Controllers\Admin\GroupRequestController;



Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ============================================
// DEBUG ROUTES - Test & Development
// ============================================

// Simple debug route
Route::get('/debug-simple', function () {
    try {
        $tour = App\Models\Tour::first();
        $departures = App\Models\TourDeparture::where('tour_id', $tour->id)->get();

        return response()->json([
            'success' => true,
            'tour_id' => $tour->id,
            'tour_title' => $tour->title,
            'departures_count' => $departures->count(),
            'departures' => $departures->map(function ($dep) {
                return [
                    'id' => $dep->id,
                    'date' => $dep->departure_date,
                    'seats' => $dep->seats_available . '/' . $dep->seats_total
                ];
            })
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

// Debug route to check what's happening in test-booking
Route::get('/debug-test-booking', function () {
    $tour = App\Models\Tour::first();
    $departures = App\Models\TourDeparture::where('tour_id', $tour->id)
        ->orderBy('departure_date', 'asc')
        ->get();

    return response()->json([
        'tour_id' => $tour->id,
        'tour_title' => $tour->title,
        'departures_count' => $departures->count(),
        'departures' => $departures->map(function ($dep) {
            return [
                'id' => $dep->id,
                'date' => $dep->departure_date,
                'seats' => $dep->seats_available . '/' . $dep->seats_total
            ];
        })
    ]);
});

// Test route with hardcoded data
Route::get('/test-hardcoded', function () {
    $tour = (object) [
        'id' => 1,
        'title' => 'Test Tour',
        'price' => 1000000
    ];

    $departures = collect([
        (object) ['id' => 1, 'departure_date' => '2025-10-19', 'seats_available' => 15, 'seats_total' => 20],
        (object) ['id' => 2, 'departure_date' => '2025-10-26', 'seats_available' => 18, 'seats_total' => 20],
        (object) ['id' => 3, 'departure_date' => '2025-11-02', 'seats_available' => 12, 'seats_total' => 20],
    ]);

    $promotions = collect();

    return view('bookings.create', compact('tour', 'departures', 'promotions'));
});

// Debug route to check data
Route::get('/debug-departures', function () {
    $tour = App\Models\Tour::first();
    $departures = App\Models\TourDeparture::where('tour_id', $tour->id)->get();

    $output = "Tour ID: " . $tour->id . "\n";
    $output .= "Tour Title: " . $tour->title . "\n";
    $output .= "Departures count: " . $departures->count() . "\n\n";

    foreach ($departures as $dep) {
        $output .= "Departure ID: " . $dep->id . "\n";
        $output .= "Date: " . $dep->departure_date . "\n";
        $output .= "Seats: " . $dep->seats_available . "/" . $dep->seats_total . "\n";
        $output .= "---\n";
    }

    return response($output, 200, ['Content-Type' => 'text/plain']);
});

// ============================================
// INVOICE DEBUG & TEST ROUTES
// ============================================

// Test Invoice API
Route::get('/test-invoice', function () {
    return view('test-invoice');
});

// Debug API endpoint
Route::get('/debug-invoice/{bookingId}', function ($bookingId) {
    try {
        $booking = \App\Models\Booking::with(['user', 'tour'])->find($bookingId);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found',
                'booking_id' => $bookingId
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking found',
            'data' => [
                'booking_id' => $booking->id,
                'user_name' => $booking->user->name,
                'tour_title' => $booking->tour->title,
                'total_amount' => $booking->total_amount,
                'has_invoice' => $booking->invoice ? true : false
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
});

// Test route to check if we have any bookings
Route::get('/test-bookings', function () {
    try {
        $bookings = \App\Models\Booking::with(['user', 'tour'])->take(5)->get();

        return response()->json([
            'success' => true,
            'message' => 'Bookings found',
            'count' => $bookings->count(),
            'data' => $bookings->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'user_name' => $booking->user->name ?? 'No user',
                    'tour_title' => $booking->tour->title ?? 'No tour',
                    'total_amount' => $booking->total_amount ?? 0,
                    'status' => $booking->status ?? 'unknown'
                ];
            })
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
});

// Simple test route
Route::get('/simple-test', function () {
    return response()->json([
        'success' => true,
        'message' => 'Simple test route working!',
        'timestamp' => now()
    ]);
});

// Debug invoice route (simple version)
Route::get('/debug-invoice-simple/{bookingId}', function ($bookingId) {
    try {
        $booking = \App\Models\Booking::with(['user', 'tour'])->find($bookingId);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking found',
            'data' => [
                'booking_id' => $booking->id,
                'user_name' => $booking->user->name ?? 'No user',
                'tour_title' => $booking->tour->title ?? 'No tour',
                'total_amount' => $booking->total_amount ?? 0
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// ============================================
// INVOICE GENERATION ROUTE (Web Interface)
// ============================================

// Web invoice routes (no authentication required for admin interface)
Route::get('/web/invoices/booking/{bookingId}/pdf', function ($bookingId) {
    try {
        $booking = \App\Models\Booking::with(['user', 'tour', 'departure'])->find($bookingId);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        // Create invoice if not exists
        if (!$booking->invoice) {
            $invoice = \App\Models\Invoice::create([
                'booking_id' => $booking->id,
                'invoice_number' => \App\Models\Invoice::generateInvoiceNumber(),
                'issue_date' => now(),
                'amount' => $booking->total_amount,
                'status' => 'issued',
            ]);
        } else {
            $invoice = $booking->invoice;
        }

        // Load booking with promotion relationship
        $booking->load('promotion');

        // Company information
        $company = [
            'name' => 'Tour365 - Công ty Du lịch',
            'address' => '123 Đường ABC, Quận 1, TP.HCM',
            'phone' => '0123 456 789',
            'email' => 'info@tour365.com',
            'website' => 'www.tour365.com',
            'tax_code' => '0123456789'
        ];

        // For now, return HTML view instead of PDF
        $html = view('invoices.pdf', [
            'invoice' => $invoice,
            'booking' => $booking,
            'tour' => $booking->tour,
            'user' => $booking->user,
            'departure' => $booking->departure,
            'company' => $company,
            'promotion' => $booking->promotion ?? null,
        ])->render();

        // Save HTML to public directory for direct access
        $fileName = 'invoice_' . $bookingId . '_' . time() . '.html';
        $publicPath = public_path('invoices/' . $fileName);

        // Create invoices directory if not exists
        if (!file_exists(public_path('invoices'))) {
            mkdir(public_path('invoices'), 0755, true);
        }

        file_put_contents($publicPath, $html);

        $downloadUrl = url('invoices/' . $fileName);

        return response()->json([
            'success' => true,
            'message' => 'Invoice generated successfully (HTML format)',
            'data' => [
                'download_url' => $downloadUrl,
                'file_name' => $fileName,
                'invoice_number' => $invoice->invoice_number,
                'format' => 'html'
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error generating invoice: ' . $e->getMessage()
        ], 500);
    }
});

// Route to download invoice file (with proper headers)
Route::get('/web/invoices/booking/{bookingId}/download', function ($bookingId) {
    try {
        $booking = \App\Models\Booking::with(['user', 'tour', 'departure'])->find($bookingId);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        // Create invoice if not exists
        if (!$booking->invoice) {
            $invoice = \App\Models\Invoice::create([
                'booking_id' => $booking->id,
                'invoice_number' => \App\Models\Invoice::generateInvoiceNumber(),
                'issue_date' => now(),
                'amount' => $booking->total_amount,
                'status' => 'issued',
            ]);
        } else {
            $invoice = $booking->invoice;
        }

        // Load booking with promotion relationship
        $booking->load('promotion');

        // Company information
        $company = [
            'name' => 'Tour365 - Công ty Du lịch',
            'address' => '123 Đường ABC, Quận 1, TP.HCM',
            'phone' => '0123 456 789',
            'email' => 'info@tour365.com',
            'website' => 'www.tour365.com',
            'tax_code' => '0123456789'
        ];

        // Generate HTML
        $html = view('invoices.pdf', [
            'invoice' => $invoice,
            'booking' => $booking,
            'tour' => $booking->tour,
            'user' => $booking->user,
            'departure' => $booking->departure,
            'company' => $company,
            'promotion' => $booking->promotion ?? null,
        ])->render();

        // Generate filename
        $fileName = 'invoice_' . $bookingId . '_' . time() . '.html';

        // Return file with download headers
        return response($html, 200)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->header('Content-Length', strlen($html));
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error downloading invoice: ' . $e->getMessage()
        ], 500);
    }
});

// ============================================
// PUBLIC ROUTES
// ============================================

Route::get('/tours', [TourController::class, 'index'])->name('tours.index');
Route::get('/tours/{tour}', [TourController::class, 'show'])->name('tours.show');
Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');
Route::get('/sitemap', function () {
    return view('sitemap');
})->name('sitemap');

// Blog pages (static for now)
Route::get('/blog', function () {
    return view('blog.index');
})->name('blog.index');

Route::get('/blog/{slug}', function ($slug) {
    return view('blog.show', compact('slug'));
})->name('blog.show');

// Promotions page (static mock)
Route::get('/promotions', function () {
    return view('promotions.index');
})->name('promotions.index');

// ============================================
// AUTHENTICATION ROUTES
// ============================================

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get')->middleware('auth');

// ============================================
// CUSTOMER ROUTES (Authenticated Users)
// ============================================

Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile.index');

    // Route upload danh sách đoàn 
    Route::post('/bookings/{booking}/upload-manifest', [BookingController::class, 'uploadManifest'])
        ->name('bookings.upload-manifest');


    // Lịch sử yêu cầu tour đoàn
    Route::get('/profile/group-requests', [GroupTourController::class, 'history'])
        ->name('profile.group-requests');

    // Route để User gửi đánh giá
    Route::post('/bookings/{booking}/reviews', [ReviewController::class, 'store'])
         ->name('bookings.reviews.store'); // <-- Tên route mới

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/recent', [\App\Http\Controllers\NotificationController::class, 'recent'])->name('notifications.recent');
    Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');


    // Bookings
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

    // Wishlist routes
    Route::get('/wishlists', [WishlistsController::class, 'index'])->name('wishlists.index');
    Route::post('/wishlists', [WishlistsController::class, 'store'])->name('wishlists.store');
    Route::delete('/wishlists/{id}', [WishlistsController::class, 'destroy'])->name('wishlists.destroy');
    // Thanh toán MOMO
    Route::get('/checkout', function () {
        return view('checkout'); // form thanh toán
    });

    Route::post('/momo_payment/{id}', [CheckoutController::class, 'momo_payment'])->name('momo_payment');
    // MoMo redirect user về sau khi thanh toán (hiển thị kết quả)
    Route::get('/payment/momo_return', [CheckoutController::class, 'momo_return'])->name('momo.return');

    // MoMo gửi IPN server → server xác nhận đơn hàng
    Route::post('/payment/momo_ipn', [CheckoutController::class, 'momo_ipn'])->name('momo.ipn');

    // Thanh toán VNPay
    Route::post('/payment/vnpay/{id}', [CheckoutController::class, 'vnpay_payment'])->name('payment.vnpay');

});

// VNPay callback routes (không cần auth)
Route::get('/payment/vnpay/return', [CheckoutController::class, 'vnpay_return'])->name('payment.vnpay.return');
Route::get('/payment/vnpay_return', [CheckoutController::class, 'vnpay_return'])->name('payment.vnpay_return');

// --- 1. ROUTE CHO KHÁCH (Đặt ở ngoài, không cần đăng nhập cũng xem được) ---
Route::get('/dat-tour-doan', [GroupTourController::class, 'create'])->name('group-tour.create');
Route::post('/dat-tour-doan', [GroupTourController::class, 'store'])->name('group-tour.store');

// ============================================
// ADMIN ROUTES
// ============================================

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Tours management
    Route::get('/tours', [AdminController::class, 'tours'])->name('tours.index');
    Route::get('/tours/create', [AdminController::class, 'createTour'])->name('tours.create');
    Route::post('/tours', [AdminController::class, 'storeTour'])->name('tours.store');
    Route::get('/tours/{tour}', [AdminController::class, 'showTour'])->name('tours.show');
    Route::get('/tours/{tour}/edit', [AdminController::class, 'editTour'])->name('tours.edit');
    Route::put('/tours/{tour}', [AdminController::class, 'updateTour'])->name('tours.update');
    Route::delete('/tours/{tour}', [AdminController::class, 'deleteTour'])->name('tours.destroy');

    // Xóa ảnh của tour
    Route::delete('/tours/{tour}/images/{image}', [AdminController::class, 'deleteTourImage'])
        ->name('tours.images.delete');
        
        // route mới 3/12/2025
        Route::put('/departures/{id}/operating', [\App\Http\Controllers\Admin\DepartureController::class, 'updateOperating'])
        ->name('departures.update_operating');

        // route mới 4/12/2025
        Route::post('/bookings/{booking}/admin-upload-manifest', [AdminController::class, 'uploadManifest'])->name('bookings.upload-manifest');

    // Guides management
    Route::resource('guides', GuideWebController::class);

    // Invoices management
    Route::resource('invoices', \App\Http\Controllers\InvoiceController::class);
    Route::get('/invoices/{invoice}/pdf', [\App\Http\Controllers\InvoiceController::class, 'generatePdf'])->name('invoices.pdf');
    Route::get('/invoices/{invoice}/download', [\App\Http\Controllers\InvoiceController::class, 'downloadPdf'])->name('invoices.download');
    Route::post('/invoices/{invoice}/save-pdf', [\App\Http\Controllers\InvoiceController::class, 'savePdf'])->name('invoices.save-pdf');
    Route::post('/invoices/{invoice}/send-email', [\App\Http\Controllers\InvoiceController::class, 'sendEmail'])->name('invoices.send-email');
    Route::post('/invoices/{invoice}/mark-paid', [\App\Http\Controllers\InvoiceController::class, 'markAsPaid'])->name('invoices.mark-paid');

    // Bookings management
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{booking}', [AdminController::class, 'showBooking'])->name('bookings.show');
    Route::put('/bookings/{booking}', [AdminController::class, 'updateBooking'])->name('bookings.update');
    Route::post('/bookings/{booking}/confirm', [AdminController::class, 'confirmBooking'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/mark-as-paid', [AdminController::class, 'markAsPaid'])->name('bookings.markAsPaid');
    Route::post('/bookings/{booking}/cancel', [AdminController::class, 'cancelBooking'])->name('bookings.cancel');
    Route::delete('/bookings/{booking}', [AdminController::class, 'deleteBooking'])->name('bookings.destroy');
    Route::post('/bookings/{booking}/update-receipt', [AdminController::class, 'updateReceiptImage'])->name('bookings.updateReceipt');

    //  Group management operations
    Route::post('/bookings/confirm-group', [AdminController::class, 'confirmGroup'])->name('bookings.confirm-group');
    Route::post('/bookings/assign-guide', [AdminController::class, 'assignGuide'])->name('bookings.assign-guide');
    Route::post('/bookings/assign-vehicle', [AdminController::class, 'assignVehicle'])->name('bookings.assign-vehicle');
    Route::post('/bookings/send-pre-tour-info', [AdminController::class, 'sendPreTourInfo'])->name('bookings.send-pre-tour-info');

    // Customers management
    Route::get('/customers', [AdminController::class, 'customers'])->name('customers');
    Route::get('/customers/{user}', [AdminController::class, 'showCustomer'])->name('customers.show');  
    Route::put('/customers/{user}', [AdminController::class, 'updateCustomer'])->name('customers.update');
    Route::delete('/customers/{user}', [AdminController::class, 'deleteCustomer'])->name('customers.destroy');

    // Categories management
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::get('/categories/{category}', [AdminController::class, 'showCategory'])->name('categories.show');
    Route::get('/categories/{category}/edit', [AdminController::class, 'editCategory'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'deleteCategory'])->name('categories.destroy');


    // Reviews management  - code cũ của đánh giá 14/11  - rep 1-1 chạy ok 
    // Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews');
    // Route::post('/reviews/{review}/approve', [AdminController::class, 'approveReview'])->name('reviews.approve');
    // Route::post('/reviews/{review}/hide', [AdminController::class, 'hideReview'])->name('reviews.hide');
    // Route::post('/reviews/{review}/reply', [AdminController::class, 'storeReviewReply'])->name('reviews.reply');

// ============================================
    // Yêu cầu Tour đoàn (Group Requests) - VIẾT TƯỜNG MINH
    // ============================================
    // Route viết tường minh cho Group Requests (như chúng ta đã chốt)
    Route::get('/group-requests', [GroupRequestController::class, 'index'])->name('group-requests.index');
    Route::get('/group-requests/{id}', [GroupRequestController::class, 'show'])->name('group-requests.show');
    Route::put('/group-requests/{id}', [GroupRequestController::class, 'update'])->name('group-requests.update');
    Route::delete('/group-requests/{id}', [GroupRequestController::class, 'destroy'])->name('group-requests.destroy');

    // --- (THÊM MỚI) Route chuyển đổi thành Booking ---
    Route::post('/group-requests/{id}/convert', [GroupRequestController::class, 'convertToBooking'])->name('group-requests.convert');

    // reviews mới admin có thêm 2 nút sửa - xóa đánh giá 

    // Reviews management

    Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews');
    Route::put('/reviews/{review}', [AdminController::class, 'updateReview'])->name('reviews.update');
    Route::delete('/reviews/{review}', [AdminController::class, 'deleteReview'])->name('reviews.destroy');

    // Payments management
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
    Route::put('/payments/{payment}', [AdminController::class, 'updatePayment'])->name('payments.update');
    Route::delete('/payments/{payment}', [AdminController::class, 'deletePayment'])->name('payments.destroy');

    // Promotions management
    Route::get('/promotions', [AdminController::class, 'promotions'])->name('promotions');
    Route::get('/promotions/create', [AdminController::class, 'createPromotion'])->name('promotions.create');
    Route::post('/promotions', [AdminController::class, 'storePromotion'])->name('promotions.store');
    Route::get('/promotions/{promotion}', [AdminController::class, 'showPromotion'])->name('promotions.show');
    Route::get('/promotions/{promotion}/edit', [AdminController::class, 'editPromotion'])->name('promotions.edit');
    Route::put('/promotions/{promotion}', [AdminController::class, 'updatePromotion'])->name('promotions.update');
    Route::delete('/promotions/{promotion}', [AdminController::class, 'deletePromotion'])->name('promotions.destroy');

    // Check-in/Check-out management
    Route::get('/check-in-out', [CheckInOutController::class, 'index'])->name('check-in-out.index');
    Route::get('/check-in-out/create', [CheckInOutController::class, 'create'])->name('check-in-out.create');
    Route::post('/check-in-out', [CheckInOutController::class, 'store'])->name('check-in-out.store');
    Route::get('/check-in-out/{checkInOut}', [CheckInOutController::class, 'show'])->name('check-in-out.show');
    Route::get('/check-in-out/{checkInOut}/edit', [CheckInOutController::class, 'edit'])->name('check-in-out.edit');
    Route::put('/check-in-out/{checkInOut}', [CheckInOutController::class, 'update'])->name('check-in-out.update');
    Route::delete('/check-in-out/{checkInOut}', [CheckInOutController::class, 'destroy'])->name('check-in-out.destroy');
    Route::post('/check-in-out/{checkInOut}/confirm', [CheckInOutController::class, 'confirm'])->name('check-in-out.confirm');
    Route::post('/check-in-out/{checkInOut}/cancel', [CheckInOutController::class, 'cancel'])->name('check-in-out.cancel');
    Route::get('/check-in-out-statistics', [CheckInOutController::class, 'statistics'])->name('check-in-out.statistics');
    Route::get('/check-in-out-statistics-page', [CheckInOutController::class, 'showStatistics'])->name('check-in-out.statistics-page');

    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');

    // Notifications management
    Route::get('/notifications', [AdminController::class, 'notifications'])->name('notifications');
    Route::get('/notifications/create', [AdminController::class, 'createNotification'])->name('notifications.create');
    Route::post('/notifications', [AdminController::class, 'storeNotification'])->name('notifications.store');
    Route::put('/notifications/{notification}', [AdminController::class, 'updateNotification'])->name('notifications.update');
    Route::delete('/notifications/{notification}', [AdminController::class, 'deleteNotification'])->name('notifications.destroy');

    // Support management
    Route::get('/support', [AdminController::class, 'support'])->name('support');
    Route::get('/support/create', [AdminController::class, 'createSupportTicket'])->name('support.create');
    Route::post('/support', [AdminController::class, 'storeSupportTicket'])->name('support.store');
    Route::put('/support/{ticket}', [AdminController::class, 'updateTicket'])->name('support.update');
    Route::delete('/support/{ticket}', [AdminController::class, 'deleteTicket'])->name('support.destroy');

    // Admin quản lý khởi hành tour (Departures)
    Route::resource('departures', DepartureController::class);

    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

    // Users management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Banner management
    Route::get('/banners', [AdminController::class, 'banners'])->name('banners');
    Route::get('/banners/create', [AdminController::class, 'createBanner'])->name('banners.create');
    Route::post('/banners', [AdminController::class, 'storeBanner'])->name('banners.store');
    Route::get('/banners/{banner}', [AdminController::class, 'showBanner'])->name('banners.show');
    Route::get('/banners/{banner}/edit', [AdminController::class, 'editBanner'])->name('banners.edit');
    Route::put('/banners/{banner}', [AdminController::class, 'updateBanner'])->name('banners.update');
    Route::delete('/banners/{banner}', [AdminController::class, 'deleteBanner'])->name('banners.destroy');
    Route::post('/banners/{banner}/move', [AdminController::class, 'moveBanner'])->name('banners.move');

    // ============================================
    // TEST NOTIFICATIONS (Admin Only)
    // ============================================
    Route::get('/test/notifications', [\App\Http\Controllers\TestNotificationController::class, 'index'])->name('test.notifications');
    Route::post('/test/notifications/send', [\App\Http\Controllers\TestNotificationController::class, 'sendTest'])->name('test.notifications.send');

    // ============================================
    // INVOICE MANAGEMENT (Admin Only)
    // ============================================
    Route::get('/invoices', function () {
        return view('admin.invoices.index');
    })->name('invoices');

    Route::post('/invoices', [App\Http\Controllers\InvoiceWebController::class, 'createInvoice']);
    Route::put('/invoices/{invoice}/status', [App\Http\Controllers\InvoiceWebController::class, 'updateStatus']);
    Route::get('/invoices/{invoice}', [App\Http\Controllers\InvoiceWebController::class, 'show']);
    // Quản lý lịch trình
    Route::get('tours/{tour}/schedules', [TourScheduleController::class, 'index'])->name('schedules.index');
    Route::get('tours/{tour}/schedules/create', [TourScheduleController::class, 'create'])->name('schedules.create');
    Route::post('tours/{tour}/schedules', [TourScheduleController::class, 'store'])->name('schedules.store');
    Route::get('schedules/{id}/edit', [TourScheduleController::class, 'edit'])->name('schedules.edit');
    Route::put('schedules/{id}', [TourScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('schedules/{id}', [TourScheduleController::class, 'destroy'])->name('schedules.destroy');
});

// ============================================
// STAFF ROUTES
// ============================================

// Guide routes
Route::middleware(['auth', 'guide'])->prefix('guide')->name('guide.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Guide\GuideDashboardController::class, 'index'])->name('dashboard');
    
    // Departures
    Route::get('/departures/{id}', [\App\Http\Controllers\Guide\GuideDashboardController::class, 'showDeparture'])->name('departures.show');
    Route::get('/departures/{departureId}/customers', [\App\Http\Controllers\Guide\GuideDashboardController::class, 'showCustomers'])->name('departures.customers');
    
    // Tour Logs (Nhật ký tour)
    Route::get('/departures/{departureId}/logs', [\App\Http\Controllers\Guide\TourLogController::class, 'index'])->name('tour-logs.index');
    Route::get('/departures/{departureId}/logs/create', [\App\Http\Controllers\Guide\TourLogController::class, 'create'])->name('tour-logs.create');
    Route::post('/departures/{departureId}/logs', [\App\Http\Controllers\Guide\TourLogController::class, 'store'])->name('tour-logs.store');
    Route::get('/departures/{departureId}/logs/{logId}', [\App\Http\Controllers\Guide\TourLogController::class, 'show'])->name('tour-logs.show');
    Route::get('/departures/{departureId}/logs/{logId}/edit', [\App\Http\Controllers\Guide\TourLogController::class, 'edit'])->name('tour-logs.edit');
    Route::put('/departures/{departureId}/logs/{logId}', [\App\Http\Controllers\Guide\TourLogController::class, 'update'])->name('tour-logs.update');
    Route::delete('/departures/{departureId}/logs/{logId}', [\App\Http\Controllers\Guide\TourLogController::class, 'destroy'])->name('tour-logs.destroy');
    
    // Check-ins (Điểm danh)
    Route::get('/departures/{departureId}/check-ins', [\App\Http\Controllers\Guide\CheckInController::class, 'index'])->name('check-ins.index');
    Route::get('/departures/{departureId}/check-ins/{checkInId}', [\App\Http\Controllers\Guide\CheckInController::class, 'show'])->name('check-ins.show');
    Route::post('/departures/{departureId}/check-ins', [\App\Http\Controllers\Guide\CheckInController::class, 'store'])->name('check-ins.store');
    Route::put('/departures/{departureId}/check-ins/{checkInId}', [\App\Http\Controllers\Guide\CheckInController::class, 'update'])->name('check-ins.update');
    Route::delete('/departures/{departureId}/check-ins/{checkInId}', [\App\Http\Controllers\Guide\CheckInController::class, 'destroy'])->name('check-ins.destroy');
    
    // Special Requests (Yêu cầu đặc biệt)
    Route::get('/departures/{departureId}/special-requests', [\App\Http\Controllers\Guide\SpecialRequestController::class, 'index'])->name('special-requests.index');
    Route::get('/departures/{departureId}/special-requests/create', [\App\Http\Controllers\Guide\SpecialRequestController::class, 'create'])->name('special-requests.create');
    Route::post('/departures/{departureId}/special-requests', [\App\Http\Controllers\Guide\SpecialRequestController::class, 'store'])->name('special-requests.store');
    Route::get('/departures/{departureId}/special-requests/{requestId}', [\App\Http\Controllers\Guide\SpecialRequestController::class, 'show'])->name('special-requests.show');
    Route::put('/departures/{departureId}/special-requests/{requestId}', [\App\Http\Controllers\Guide\SpecialRequestController::class, 'update'])->name('special-requests.update');
    Route::delete('/departures/{departureId}/special-requests/{requestId}', [\App\Http\Controllers\Guide\SpecialRequestController::class, 'destroy'])->name('special-requests.destroy');
    
    // Feedback (Phản hồi đánh giá)
    Route::get('/feedback', [\App\Http\Controllers\Guide\TourFeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/departures/{departureId}/feedback/create', [\App\Http\Controllers\Guide\TourFeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/departures/{departureId}/feedback', [\App\Http\Controllers\Guide\TourFeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/feedback/{id}', [\App\Http\Controllers\Guide\TourFeedbackController::class, 'show'])->name('feedback.show');
    Route::get('/feedback/{id}/edit', [\App\Http\Controllers\Guide\TourFeedbackController::class, 'edit'])->name('feedback.edit');
    Route::put('/feedback/{id}', [\App\Http\Controllers\Guide\TourFeedbackController::class, 'update'])->name('feedback.update');
    Route::delete('/feedback/{id}', [\App\Http\Controllers\Guide\TourFeedbackController::class, 'destroy'])->name('feedback.destroy');
});

Route::middleware(['auth', 'staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/', [StaffController::class, 'dashboard'])->name('dashboard');

    // Tours management (read-only for staff)
    Route::get('/tours', [StaffController::class, 'tours'])->name('tours');
    Route::get('/tours/{tour}', [StaffController::class, 'showTour'])->name('tours.show');

    // Bookings management
    Route::get('/bookings', [StaffController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{booking}', [StaffController::class, 'showBooking'])->name('bookings.show');
    Route::put('/bookings/{booking}', [StaffController::class, 'updateBooking'])->name('bookings.update');

    // Customers management
    Route::get('/customers', [StaffController::class, 'customers'])->name('customers');
    Route::get('/customers/{user}', [StaffController::class, 'showCustomer'])->name('customers.show');

    // Profile
    Route::get('/profile', [StaffController::class, 'profile'])->name('profile');
    Route::post('/profile', [StaffController::class, 'updateProfile'])->name('profile.update');
});
