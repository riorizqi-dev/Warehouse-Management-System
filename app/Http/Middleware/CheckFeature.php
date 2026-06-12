<?php

namespace App\Http\Middleware;

use App\Helpers\FeatureHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeature
{
    public function handle(Request $request, Closure $next, string $featureKey): Response
    {
        if (auth()->check() && auth()->user()->isSuperAdmin()) {
            return $next($request);
        }

        if (! FeatureHelper::isEnabled($featureKey)) {
            abort(403, 'Fitur ini tidak tersedia saat ini.');
        }

        return $next($request);
    }
}
