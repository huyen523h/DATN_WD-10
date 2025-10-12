<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeAuthController extends Controller
{
    /**
     * Show employee login form
     */
    public function showLoginForm(): View
    {
        return view('employee.auth.login');
    }

    /**
     * Handle employee login
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Find employee by email
        $employee = Employee::where('email', $credentials['email'])->first();
        
        if (!$employee) {
            return back()->withErrors(['email' => 'Email không tồn tại trong hệ thống.'])->withInput();
        }

        // Check if employee has user account
        if (!$employee->user) {
            return back()->withErrors(['email' => 'Tài khoản chưa được tạo. Vui lòng liên hệ quản trị viên.'])->withInput();
        }

        // Check if employee is active
        if ($employee->status !== 'active') {
            return back()->withErrors(['email' => 'Tài khoản đã bị khóa. Vui lòng liên hệ quản trị viên.'])->withInput();
        }

        // Attempt to login with user credentials
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            
            // Store employee info in session
            session(['employee_id' => $employee->id]);
            session(['employee_role' => $employee->role->name ?? 'employee']);
            session(['employee_name' => $employee->name]);
            session(['employee_avatar' => $employee->avatar_url]);
            
            return redirect()->intended(route('employee.dashboard'));
        }

        return back()->withErrors(['password' => 'Mật khẩu không đúng.'])->withInput();
    }

    /**
     * Show employee dashboard
     */
    public function dashboard(): View|RedirectResponse
    {
        $employee = Employee::with(['role', 'user'])->find(session('employee_id'));
        
        if (!$employee) {
            Auth::logout();
            return redirect()->route('employee.login');
        }

        return view('employee.dashboard', compact('employee'));
    }

    /**
     * Show employee profile
     */
    public function profile(): View|RedirectResponse
    {
        $employee = Employee::with(['role', 'user'])->find(session('employee_id'));
        
        if (!$employee) {
            Auth::logout();
            return redirect()->route('employee.login');
        }

        return view('employee.profile', compact('employee'));
    }

    /**
     * Update employee profile
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $employee = Employee::find(session('employee_id'));
        
        if (!$employee) {
            Auth::logout();
            return redirect()->route('employee.login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update employee info
        $employee->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('employees', 'public');
            $employee->update(['avatar' => $avatarPath]);
        }

        // Update password if provided
        if ($validated['new_password']) {
            if (!$validated['current_password'] || !Hash::check($validated['current_password'], $employee->user->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.'])->withInput();
            }
            
            $employee->user->update([
                'password' => Hash::make($validated['new_password'])
            ]);
        }

        return redirect()->route('employee.profile')->with('success', 'Thông tin đã được cập nhật thành công!');
    }

    /**
     * Handle employee logout
     */
    public function logout(Request $request): RedirectResponse
    {
        // Clear employee session data
        $request->session()->forget(['employee_id', 'employee_role', 'employee_name', 'employee_avatar']);
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('employee.logout-success');
    }

    /**
     * Show logout success page
     */
    public function logoutSuccess(): View
    {
        return view('employee.auth.logout-success');
    }

    /**
     * Create user account for employee
     */
    public function createUserAccount(Employee $employee): RedirectResponse
    {
        // Check if employee already has user account
        if ($employee->user) {
            return back()->with('error', 'Nhân viên đã có tài khoản đăng nhập.');
        }

        // Create user account
        $user = User::create([
            'name' => $employee->name,
            'email' => $employee->email,
            'password' => Hash::make('123456'), // Default password
            'email_verified_at' => now(),
        ]);

        // Link user to employee
        $employee->update(['user_id' => $user->id]);

        return back()->with('success', 'Tài khoản đăng nhập đã được tạo. Mật khẩu mặc định: 123456');
    }
}
