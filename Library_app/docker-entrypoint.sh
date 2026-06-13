#!/bin/sh

# Wait for database to be ready
echo "Waiting for MySQL to be ready..."
i=0
while [ $i -lt 30 ]; do
    if nc -z "$DB_HOST" 3306 2>/dev/null; then
        echo "MySQL is ready!"
        break
    fi
    i=$((i + 1))
    echo "Attempt $i/30: MySQL not ready yet..."
    sleep 1
done

# Start PHP-FPM in background
echo "Starting PHP-FPM..."
php-fpm -D

# Start Nginx in foreground
echo "Starting Nginx..."
exec nginx -g 'daemon off;'