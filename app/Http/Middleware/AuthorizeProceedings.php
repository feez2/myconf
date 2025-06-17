<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeProceedings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Always allow admin access
        if ($user->role === 'admin') {
            Log::info('Admin access granted in proceedings middleware', [
                'user_id' => $user->id,
                'path' => $request->path()
            ]);
            return $next($request);
        }

        // For non-admin users, check the manageProceedings permission
        if (!$user->can('manageProceedings', \App\Models\Conference::class)) {
            Log::warning('Unauthorized proceedings access attempt', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'path' => $request->path()
            ]);
            abort(403, 'This action is unauthorized.');
        }

        return $next($request);
    }
} 