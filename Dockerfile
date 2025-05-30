# Usa a imagem oficial do PHP com Apache
FROM php:8.1-apache

# Instala as extensões necessárias para PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Habilita módulo de reescrita do Apache (opcional)
RUN a2enmod rewrite

# Copia os arquivos do seu projeto para o servidor
COPY . /var/www/html/

# Dá permissão para os arquivos
RUN chown -R www-data:www-data /var/www/html

# Porta que o Apache usa (Render detecta automaticamente)
EXPOSE 80
