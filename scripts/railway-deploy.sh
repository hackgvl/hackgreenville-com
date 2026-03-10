#!/bin/bash
# Install the JavaScript dependencies
echo "Installing JavaScript dependencies..."
yarn install --frozen-lockfile
echo "JavaScript dependencies installation completed."

# Run Laravel optimization commands
echo "Configuring Laravel"
php artisan icons:cache
php artisan filament:cache-components
php artisan cloudflare:cache:purge
echo "Laravel optimization completed."

# Bundle and optimize assets
echo "Beginning Vite build..."
yarn run build
echo "Vite build completed! Assets have been bundled and/or optimized."

# Finish
echo "Deployment completed."
