<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

$user = User::find(1);
if ($user) {
    // set the current user on the auth manager
    Auth::setUser($user);
}

$content = view('admin.sidebar-admin')->render();

// extract li.sidebar-item elements and print label and class
preg_match_all('/<li[^>]*class="([^"]*)"[^>]*>(.*?)<\/li>/si', $content, $matches, PREG_SET_ORDER);
foreach ($matches as $m) {
    $class = $m[1];
    $inner = $m[2];
    $label = null;
    if (preg_match('/<span[^>]*>(.*?)<\/span>/si', $inner, $sm)) {
        $label = trim(strip_tags($sm[1]));
    }
    echo "CLASS: {$class} | LABEL: {$label}\n";
}

echo "\nFULL SIDEBAR HTML:\n";
echo $content;
