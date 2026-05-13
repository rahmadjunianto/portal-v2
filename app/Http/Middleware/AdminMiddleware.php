<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Only admin role can access.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Check if user has admin role
        $user = auth()->user();
        
        if ($user->role_name !== 'admin') {
            // Non-admin users can only access posts
            if ($request->is('admin/posts*')) {
                return $next($request);
            }
            
            // Redirect non-admin to posts if trying to access other pages
            if (!$request->is('admin/dashboard', 'admin/profile*')) {
                return redirect()->route('admin.posts.index')->with('error', 'Anda tidak memiliki akses ke menu tersebut.');
            }
            
            return $next($request);
        }

        return $next($request);
    }
}
