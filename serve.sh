#!/usr/bin/env bash
set -eou pipefail

# Tail the Laravel log in the background so debug output is visible in the terminal
tail -f storage/logs/laravel.log &
TAIL_PID=$!
trap "kill $TAIL_PID 2>/dev/null" EXIT

TZ=America/New_York APP_ENV=local LOG_CHANNEL=stack LOG_LEVEL=debug APP_DEBUG=true php artisan serve --host 0.0.0.0 --port 4000
