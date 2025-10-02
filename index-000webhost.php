<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Update paths for 000webhost structure
// Change from:
// require __DIR__.'/../vendor/autoload.php';
// To:
require __DIR__.'/laravel/vendor/autoload.php';

// Change from:
// (require_once __DIR__.'/../bootstrap/app.php')
// To:
(require_once __DIR__.'/laravel/bootstrap/app.php')
    ->handleRequest(Request::capture());