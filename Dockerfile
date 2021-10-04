ARG PHP_VERSION=7.4.8

FROM busybox as app-builder
ARG APP_ENV
ARG INSTALL_DIR
ARG PROJECT_DIR

WORKDIR /srv/${INSTALL_DIR}

COPY ${PROJECT_DIR}/composer.json ${PROJECT_DIR}/composer.lock ${PROJECT_DIR}/symfony.lock ${PROJECT_DIR}/.env ./
COPY ${PROJECT_DIR}/bin ./bin
COPY ${PROJECT_DIR}/config ./config
COPY ${PROJECT_DIR}/public ./public
COPY ${PROJECT_DIR}/src ./src


FROM php:${PHP_VERSION}-fpm-alpine as php-stage
ARG INSTALL_DIR

WORKDIR /srv/${INSTALL_DIR}

RUN set -eux \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && apk add --no-cache \
    icu-dev \
    libpq \
    libzip \
    libzip-dev \
    postgresql-dev

RUN pecl install xdebug \
    && docker-php-ext-install bcmath intl opcache pdo pdo_pgsql zip \
#    && docker-php-ext-enable bcmath intl opcache pdo pdo_pgsql zip \
    && apk del .build-deps


FROM php:${PHP_VERSION}-fpm-alpine as composer-stage

ARG INSTALL_DIR
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/composer
ENV COMPOSER_HOME=/composer
ARG ENTRYPOINT_FILE

WORKDIR /srv/${INSTALL_DIR}

RUN set -eux; \
    apk add --no-cache \
    acl \
    fcgi

COPY .docker/php/${ENTRYPOINT_FILE} /usr/local/bin/docker-entrypoint.sh
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY --from=php-stage /usr /usr
COPY --from=app-builder /srv /srv
COPY ./.docker/php/conf.d/symfony.prod.ini $PHP_INI_DIR/conf.d/symfony.ini
\
RUN ln -s $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini \
    && mkdir /composer \
    && set -eux \
    && composer install --classmap-authoritative --prefer-dist --no-dev --no-scripts --no-progress \
    && composer dump-autoload --classmap-authoritative --no-dev \
    && chmod +x bin/console \
    && sync \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

FROM php:${PHP_VERSION}-fpm-alpine as dev-stage
ARG INSTALL_DIR
ARG APP_ENV
ARG GID
ARG UID

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/composer
ENV APP_ENV=${APP_ENV}
ENV GID="${GID}"
ENV UID="${UID}"
ENV ZSH_THEME powerlevel10k
ENV USER dockeruser
ENV TZ=Europe/Paris

WORKDIR /srv/${INSTALL_DIR}

RUN set -eux; \
    apk add --no-cache \
    acl \
    zsh \
    git \
    gettext \
    tzdata

COPY --from=composer-stage /usr /usr
COPY --from=composer-stage /srv /srv

RUN addgroup -S dockeruser -g "${GID}" \
    && adduser \
    --disabled-password \
    --gecos "" \
    --shell "/bin/zsh" \
    --home "/home/$USER" \
    --ingroup "dockeruser" \
    --uid "${UID}" \
    "dockeruser"

RUN cp /usr/share/zoneinfo/$TZ /etc/localtime  \
    && echo $TZ > /etc/timezone \
    && apk del tzdata

COPY .zshrc /home/"$USER"/.zshrc

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]