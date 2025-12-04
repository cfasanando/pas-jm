<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckRole
{
    /**
     * Check that the authenticated user has at least one of the given roles.
     *
     * Usage in routes:
     *   ->middleware('role:admin')
     *   ->middleware('role:admin,inspector')
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        // If there is no authenticated user, block access.
        if (!$user) {
            abort(403);
        }

        // If no roles were specified, let the request pass.
        if (empty($roles)) {
            return $next($request);
        }

        // Check roles using the pivot table role_user and roles.slug
        $hasRole = DB::table('role_user')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('role_user.user_id', $user->id)
            ->whereIn('roles.slug', $roles)
            ->exists();

        if (!$hasRole) {
            abort(403);
        }

        return $next($request);
    }
}
