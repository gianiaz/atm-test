FROM php:7.2-cli-alpine3.9
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug-2.6.0 \
    && docker-php-ext-enable xdebug
COPY docker/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

## Utente non-root (no sudo)
RUN adduser -D -u 1000 gianiaz \
    && mkdir -p /home/gianiaz \
    && chown -R gianiaz /home/gianiaz \
## Composer and accounts
    && curl -sS https://getcomposer.org/installer | php -- \
        --install-dir=/usr/local/bin \
        --version=1.8.5 \
        --filename=composer

USER gianiaz

RUN composer global require --no-suggest --no-interaction hirak/prestissimo \
    && rm -rf ~/.composer/cache

WORKDIR /home/gianiaz/atm
