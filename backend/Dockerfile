# Bước 1: build CSS/JS bằng Node 22
FROM node:22-slim AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci
RUN npm install @vitejs/plugin-vue
COPY . .
RUN mkdir -p public/build
RUN npm run build
# Copy file manifest ra đúng thư mục public/build cho Laravel
RUN cp public/build/.vite/manifest.json public/build/manifest.json 2>/dev/null || true

# Bước 2: chạy Laravel bằng PHP
FROM php:8.3-cli
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .
# Sao chép chính xác toàn bộ thư mục build sang PHP container
COPY --from=assets /app/public/build /app/public/build

RUN composer install --no-dev --optimize-autoloader --no-interaction

CMD sh -c "php artisan storage:link --force || true; php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"
