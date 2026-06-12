<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

// set a basic request in container so auth guard can set request
Illuminate\Support\Facades\Request::createFromGlobals();

use App\Models\User;
use Illuminate\Http\Request;

// login as user id 1 if exists
$user = User::find(1);
if ($user) {
    auth()->login($user);
}

function getActiveMenuItems($url)
{
    $request = Request::create($url, 'GET');
    $response = app()->handle($request);
    $content = $response->getContent();
    // find li elements with class sidebar-item
    preg_match_all('/<li[^>]*class="([^"]*)"[^>]*>(.*?)<\/li>/si', $content, $matches, PREG_SET_ORDER);
    $results = [];
    foreach ($matches as $m) {
        $class = $m[1];
        $inner = $m[2];
        if (strpos($class, 'sidebar-item') !== false) {
            $label = null;
            if (preg_match('/<span[^>]*>(.*?)<\/span>/si', $inner, $sm)) {
                $label = trim(strip_tags($sm[1]));
            }
            $results[] = ['class' => $class, 'label' => $label];
        }
    }

    return $results;
}

$pages = ['/barang', '/kategori-barang', '/laporan-barang', '/dashboard'];
foreach ($pages as $p) {
    echo "\n--- PAGE: $p ---\n";
    $items = getActiveMenuItems($p);
    foreach ($items as $it) {
        echo "CLASS: {$it['class']} | LABEL: {$it['label']}\n";
    }
}
