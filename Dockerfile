FROM php:8.1-apache

# Instala dependências do PostgreSQL e SSL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    openssl \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && docker-php-ext-enable pdo_pgsql

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# Configurações do Apache
RUN a2enmod rewrite
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf /etc/apache2/sites-enabled/000-default.conf

# Instala dependências
COPY composer.json composer.lock /var/www/html/
RUN cd /var/www/html && \
    composer install --no-dev --optimize-autoloader

# Copia o código
COPY . /var/www/html/

# Permissões
RUN chown -R www-data:www-data /var/www/html

EXPOSE 8080
CMD ["apache2-foreground"]