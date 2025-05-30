# Usa a imagem oficial do PHP com Apache
FROM php:8.1-apache

# Atualiza e instala dependências necessárias para PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Habilita módulo de reescrita do Apache
RUN a2enmod rewrite

# Configura Apache para escutar na porta que o Render define
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf /etc/apache2/sites-enabled/000-default.conf

# Copia os arquivos do projeto
COPY . /var/www/html/

# Permissão para os arquivos
RUN chown -R www-data:www-data /var/www/html

# Expondo porta 8080 (Render usa automaticamente PORT=8080)
EXPOSE 8080

# Inicializa Apache
CMD ["apache2-foreground"]
