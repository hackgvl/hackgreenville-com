#!/bin/bash

# Move to your project directory # example, but not needed since this is part of the repo
#cd /path/to/your/project

# Pull the latest changes from the repository
echo "Pulling latest changes..."
git pull
echo "Git pull completed."

# Install the JavaScript dependencies
echo "Installing JavaScript dependencies..."
yarn install --frozen-lockfile
echo "JavaScript dependencies installation completed."

# Install the PHP dependencies
echo "Installing PHP dependencies..."
composer install
echo "PHP dependencies installation completed."

# Run database migrations and seed the database
echo "Running database migrations and seeding..."
php artisan migrate
echo "Database migrations and seeding completed."

# Run Laravel optimization commands
echo "Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan icons:cache
php artisan filament:cache-components
echo "Laravel optimization completed."

# Bundle and optimize assets
echo "Beginning Vite build..."
yarn run build
echo "Vite build completed! Assets have been bundled and/or optimized."

# Finish
echo "Deployment completed."
