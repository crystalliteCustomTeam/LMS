<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$root = __DIR__;

// Agar local par ho to root = ek level upar
if (file_exists($root.'/../vendor/autoload.php')) {
    require $root.'/../vendor/autoload.php';
    $app = require_once $root.'/../bootstrap/app.php';
} else {
    // Agar cPanel subfolder (LMS) me ho
    require $root.'/vendor/autoload.php';
    $app = require_once $root.'/bootstrap/app.php';
}

$kernel = $app->make(Kernel::class);

$response = tap($kernel->handle(
    $request = Request::capture()
))->send();

$kernel->terminate($request, $response);
