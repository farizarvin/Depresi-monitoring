<?php
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
define('LARAVEL_START', microtime(true));
// Ubah dari '../' menjadi './' karena sekarang sudah di root
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}
// Ubah dari '../vendor' menjadi './vendor'
require __DIR__.'/vendor/autoload.php';
// Ubah dari '../bootstrap' menjadi './bootstrap'
/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';
$app->handleRequest(Request::capture());