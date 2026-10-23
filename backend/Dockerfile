# Bước 1: Build CSS/JS bằng Node 22 (Chỉ dùng để build giao diện)
FROM node:22-slim AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci
RUN npm install @vitejs/plugin-vue
COPY . .
RUN npm run build

# Bước 2: Chạy Laravel bằng PHP 8.3
FROM php:8.3-cli
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Sao chép thư mục public/build từ bước assets sang PHP
COPY --from=assets /app/public/build ./public/build

# Đảm bảo file manifest.json luôn xuất hiện đúng vị trí Laravel yêu cầu
RUN if [ -d public/build/.vite ]; then cp public/build/.vite/manifest.json public/build/manifest.json 2>/dev/null || true; fi

RUN composer install --no-dev --optimize-autoloader --no-interaction

CMD sh -c "php artisan storage:link --force || true; php artisan config:clear; php artisan view:clear; php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"
