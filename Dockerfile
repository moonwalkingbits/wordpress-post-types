FROM composer:2 AS composer
FROM php:7.4-cli-alpine

USER root

RUN apk --update --no-cache add pcre-dev libzip-dev zip ${PHPIZE_DEPS} && \
    mv /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini && \
    pecl install xdebug-3.1.5 && \
    docker-php-ext-configure zip && \
    docker-php-ext-install zip && \
    docker-php-ext-enable xdebug && \
    apk del pcre-dev ${PHPIZE_DEPS} && \
    echo "[xdebug]" >> /usr/local/etc/php/php.init && \
    echo "xdebug.mode=coverage" >> /usr/local/etc/php/php.ini && \
    echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/php.ini && \
    echo "xdebug.discover_client_host=0" >> /usr/local/etc/php/php.ini && \
    echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/php.ini

COPY --from=composer /usr/bin/composer /usr/local/bin/composer
