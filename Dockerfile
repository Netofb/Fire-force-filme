FROM php:8.1-apache

# Instala dependências
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Ativa mod_rewrite
RUN a2enmod rewrite

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia código
COPY . /var/www/html/

# Instala dependências PHP do projeto
WORKDIR /var/www/html/
RUN composer install --no-dev --optimize-autoloader

# Permissões
RUN chown -R www-data:www-data /var/www/html

# Inicia Apache
CMD ["apache2-foreground"]
