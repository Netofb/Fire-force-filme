FROM php:8.2-apache

# Instala extensões necessárias para PostgreSQL
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql

# Habilita mod_rewrite do Apache
RUN a2enmod rewrite

# Configura o Apache para servir arquivos da pasta public/
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Substitui a configuração padrão do Apache
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copia os arquivos para o container
COPY . /var/www/html/

# Configura permissões
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Expõe a porta 80
EXPOSE 80

# Comando de inicialização
CMD ["apache2-foreground"]