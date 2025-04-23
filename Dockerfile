FROM php:8.1-apache

# Копіюємо файли додатка
COPY . /var/www/html/

# Увімкнення mod_rewrite та права
RUN a2enmod rewrite && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Експортуємо порт
EXPOSE 80