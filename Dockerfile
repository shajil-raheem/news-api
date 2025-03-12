FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    cron \
    unzip \
    curl \
    git \
    libpq-dev \
    libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY crontab /etc/cron.d/laravel-cron
RUN chmod 0644 /etc/cron.d/laravel-cron && crontab /etc/cron.d/laravel-cron

CMD ["sh", "-c", "cron && php-fpm"]
