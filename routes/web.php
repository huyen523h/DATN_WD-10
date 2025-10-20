<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Api\WishlistsController;

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\TourController as AdminTourController;
use App\Http\Controllers\EmployeeAuthController;



Route::get('/', function () {
    return view('welcome');
})->name('welcome');


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
            'departures' => $departures->map(function($dep) {
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
        'departures' => $departures->map(function($dep) {
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

    foreach($departures as $dep) {
        $output .= "Departure ID: " . $dep->id . "\n";
        $output .= "Date: " . $dep->departure_date . "\n";
        $output .= "Seats: " . $dep->seats_available . "/" . $dep->seats_total . "\n";
        $output .= "---\n";
    }

    return response($output, 200, ['Content-Type' => 'text/plain']);
});

// Public routes
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

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get')->middleware('auth');

// Customer routes - Truy cập tự do
Route::group([], function () {
    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile.index');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');


    // Payment routes
    Route::get('/checkout/{booking}', function ($booking) {
        return view('payments.checkout', compact('booking'));
    })->name('payments.checkout');


    // Wishlist routes
    Route::get('/wishlists', [WishlistsController::class, 'index'])->name('wishlists.index');
    Route::post('/wishlists', [WishlistsController::class, 'store'])->name('wishlists.store');
    Route::delete('/wishlists/{id}', [WishlistsController::class, 'destroy'])->name('wishlists.destroy');

    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

    // Thanh toán MoMo & VNPAY
    Route::get('/payment/{bookingId}', [PaymentController::class, 'processPayment'])->name('payment.process');
    Route::get('/payment/momo/return', [PaymentController::class, 'momoReturn'])->name('payment.momo.return');
    Route::post('/payment/momo/notify', [PaymentController::class, 'momoNotify'])->name('payment.momo.notify');

    Route::get('/payment/vnpay/callback', [PaymentController::class, 'vnpayCallback'])->name('payment.vnpay.callback');

});

// Admin routes - Truy cập tự do
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Users management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Tours management
    Route::get('/tours', [AdminController::class, 'tours'])->name('tours');
    Route::get('/tours/create', [AdminController::class, 'createTour'])->name('tours.create');
    Route::post('/tours', [AdminController::class, 'storeTour'])->name('tours.store');
    Route::get('/tours/{tour}/edit', [AdminController::class, 'editTour'])->name('tours.edit');
    Route::put('/tours/{tour}', [AdminController::class, 'updateTour'])->name('tours.update');
    Route::delete('/tours/{tour}', [AdminController::class, 'deleteTour'])->name('tours.destroy');


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
    
     // Employees management
    Route::resource('employees', EmployeeController::class);
    Route::post('/employees/{employee}/create-account', [EmployeeController::class, 'createUserAccount'])->name('employees.create-account');
});

// Employee routes
Route::prefix('employee')->name('employee.')->group(function () {
    Route::get('/login', [EmployeeAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [EmployeeAuthController::class, 'login']);
    Route::post('/logout', [EmployeeAuthController::class, 'logout'])->name('logout');
    
    Route::group([], function () {
        Route::get('/dashboard', [EmployeeAuthController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [EmployeeAuthController::class, 'profile'])->name('profile');
        Route::post('/profile', [EmployeeAuthController::class, 'updateProfile'])->name('profile.update');
    });
});
