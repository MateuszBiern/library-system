# Stage 1: Build frontend (React + Vite)
FROM node:20-alpine AS frontend-builder

WORKDIR /app/frontend

COPY library/frontend/biblioteka/package.json library/frontend/biblioteka/package-lock.json* ./

RUN npm install

COPY library/frontend/biblioteka/ ./

RUN npm run build

# Stage 2: PHP backend + Nginx
FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx mysql-client busybox-extras

# Install PHP extensions for MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Create necessary directories
RUN mkdir -p /var/www/html /var/run/nginx /var/log/nginx

WORKDIR /var/www/html

# Copy backend PHP files
COPY library/backend/bibliotekaPHP/ ./api/

# Copy built frontend from builder stage
COPY --from=frontend-builder /app/frontend/dist ./

# Copy nginx config
COPY nginx.conf /etc/nginx/nginx.conf

# Copy startup script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose ports
EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s --retries=3 \
    CMD netstat -tlnp | grep -q ':80' || exit 1

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
