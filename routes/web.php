<?php

use App\Http\Controllers\Admin\DepartureController;
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
            'data' => $bookings->map(function($booking) {
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

    // Payment routes - MoMo & VNPay
    Route::get('/payment/{bookingId}', [PaymentController::class, 'processPayment'])->name('payment.process');
    Route::get('/payment/momo/return', [PaymentController::class, 'momoReturn'])->name('payment.momo.return');
    Route::post('/payment/momo/notify', [PaymentController::class, 'momoNotify'])->name('payment.momo.notify');
    Route::get('/payment/vnpay/callback', [PaymentController::class, 'vnpayCallback'])->name('payment.vnpay.callback');
    
    // Legacy MoMo routes (for backward compatibility)
    Route::get('/checkout', function () {
        return view('checkout'); // form thanh toán
    });
    Route::post('/momo_payment/{id}', [CheckoutController::class, 'momo_payment'])->name('momo_payment');
    Route::get('/payment/momo_return', [CheckoutController::class, 'momo_return'])->name('momo.return');
    Route::post('/payment/momo_ipn', [CheckoutController::class, 'momo_ipn'])->name('momo.ipn');
});

// ============================================
// ADMIN ROUTES
// ============================================

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Tours management
    Route::get('/tours', [AdminController::class, 'tours'])->name('tours');
    Route::get('/tours/create', [AdminController::class, 'createTour'])->name('tours.create');
    Route::post('/tours', [AdminController::class, 'storeTour'])->name('tours.store');
    Route::get('/tours/{tour}', [AdminController::class, 'showTour'])->name('tours.show');
    Route::get('/tours/{tour}/edit', [AdminController::class, 'editTour'])->name('tours.edit');
    Route::put('/tours/{tour}', [AdminController::class, 'updateTour'])->name('tours.update');
    Route::delete('/tours/{tour}', [AdminController::class, 'deleteTour'])->name('tours.destroy');
    
    // Xóa ảnh của tour
    Route::delete('/tours/{tour}/images/{image}', [AdminController::class, 'deleteTourImage'])
        ->name('tours.images.delete');

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
    Route::delete('/bookings/{booking}', [AdminController::class, 'deleteBooking'])->name('bookings.destroy');

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
    
    // ============================================
    // INVOICE MANAGEMENT (Admin Only)
    // ============================================
    Route::get('/invoices', function () {
        return view('admin.invoices.index');
    })->name('invoices');
    
    Route::post('/invoices', [App\Http\Controllers\InvoiceWebController::class, 'createInvoice']);
    Route::put('/invoices/{invoice}/status', [App\Http\Controllers\InvoiceWebController::class, 'updateStatus']);
    Route::get('/invoices/{invoice}', [App\Http\Controllers\InvoiceWebController::class, 'show']);
});

// ============================================
// STAFF ROUTES
// ============================================

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
