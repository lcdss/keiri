FROM php:alpine

LABEL maintainer="Lucas Cândido de Souza Silva <lcssbr@gmail.com>"

RUN apk add --no-cache wget ca-certificates && \
    wget -O /etc/apk/keys/phpearth.rsa.pub https://repos.php.earth/alpine/phpearth.rsa.pub && \
    echo "https://repos.php.earth/alpine/v3.8" >> /etc/apk/repositories && \
    apk update && apk upgrade && \
    apk add --no-cache php7.3-dev php7.3-composer freetype-dev libjpeg-turbo-dev libpng-dev libzip-dev \
      openssh autoconf g++ imagemagick-dev libtool make pcre-dev yarn git python && \
    pecl install imagick && \
    docker-php-ext-enable imagick && \
    docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-install zip pdo_mysql gd && \
    apk del autoconf g++ libtool make pcre-dev

RUN adduser -D keiri keiri

USER keiri

RUN composer g require psy/psysh:@stable hirak/prestissimo
