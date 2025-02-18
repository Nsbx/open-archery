FROM webdevops/php-apache:8.2-alpine

ENV WEB_DOCUMENT_ROOT /app/public

COPY ./ /app
COPY php.ini /opt/docker/etc/php/php.ini

WORKDIR /app

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

RUN composer install
RUN symfony console tailwind:build
RUN symfony console asset-map:compile
