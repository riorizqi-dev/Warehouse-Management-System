<?php

namespace App\Helpers;

use App\Models\FeatureToggle;

class FeatureHelper
{
    public static function isEnabled(string $key): bool
    {
        $feature = FeatureToggle::where('key', $key)->first();

        if (! $feature) {
            return true;
        }

        return (bool) $feature->enabled;
    }

    public static function isDisabled(string $key): bool
    {
        return ! self::isEnabled($key);
    }
}
