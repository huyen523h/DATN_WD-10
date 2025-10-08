<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

// Temporary route to test user management
Route::get('/admin', function () {
    return redirect()->route('admin.users.index');
});

// Debug route
Route::get('/debug-users', function () {
    $users = App\Models\User::all();
    return response()->json([
        'total_users' => $users->count(),
        'users' => $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => $user->is_active,
            ];
        })
    ]);
});

// Test create user route
Route::post('/test-create-user', function (Illuminate\Http\Request $request) {
    try {
        $user = App\Models\User::create([
            'name' => $request->name ?? 'Test User',
            'email' => $request->email ?? 'test@example.com',
            'password' => bcrypt($request->password ?? 'password'),
            'role' => $request->role ?? 'customer',
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => $request->has('is_active'),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'user' => $user
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
});


// Admin User Management Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
});
