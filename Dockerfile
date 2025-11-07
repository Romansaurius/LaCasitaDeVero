FROM php:8.1-apache

# Instalar extensiones PHP
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Copiar archivos
COPY . /var/www/html/

# Copiar configuración de Apache
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Permisos
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

WORKDIR /var/www/html

# Render usa la variable PORT
ENV PORT=80
EXPOSE 80

# Configurar Apache para escuchar en el puerto correcto
RUN sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf

CMD ["apache2-foreground"]
