<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'role' => 'required|exists:roles,name',
        ]);

        $userData = $request->all();
        $userData['password'] = Hash::make($userData['password']);

        $user = User::create($userData);

        // Assign role
        $role = $userData['role'] ?? 'customer';
        $user->assignRole($role);

        return redirect()->route('admin.users.index')
            ->with('success', 'Tạo người dùng thành công.');
    }

    public function show(User $user)
    {
        $user->load(['roles', 'bookings', 'reviews']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load('roles');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'role' => 'required|exists:roles,name',
        ]);

        $userData = $request->all();

        // Update password if provided
        if (isset($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        } else {
            unset($userData['password']);
        }

        $user->update($userData);

        // Update role
        $role = $userData['role'] ?? 'customer';
        $user->roles()->detach();
        $user->assignRole($role);

        return redirect()->route('admin.users.index')
            ->with('success', 'Cập nhật người dùng thành công.');
    }

    public function destroy(User $user)
    {
        // Prevent deleting current user
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'Không thể xóa chính mình.');
        }

        // Check if user has bookings
        if ($user->bookings()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Không thể xóa người dùng có booking.');
        }

        // Check if user has reviews
        if ($user->reviews()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Không thể xóa người dùng có đánh giá.');
        }

        // Detach roles before deleting
        $user->roles()->detach();
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Xóa người dùng thành công.');
    }

    // API Methods
    public function apiIndex(Request $request)
    {
        $query = User::with('roles');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function apiStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'role' => 'required|exists:roles,name',
        ]);

        $userData = $request->all();
        $userData['password'] = Hash::make($userData['password']);

        $user = User::create($userData);
        $user->assignRole($userData['role']);

        return response()->json([
            'success' => true,
            'message' => 'Tạo người dùng thành công.',
            'data' => $user->load('roles')
        ], 201);
    }

    public function apiShow(User $user)
    {
        $user->load(['roles', 'bookings', 'reviews']);
        
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function apiUpdate(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'role' => 'required|exists:roles,name',
        ]);

        $userData = $request->all();

        if (isset($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        } else {
            unset($userData['password']);
        }

        $user->update($userData);

        $role = $userData['role'] ?? 'customer';
        $user->roles()->detach();
        $user->assignRole($role);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật người dùng thành công.',
            'data' => $user->load('roles')
        ]);
    }

    public function apiDestroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa chính mình.'
            ], 400);
        }

        if ($user->bookings()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa người dùng có booking.'
            ], 400);
        }

        if ($user->reviews()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa người dùng có đánh giá.'
            ], 400);
        }

        $user->roles()->detach();
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa người dùng thành công.'
        ]);
    }
}
