# Usa a imagem oficial do PHP com Apache
FROM php:8.1-apache

# Atualiza e instala dependências necessárias para PostgreSQL e Composer
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Habilita módulo de reescrita do Apache
RUN a2enmod rewrite

# Configura Apache para escutar na porta que o Render define
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf /etc/apache2/sites-enabled/000-default.conf

# Copia APENAS os arquivos necessários para instalar dependências primeiro
COPY composer.json composer.lock /var/www/html/

# Instala as dependências do Composer
RUN cd /var/www/html && \
    composer install --no-dev --optimize-autoloader

# Copia o resto dos arquivos do projeto
COPY . /var/www/html/

# Permissão para os arquivos (ajustado para seu projeto)
RUN chown -R www-data:www-data /var/www/html && \
    [ -d "/var/www/html/storage" ] && chmod -R 755 /var/www/html/storage || true

# Expondo porta 8080
EXPOSE 8080

# Inicializa Apache
CMD ["apache2-foreground"]
# ... (mantenha todas as configurações anteriores até a linha 38)

RUN if [ -f .env.example ]; then \
      cp .env.example /var/www/html/.env; \
    else \
      touch /var/www/html/.env; \
      chmod 644 /var/www/html/.env; \
    fi

# Permissões
RUN chown -R www-data:www-data /var/www/html