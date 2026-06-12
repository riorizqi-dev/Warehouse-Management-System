<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\FeatureToggle;

$features = FeatureToggle::all();
foreach ($features as $f) {
    echo $f->id.':'.$f->key.':'.($f->name ?? 'NULL').':'.($f->route_name ?? 'NULL').':'.($f->enabled ? 'ENABLED' : 'DISABLED').PHP_EOL;
}
