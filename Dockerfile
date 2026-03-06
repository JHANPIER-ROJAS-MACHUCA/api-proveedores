FROM php:8.2-apache

# Deshabilitar MPM event y habilitar MPM prefork (evita conflicto)
RUN a2dismod mpm_event && a2enmod mpm_prefork rewrite

# Copiar archivos PHP al servidor
COPY . /var/www/html/

# Permisos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
