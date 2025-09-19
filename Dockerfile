# Dockerfile
FROM php:8.2-apache

# Instala extensões necessárias para PostgreSQL
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql

# Habilita mod_rewrite do Apache
RUN a2enmod rewrite

# Copia os arquivos para o container
COPY . /var/www/html/

# Define o diretório de trabalho
WORKDIR /var/www/html

# Configura permissões
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Expõe a porta 80
EXPOSE 80

# Comando de inicialização
CMD ["apache2-foreground"]