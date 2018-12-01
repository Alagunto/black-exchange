FROM php:alpine
LABEL maintainer="alagunto_with_love <priah@mail.ru>"

RUN apk update && apk upgrade && apk add bash git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

RUN touch /tmp/main.db

WORKDIR /var/www

COPY . /var/www
RUN cd /var/www && composer install

CMD php /var/www/artisan migrate && cd /var/www/public && php -S 0.0.0.0:9000
EXPOSE 9000
