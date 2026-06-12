<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth()->user();
        if (! $user) {
            abort(403, 'Unauthorized.');
        }

        $normalizedRoles = collect($roles)
            ->flatMap(function ($role) {
                return explode(',', (string) $role);
            })
            ->map(fn ($item) => strtolower(trim((string) $item)))
            ->filter()
            ->values()
            ->all();

        if (empty($normalizedRoles)) {
            return $next($request);
        }

        if ($user->hasAnyRole($normalizedRoles)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses ke modul ini.');
    }
}
