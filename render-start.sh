#!/bin/bash

# Install dependencies
composer install --no-dev --optimize-autoloader

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Start the server
apache2-foreground