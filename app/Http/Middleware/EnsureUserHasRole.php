<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $allowedRoles = [];
        if (str_contains($roles, ',')) {
            $allowedRoles = array_map('trim', explode(',', $roles));
        } elseif (str_contains($roles, '|')) {
            $allowedRoles = array_map('trim', explode('|', $roles));
        } else {
            $allowedRoles = [trim($roles)];
        }

        $hasAccess = false;
        foreach ($allowedRoles as $role) {
            if (auth()->user()->hasRole($role)) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
}
