# Usar una imagen base de PHP
FROM php:8.2.12-fpm

# Instalar las dependencias necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install zip pdo_mysql

# Copiar los archivos de la aplicación al contenedor
COPY . /var/www/html

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar las dependencias de Composer
RUN composer install

# Ejecutar las migraciones de la base de datos
RUN php artisan migrate --force

# Instalar Laravel Passport
RUN composer require laravel/passport

# Generar las claves de Passport
RUN php artisan passport:install --force

# Ejecutar los seeders
RUN php artisan db:seed

# Exponer el puerto 8000 para el servidor PHP-FPM (no es necesario si vas a usar php artisan serve)
# EXPOSE 8000

# No es necesario levantar el servidor con php artisan serve si estás utilizando php-fpm

# CMD ["php", "artisan", "serve", "--host=0.0.0.0"]

# Cambiado para permitir el contenedor ejecutar de manera continua sin detenerse.
CMD ["php-fpm"]
