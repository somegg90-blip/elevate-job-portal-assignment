<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RoleMiddleware
 *
 * A custom middleware that restricts route access based on the user's role.
 *
 * OOP Principle — Encapsulation:
 *   Authorization logic is encapsulated here, keeping Controllers clean.
 *
 * Usage in routes:
 *   Route::middleware('role:company')->group(...)
 *   Route::middleware('role:jobseeker')->group(...)
 *
 * @package App\Http\Middleware
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     * @param  string                    $role    The required role (e.g. 'company', 'jobseeker')
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // If not logged in at all, redirect to login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please log in to access this page.');
        }

        $user = Auth::user();

        // Admin bypasses all role restrictions
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Check the specific role required
        if ($user->role !== $role) {
            abort(403, "Access denied. This page requires a '{$role}' account.");
        }

        return $next($request);
    }
}
