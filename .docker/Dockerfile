FROM php:7.2.6-apache

COPY . /monolith/app
COPY .docker/monolith_vhost.conf /etc/apache2/sites-available/000-default.conf

RUN chown -R www-data:www-data /monolith/app \
&& a2enmod rewrite
