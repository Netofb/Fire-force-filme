# Usa a imagem oficial do PHP com Apache
FROM php:8.1-apache

# Instala as extensões necessárias para PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Habilita módulo de reescrita do Apache (opcional)
RUN a2enmod rewrite

# Altera a porta padrão do Apache para 8080
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf /etc/apache2/sites-enabled/000-default.conf

# Copia os arquivos do seu projeto para o servidor
COPY . /var/www/html/

# Dá permissão para os arquivos
RUN chown -R www-data:www-data /var/www/html

# Expõe a porta 8080
EXPOSE 8080

# Inicializa o Apache
CMD ["apache2-foreground"]
