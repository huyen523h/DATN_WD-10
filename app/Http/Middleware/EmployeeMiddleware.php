<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để truy cập.');
        }

        $user = auth()->user();
        
        // Check if user is admin or employee
        if (!$user->isAdmin() && !$user->isStaff()) {
            abort(403, 'Chỉ quản trị viên và nhân viên mới có thể truy cập trang này.');
        }

        return $next($request);
    }
}
