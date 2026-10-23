# 1. Môi trường PHP 8.3
FROM php:8.3-cli

# 2. Cài đặt thư viện hệ thống + Node.js (để biên dịch Vite trực tiếp)
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd bcmath

# 3. Cài Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# 4. Sao chép mã nguồn
COPY . .

# 5. Cài đặt các thư viện PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 6. Cài đặt thư viện JS và Build giao diện Vite
RUN npm ci
RUN npm install @vitejs/plugin-vue
RUN npm run build

# 7. Đảm bảo file manifest.json nằm đúng vị trí public/build/manifest.json
RUN mkdir -p public/build
RUN if [ -f public/build/.vite/manifest.json ]; then cp public/build/.vite/manifest.json public/build/manifest.json; fi
RUN find public -name "manifest.json" -exec cp {} public/build/manifest.json \; 2>/dev/null || true

# 8. Xóa sạch cache cấu hình/giao diện Laravel & Khởi chạy server
CMD sh -c "php artisan storage:link --force || true; php artisan config:clear; php artisan view:clear; php artisan route:clear; php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"
