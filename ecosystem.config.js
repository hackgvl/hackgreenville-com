require('dotenv').config();

const { env } = process;

module.exports = {
  apps: [
    {
      name: 'laravel-serve',
      script: 'artisan',
      args: 'serve',
      interpreter: 'php',
      env: {
        ...env,
        // Environment variables go here, e.g.,
        // APP_ENV: 'production',
        // APP_DEBUG: 'false',
      },
    },
    {
      name: 'laravel-queue',
      script: 'artisan',
      args: 'queue:work',
      interpreter: 'php',
      env: {
        ...env,
        // Environment variables go here, e.g.,
        // APP_ENV: 'production',
        // APP_DEBUG: 'false',
      },
    },
    {
      name: 'vite-dev',
      script: './node_modules/.bin/yarn',
      args: 'dev',
      env: {
        ...env,
        // Environment variables go here, e.g.
        // NODE_ENV: 'development',
      },
    },
  ],
};
