#!/bin/bash

echo "Running migrations..."
php artisan migrate --force

echo "Clearing cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Deployment complete!"
