FROM webdevops/php-apache:8.2-alpine

ENV WEB_DOCUMENT_ROOT /app/public

COPY ./ /app
COPY php.ini /opt/docker/etc/php/php.ini

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

WORKDIR /app

RUN composer install
RUN symfony console tailwind:build
