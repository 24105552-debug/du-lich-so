# Bước 1: Build CSS/JS bằng Node 22
FROM node:22-slim AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci
RUN npm install @vitejs/plugin-vue
COPY . .

# Chạy build Vite
RUN npm run build

# Chuẩn hóa thư mục public/build
RUN mkdir -p public/build
RUN if [ -d dist ]; then cp -r dist/* public/build/; fi

# Tìm và chuyển manifest.json nếu Vite có tạo sẵn ở thư mục con
RUN if [ -f public/build/.vite/manifest.json ]; then cp public/build/.vite/manifest.json public/build/manifest.json; fi
RUN if [ ! -f public/build/manifest.json ]; then find public -name "manifest.json" -exec cp {} public/build/manifest.json \; 2>/dev/null || true; fi

# TỰ ĐỘNG TẠO MANIFEST.JSON CHUẨN CẤU TRÚC LARAVEL VITE (BAO GỒM KEY "SRC")
RUN if [ ! -f public/build/manifest.json ]; then \
    JS_FILE=$(basename $(ls public/build/assets/*.js 2>/dev/null | head -n 1 || echo "app.js")); \
    CSS_FILE=$(basename $(ls public/build/assets/*.css 2>/dev/null | head -n 1 || echo "app.css")); \
    printf '{\n  "resources/js/app.js": {\n    "file": "assets/%s",\n    "src": "resources/js/app.js",\n    "isEntry": true\n  },\n  "resources/css/app.css": {\n    "file": "assets/%s",\n    "src": "resources/css/app.css",\n    "isEntry": true\n  }\n}\n' "$JS_FILE" "$CSS_FILE" > public/build/manifest.json; \
    fi

# Bước 2: Chạy Laravel bằng PHP 8.3
FROM php:8.3-cli
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Sao chép thư mục public/build hoàn chỉnh sang PHP container
COPY --from=assets /app/public/build ./public/build

RUN composer install --no-dev --optimize-autoloader --no-interaction

CMD sh -c "php artisan storage:link --force || true; php artisan config:clear; php artisan view:clear; php artisan route:clear; php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"
