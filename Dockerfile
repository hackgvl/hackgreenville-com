############################################
# Base: shared by local development and production
############################################
FROM serversideup/php:8.5-fpm-nginx AS base

# Install additional packages
USER root
RUN install-php-extensions intl

# Install Node.js and Yarn
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g yarn

# Drop back to our unprivileged user
USER www-data

WORKDIR /var/www/html

############################################
# Development: used by docker-compose. Code is bind-mounted rather than
# baked in, and the production-only s6 services below are left out.
############################################
FROM base AS development

# Match www-data to the host user so files written to the bind mount
# (vendor/, node_modules/, storage/) are owned by you on the host.
ARG USER_ID=1000
ARG GROUP_ID=1000

USER root
RUN install-php-extensions pcov \
    && docker-php-serversideup-set-id www-data ${USER_ID}:${GROUP_ID} \
    && docker-php-serversideup-set-file-permissions --owner ${USER_ID}:${GROUP_ID} --service nginx

USER www-data

############################################
# Production: built by Railway. Keep this stage last so a plain
# `docker build` (no --target) produces it.
############################################
FROM base AS production

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
    # Register on the user bundle so it starts automatically.
    # s6-overlay 3.2.3.2+ (serversideup/php v5) reads
    # /etc/s6-overlay/user-bundles.d. Do not create s6-rc.d/user:
    # rc.init then tries to write that bundle's type file at runtime as
    # www-data, gets "Permission denied", and stops the container.
    mkdir -p /etc/s6-overlay/user-bundles.d/user/contents.d && \
    touch /etc/s6-overlay/user-bundles.d/user/contents.d/laravel-scheduler

# Create S6 oneshot service for deploy init tasks (runs once on container start)
RUN mkdir -p /etc/s6-overlay/s6-rc.d/deploy-init && \
    echo 'oneshot' > /etc/s6-overlay/s6-rc.d/deploy-init/type && \
    printf '#!/command/with-contenv bash\ncd /var/www/html\nexec php artisan deploy:railway\n' \
        > /etc/s6-overlay/s6-rc.d/deploy-init/up && \
    chmod +x /etc/s6-overlay/s6-rc.d/deploy-init/up && \
    mkdir -p /etc/s6-overlay/user-bundles.d/user/contents.d && \
    touch /etc/s6-overlay/user-bundles.d/user/contents.d/deploy-init

EXPOSE 8080

USER www-data
