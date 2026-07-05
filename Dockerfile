FROM php:8.3-cli

RUN docker-php-ext-install pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

COPY docker/entrypoint.sh /usr/local/bin/app-entrypoint
RUN chmod +x /usr/local/bin/app-entrypoint

WORKDIR /var/www/html/public

EXPOSE 8080

ENTRYPOINT ["app-entrypoint"]
CMD ["php", "-d", "include_path=.:../app/router", "-S", "0.0.0.0:8080"]
