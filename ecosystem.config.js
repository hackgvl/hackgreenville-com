require('dotenv').config();

const {
    HOST = '0.0.0.0',
    PORT = 8051,
} = process.env;

module.exports = {
    apps: [
        {
            name: 'hg:watch',
            script: 'node_modules/webpack/bin/webpack.js',

            // Options reference: https://pm2.keymetrics.io/docs/usage/application-declaration/
            args: '--hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js --watch',
            instances: 1,
            autorestart: true,
            watch: false,
            max_memory_restart: '1G',
            env: {
                NODE_ENV: 'development',
                PROXY_HOST: HOST,
                PROXY_PORT: PORT,
            },
        },

        {
            name: 'hg:serve',
            script: 'artisan',
            interpreter: 'php',
            exec_mode: 'fork',

            // Options reference: https://pm2.keymetrics.io/docs/usage/application-declaration/
            args: `serve --host=${HOST} --port=${PORT}`,
            instances: 1,
            autorestart: true,
            watch: false,
            max_memory_restart: '1G',
            env: {
                NODE_ENV: 'development'
            },
        },

        {
            name: 'hg:queue',
            script: 'artisan',
            interpreter: 'php',
            exec_mode: 'fork',

            // Options reference: https://pm2.keymetrics.io/docs/usage/application-declaration/
            args: `queue:work --tries 3`,
            instances: 1,
            autorestart: true,
            watch: false,
            max_memory_restart: '1G',
            env: {
                NODE_ENV: 'development'
            },
        }
    ],
};
