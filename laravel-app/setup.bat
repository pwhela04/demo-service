@echo off
echo Setting up Laravel User Management API...
echo.

echo 1. Creating .env file with SQLite configuration...
(
echo APP_NAME=Laravel
echo APP_ENV=local
echo APP_DEBUG=true
echo APP_URL=http://localhost
echo.
echo LOG_CHANNEL=stack
echo LOG_DEPRECATIONS_CHANNEL=null
echo LOG_LEVEL=debug
echo.
echo DB_CONNECTION=sqlite
echo DB_DATABASE=database/database.sqlite
echo.
echo BROADCAST_DRIVER=log
echo CACHE_DRIVER=file
echo FILESYSTEM_DRIVER=local
echo QUEUE_CONNECTION=sync
echo SESSION_DRIVER=file
echo SESSION_LIFETIME=120
echo.
echo MEMCACHED_HOST=127.0.0.1
echo.
echo REDIS_HOST=127.0.0.1
echo REDIS_PASSWORD=null
echo REDIS_PORT=6379
echo.
echo MAIL_MAILER=smtp
echo MAIL_HOST=mailhog
echo MAIL_PORT=1025
echo MAIL_USERNAME=null
echo MAIL_PASSWORD=null
echo MAIL_ENCRYPTION=null
echo MAIL_FROM_ADDRESS=null
echo MAIL_FROM_NAME="${APP_NAME}"
echo.
echo AWS_ACCESS_KEY_ID=
echo AWS_SECRET_ACCESS_KEY=
echo AWS_DEFAULT_REGION=us-east-1
echo AWS_BUCKET=
echo AWS_USE_PATH_STYLE_ENDPOINT=false
echo.
echo PUSHER_APP_ID=
echo PUSHER_APP_KEY=
echo PUSHER_APP_SECRET=
echo PUSHER_APP_CLUSTER=mt1
echo.
echo MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
echo MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
) > .env

echo 2. Creating SQLite database file...
if not exist database mkdir database
echo. > database\database.sqlite

echo 3. Generating application key...
php artisan key:generate

echo 4. Clearing configuration cache...
php artisan config:clear
php artisan cache:clear

echo 5. Running migrations...
php artisan migrate

echo.
echo Setup complete! 
echo.
echo To start the development server, run:
echo   php artisan serve
echo.
echo To test the API, run:
echo   php test_api.php
echo.
echo API will be available at: http://localhost:8000/api
echo.
pause 