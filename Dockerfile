FROM serversideup/php:8.3-fpm-nginx

# Install additional packages
USER root
RUN install-php-extensions intl

# Install Node.js and Yarn
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g yarn

# Drop back to our unprivileged user
USER www-data

WORKDIR /var/www/html

# Copy package files first for better caching
COPY --chown=www-data:www-data package.json yarn.lock ./

# Install ALL dependencies (including devDependencies for build tools)
RUN yarn install --frozen-lockfile

COPY --chown=www-data:www-data . /var/www/html

RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction

RUN composer dump-autoload --optimize --classmap-authoritative

# Build frontend assets
RUN yarn build

# Clean up node_modules after build to reduce image size
RUN rm -rf node_modules

# Configure S6 overlay for Laravel scheduler (as root)
USER root

# Create S6 service directory for scheduler (controlled by ENABLE_SCHEDULER env var)
RUN mkdir -p /etc/s6-overlay/s6-rc.d/laravel-scheduler && \
    # Create the run script with conditional check
    echo '#!/command/with-contenv bash' > /etc/s6-overlay/s6-rc.d/laravel-scheduler/run && \
    echo '# Check if scheduler is enabled' >> /etc/s6-overlay/s6-rc.d/laravel-scheduler/run && \
    echo 'if [ "${ENABLE_SCHEDULER}" = "true" ] || [ "${ENABLE_SCHEDULER}" = "1" ]; then' >> /etc/s6-overlay/s6-rc.d/laravel-scheduler/run && \
    echo '    echo "🗓️ Laravel Scheduler is ENABLED"' >> /etc/s6-overlay/s6-rc.d/laravel-scheduler/run && \
    echo '    cd /var/www/html' >> /etc/s6-overlay/s6-rc.d/laravel-scheduler/run && \
    echo '    exec php artisan schedule:work --verbose --no-interaction' >> /etc/s6-overlay/s6-rc.d/laravel-scheduler/run && \
    echo 'else' >> /etc/s6-overlay/s6-rc.d/laravel-scheduler/run && \
    echo '    echo "🗓️ Laravel Scheduler is DISABLED (set ENABLE_SCHEDULER=true to enable)"' >> /etc/s6-overlay/s6-rc.d/laravel-scheduler/run && \
    echo '    # Keep the service running but doing nothing' >> /etc/s6-overlay/s6-rc.d/laravel-scheduler/run && \
    echo '    exec sleep infinity' >> /etc/s6-overlay/s6-rc.d/laravel-scheduler/run && \
    echo 'fi' >> /etc/s6-overlay/s6-rc.d/laravel-scheduler/run && \
    chmod +x /etc/s6-overlay/s6-rc.d/laravel-scheduler/run && \
    # Set service type to longrun
    echo 'longrun' > /etc/s6-overlay/s6-rc.d/laravel-scheduler/type && \
    # Add to user bundle so it starts automatically
    mkdir -p /etc/s6-overlay/s6-rc.d/user/contents.d && \
    touch /etc/s6-overlay/s6-rc.d/user/contents.d/laravel-scheduler

EXPOSE 8080

USER www-data
