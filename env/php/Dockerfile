FROM php:8.5-fpm AS base

ARG LOCAL_USER_UID=1000
ARG LOCAL_USER_GID=1000
ARG LOCAL_USER=kondy

RUN apt-get update && apt-get install -y \
      apt-utils curl libpq-dev libpng-dev libzip-dev zip unzip git && \
      docker-php-ext-install pdo_pgsql pdo_mysql bcmath gd zip pcntl mysqli && \
      apt-get clean && rm -rf /var/lib/apt/lists/*

RUN groupadd -g ${LOCAL_USER_GID} ${LOCAL_USER} || true && \
    useradd -u ${LOCAL_USER_UID} -g ${LOCAL_USER_GID} -m -s /bin/bash ${LOCAL_USER} || true

COPY ./env/php/php.ini /usr/local/etc/php/conf.d/php.ini

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin && \
    ln -s /usr/local/bin/composer /usr/bin/composer || true

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions http sockets redis

WORKDIR /var/www/

RUN chown -R ${LOCAL_USER}:${LOCAL_USER} /var/www

FROM base AS production
USER ${LOCAL_USER}

FROM base AS development
RUN install-php-extensions xdebug
COPY ./env/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

USER ${LOCAL_USER}
