<?php

/**
 * Vercel Serverless Function entry point.
 * This forwards all requests to the standard Laravel public/index.php.
 */
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// MAGIC VERCEL TRICK: Move storage to /tmp to avoid Read-Only file system errors
$app->useStoragePath($_ENV['APP_STORAGE'] ?? '/tmp/storage');
$app->useBootstrapPath($_ENV['APP_BOOTSTRAP'] ?? '/tmp/bootstrap');

// Create required directories in /tmp
$storagePath = $app->storagePath();
$bootstrapPath = $app->bootstrapPath();
foreach ([
    "{$storagePath}/app/public", 
    "{$storagePath}/framework/views", 
    "{$storagePath}/framework/cache/data", 
    "{$storagePath}/framework/sessions", 
    "{$storagePath}/logs",
    "{$bootstrapPath}/cache"
] as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

$app->handleRequest(Request::capture());
