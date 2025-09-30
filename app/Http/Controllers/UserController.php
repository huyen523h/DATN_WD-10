<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');
        $currentUser = auth()->user();

        if ($currentUser->isStaff() && !$currentUser->isAdmin()) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', 'customer');
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role') && $currentUser->isAdmin()) {
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
        $currentUser = auth()->user();

        if ($currentUser->isStaff() && !$currentUser->isAdmin()) {
            $roles = Role::where('name', 'customer')->get();
        } else {
            $roles = Role::all();
        }

        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $currentUser = auth()->user();
        $userData = $request->validated();

        $role = $userData['role'];
        unset($userData['role']);

        if ($currentUser->isStaff() && !$currentUser->isAdmin()) {
            $role = 'customer';
        }

        $user = User::create($userData);
        $user->assignRole($role);

        return redirect()->route('admin.users.index')
            ->with('success', 'Tạo người dùng thành công.');
    }

    public function show(User $user)
    {
        $user->load('roles');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $currentUser = auth()->user();

        if ($currentUser->isStaff() && !$currentUser->isAdmin()) {
            if (!$user->hasRole('customer')) {
                abort(403, 'Bạn không có quyền chỉnh sửa người dùng này.');
            }
            $roles = Role::where('name', 'customer')->get();
        } else {
            $roles = Role::all();
        }

        $user->load('roles');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $userData = $request->validated();

        $role = $userData['role'];
        unset($userData['role']);

        if (empty($userData['password'])) {
            unset($userData['password']);
        }

        $user->update($userData);

        $user->roles()->detach();
        $user->assignRole($role);

        return redirect()->route('admin.users.index')
            ->with('success', 'Cập nhật người dùng thành công.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Bạn không thể xóa chính mình.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Xóa người dùng thành công.');
    }
}
