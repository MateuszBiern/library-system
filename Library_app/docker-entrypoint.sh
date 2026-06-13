#!/bin/sh

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

echo "Checking if database needs initialization..."
TABLE_EXISTS=$(mysql -h "$DB_HOST" --ssl-mode=DISABLED -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "SHOW TABLES LIKE 'books';" 2>/dev/null | grep books)

if [ -z "$TABLE_EXISTS" ]; then
    echo "Initializing database..."
    mysql -h "$DB_HOST" --ssl-mode=DISABLED -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < /var/www/html/library.sql
    echo "Database initialized!"
else
    echo "Database already initialized, skipping."
fi

echo "Starting PHP-FPM..."
php-fpm -D

echo "Starting Nginx..."
exec nginx -g 'daemon off;'
