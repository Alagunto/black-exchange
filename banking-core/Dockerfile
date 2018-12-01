FROM php:alpine
LABEL maintainer="alagunto <priah@mail.ru>"

RUN apk update && apk upgrade && apk add bash git

WORKDIR /var/www
RUN touch /tmp/main.db
CMD php /var/www/artisan migrate && cd /var/www/public && php -S 0.0.0.0:9000
EXPOSE 9000
