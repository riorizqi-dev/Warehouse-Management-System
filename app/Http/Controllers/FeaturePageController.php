<?php

namespace App\Http\Controllers;

use App\Models\FeatureToggle;

class FeaturePageController extends Controller
{
    public function show(string $key)
    {
        $feature = FeatureToggle::where('key', $key)
            ->where('enabled', true)
            ->firstOrFail();

        return view('admin.feature-page', compact('feature'));
    }
}
