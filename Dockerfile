# Imagem base com PHP e Apache
FROM php:8.1-apache

# Instala dependências do PostgreSQL e ferramentas
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Ativa o mod_rewrite do Apache
RUN a2enmod rewrite

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia o código para a imagem
COPY . /var/www/html/

# Permissões
RUN chown -R www-data:www-data /var/www/html

# Expõe a porta padrão do Apache (Railway cuida do roteamento)
EXPOSE 8000

# Define a variável de ambiente para Apache rodar na porta 8000
ENV PORT=8000

# Altera configuração da porta no Apache
RUN sed -i "s/80/8000/g" /etc/apache2/ports.conf /etc/apache2/sites-enabled/000-default.conf

# Comando de inicialização
CMD ["apache2-foreground"]
