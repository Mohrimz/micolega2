#!/bin/bash

# Wait for database to be ready
echo "Waiting for database..."
sleep 5

# Run Laravel optimization commands
echo "Running Laravel setup..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "Running migrations..."
php artisan migrate --force

# Start Apache
echo "Starting Apache..."
apache2-foreground
