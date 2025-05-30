# Usa a imagem do PHP com Apache
FROM php:8.1-apache

# Instala as extensões necessárias para PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Habilita URL amigável (opcional, se quiser usar .htaccess futuramente)
RUN a2enmod rewrite

# Copia todos os arquivos do projeto para o servidor
COPY . /var/www/html/

# Dá permissão aos arquivos
RUN chown -R www-data:www-data /var/www/html
