# Используем PHP 8.2 с FPM
FROM php:8.2-fpm

# Устанавливаем системные зависимости
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    libsqlite3-dev \
    nodejs \
    npm \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        zip \
        exif \
        pcntl \
        gd \
        intl \
        pdo_sqlite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Устанавливаем рабочую директорию
WORKDIR /var/www/html

# Копируем файлы проекта
COPY . .

# Устанавливаем зависимости PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Устанавливаем зависимости Node.js и собираем assets
RUN npm ci && npm run build

# Создаем необходимые директории
RUN mkdir -p \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chmod -R 777 storage \
    && chmod -R 777 bootstrap/cache

# Создаем скрипт запуска
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "📦 Waiting for database to be ready..."\n\
sleep 3\n\
\n\
echo "🔨 Creating database file if not exists..."\n\
touch $DB_DATABASE\n\
chmod 666 $DB_DATABASE\n\
\n\
echo "⚙️ Running migrations..."\n\
php artisan migrate --force\n\
\n\
echo "🌱 Running seeders..."\n\
# Проверяем, есть ли уже данные в таблице users\n\
USER_COUNT=$(sqlite3 $DB_DATABASE "SELECT COUNT(*) FROM users;" 2>/dev/null || echo "0")\n\
if [ "$USER_COUNT" -eq "0" ]; then\n\
    echo "🌱 Database is empty. Running seeders..."\n\
    php artisan db:seed --force\n\
    echo "✅ Seeders completed successfully!"\n\
else\n\
    echo "✅ Database already has data. Skipping seeders."\n\
fi\n\
\n\
echo "🔗 Creating storage link..."\n\
php artisan storage:link\n\
\n\
echo "🚀 Caching configuration..."\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
echo "⚡ Starting queue worker..."\n\
php artisan queue:work --daemon &\n\
\n\
echo "🎯 Application is ready! Starting server..."\n\
php artisan serve --host=0.0.0.0 --port=$PORT\n\
' > /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

# Открываем порт
EXPOSE 8000

# Запускаем приложение
CMD ["/usr/local/bin/start.sh"]