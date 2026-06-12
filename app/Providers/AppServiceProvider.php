<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if (file_exists($helperPath = app_path('Helpers/helpers.php'))) {
            require_once $helperPath;
        }

        if (! function_exists('feature_enabled')) {
            function feature_enabled(string $key): bool
            {
                return \App\Helpers\FeatureHelper::isEnabled($key);
            }
        }
    }
}
