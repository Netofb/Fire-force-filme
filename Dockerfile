# Usa a imagem oficial do PHP com Apache
FROM php:8.1-apache

# Atualiza e instala dependências
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# Configurações do Apache
RUN a2enmod rewrite
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf /etc/apache2/sites-enabled/000-default.conf

# Instala dependências (com cache otimizado)
COPY composer.json composer.lock /var/www/html/
RUN cd /var/www/html && \
    composer install --no-dev --optimize-autoloader

# Copia o código e configura ambiente
COPY . /var/www/html/

# Configura .env seguro (apenas para desenvolvimento)
RUN if [ -f .env.example ]; then \
      cp .env.example /var/www/html/.env; \
    else \
      touch /var/www/html/.env; \
    fi && \
    chmod 644 /var/www/html/.env && \
    chown -R www-data:www-data /var/www/html && \
    [ -d "/var/www/html/storage" ] && chmod -R 755 /var/www/html/storage || true

EXPOSE 8080
CMD ["apache2-foreground"]