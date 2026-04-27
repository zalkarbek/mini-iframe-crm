#!/usr/bin/env bash
set -euo pipefail

APP_DIR="/var/www/html"
INIT_MARKER="$APP_DIR/storage/app/.docker-initialized"

cd "$APP_DIR"

mkdir -p storage/app bootstrap/cache database
touch database/database.sqlite

git config --global --add safe.directory "$APP_DIR" || true

if [ ! -f .env ]; then
  cp .env.example .env
fi

php -r '
$envPath = ".env";
$pairs = [
    "APP_URL" => "http://127.0.0.1:8000",
    "DB_CONNECTION" => "sqlite",
    "DB_DATABASE" => "/var/www/html/database/database.sqlite",
    "QUEUE_CONNECTION" => "database",
    "SESSION_DRIVER" => "database",
    "CACHE_STORE" => "database",
];
$content = file_get_contents($envPath);
foreach ($pairs as $key => $value) {
    if (preg_match("/^{$key}=.*/m", $content)) {
        $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
    } else {
        $content .= PHP_EOL."{$key}={$value}";
    }
}
file_put_contents($envPath, $content);
'

if [ ! -f vendor/autoload.php ]; then
  composer install --no-interaction
fi

if [ ! -x node_modules/.bin/vite ]; then
  bun install --no-progress
fi

if ! grep -q '^APP_KEY=base64:' .env; then
  php artisan key:generate --force
fi

php artisan storage:link --relative || true

if [ ! -f "$INIT_MARKER" ]; then
  php artisan migrate:fresh --seed --force
  touch "$INIT_MARKER"
else
  php artisan migrate --force
fi

exec composer run dev-docker
