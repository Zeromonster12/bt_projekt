<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthorized. User not authenticated.'], 401);
        }

        $user = $request->user();
        $userRole = null;
        
        if (isset($user->role_id)) {
            $role = \App\Models\Role::find($user->role_id);
            $userRole = $role ? $role->name : null;
        }
        
        if (!in_array($userRole, $roles)) {
            return response()->json([
                'message' => 'Unauthorized. Insufficient permissions.',
                'required' => $roles,
                'current' => $userRole
            ], 403);
        }

        return $next($request);
    }
}
