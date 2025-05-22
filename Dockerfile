FROM serversideup/php:8.1-fpm-nginx

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

EXPOSE 8080

USER www-data
