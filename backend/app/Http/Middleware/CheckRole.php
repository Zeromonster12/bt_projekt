<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole {
    public function handle(Request $request, Closure $next, string $role) {
        if (!$request->user() || !$request->user()->hasRole($role)) {
            return response()->json(['message' => 'Access denied'], 403);
        }
        return $next($request);
    }
}