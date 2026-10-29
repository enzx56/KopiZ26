<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Setup temporary directories for writable paths in Vercel Serverless environment
$tmpDirs = [
    '/tmp/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

putenv('APP_CONFIG_CACHE=/tmp/bootstrap/cache/config.php');
putenv('APP_EVENTS_CACHE=/tmp/bootstrap/cache/events.php');
putenv('APP_PACKAGES_CACHE=/tmp/bootstrap/cache/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/bootstrap/cache/routes.php');
putenv('APP_SERVICES_CACHE=/tmp/bootstrap/cache/services.php');
putenv('VIEW_COMPILED_PATH=/tmp/views');

// Autoload composer
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel and handle the request
/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Set writable storage path
$app->useStoragePath('/tmp/storage');

// Capture incoming serverless request
$request = Request::capture();

// Fix request URI for Vercel internal rewrite: if path is /api/index.php, recover original path
if (str_starts_with($request->server->get('REQUEST_URI', ''), '/api/index.php')) {
    $origUri = $request->server->get('HTTP_X_NOW_ROUTE', $request->server->get('HTTP_X_VERCEL_FORWARDED_PATH', '/'));
    if ($origUri) {
        $request->server->set('REQUEST_URI', $origUri);
    }
}

$app->handleRequest($request);
