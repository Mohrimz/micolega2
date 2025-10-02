<?php
// Vercel PHP runtime entry point for Laravel
require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Http\Request;

// Set environment to production
putenv('APP_ENV=production');
putenv('APP_DEBUG=false');

// Load all environment variables
putenv('APP_NAME=MiColega');
putenv('APP_KEY=base64:nc1rvpr0Qy1bQ07LjOjKmdMPJ3FJlO2nM9LIZ+wg3Oo=');
putenv('LOG_CHANNEL=stderr');
putenv('DB_CONNECTION=pgsql');
putenv('DB_HOST=ep-solitary-bush-ado718et-pooler.c-2.us-east-1.aws.neon.tech');
putenv('DB_PORT=5432');
putenv('DB_DATABASE=neondb');
putenv('DB_USERNAME=neondb_owner');
putenv('DB_PASSWORD=npg_eUlxp1fz3Hqc');
putenv('DB_SSLMODE=require');
putenv('SESSION_DRIVER=database');
putenv('CACHE_DRIVER=database');
putenv('QUEUE_CONNECTION=database');

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Handle the request
$response = $app->handleRequest(Request::capture());
$response->send();