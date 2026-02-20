<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        if (!$user->hasPermissionTo($permission)) {
            abort(403, 'You do not have access to this page.');
        }

        return $next($request);
    }
}
