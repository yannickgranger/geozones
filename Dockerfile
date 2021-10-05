ARG PHP_VERSION=8.0.9
ARG NGINX_VERSION=1.20
ARG INSTALL_DIR=app

FROM alpine as app-builder
ARG APP_ENV
ARG APP_DIR
ARG INSTALL_DIR

WORKDIR /srv/${INSTALL_DIR}

COPY ${APP_DIR}/composer.json ${APP_DIR}/composer.lock ${APP_DIR}/symfony.lock ${APP_DIR}/.env ./
COPY ${APP_DIR}/bin ./bin
COPY ${APP_DIR}/config ./config
COPY ${APP_DIR}/public ./public
COPY ${APP_DIR}/src ./src


FROM php:${PHP_VERSION}-fpm-alpine AS symfony_php
ARG APP_ENV
ARG PHP_CONF_DIR
ARG INSTALL_DIR
ARG ENTRYPOINT_FILE
ARG TZ
ENV TZ=${TZ}

# persistent / runtime deps
RUN apk add --no-cache \
		acl \
		fcgi \
		file \
		gettext \
		git \
		gnu-libiconv \
        tzdata \
	;

ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so

ARG APCU_VERSION=5.1.20
RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		icu-dev \
		libzip-dev \
        postgresql-dev \
		zlib-dev \
	; \
	\
	docker-php-ext-configure zip; \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql; \
	docker-php-ext-install -j$(nproc) \
		intl \
		zip \
        pdo \
        pdo_pgsql \
	; \
	pecl install \
		apcu-${APCU_VERSION} \
	; \
	pecl clear-cache; \
	docker-php-ext-enable \
		apcu \
		opcache \
        pdo \
        pdo_pgsql \
	; \
	\
	runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)"; \
	apk add --no-cache --virtual .phpexts-rundeps $runDeps; \
	\
	apk del .build-deps

HEALTHCHECK --interval=10s --timeout=3s --retries=3 CMD ["docker-healthcheck"]
COPY ${PHP_CONF_DIR}/php-fpm.d/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf
COPY --from=app-builder /srv /srv

WORKDIR /srv/${INSTALL_DIR}

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY ${PHP_CONF_DIR}/docker-healthcheck.sh /usr/local/bin/docker-healthcheck
COPY ${PHP_CONF_DIR}/${ENTRYPOINT_FILE} /usr/local/bin/docker-entrypoint.sh
COPY ${PHP_CONF_DIR}/conf.d/symfony.prod.ini $PHP_INI_DIR/conf.d/symfony.ini

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN ln -s $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini ; \
    set -eux; \
	mkdir -p var/cache var/log; \
	composer install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction; \
	composer dump-autoload --classmap-authoritative --no-dev; \
	composer symfony:dump-env prod; \
	composer run-script --no-dev post-install-cmd; \
	chmod +x bin/console /usr/local/bin/docker-entrypoint.sh /usr/local/bin/docker-healthcheck; \
    cp /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone \
    && apk del tzdata; \
    sync

VOLUME /srv/app/var
VOLUME /var/run/php

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]


FROM nginx:${NGINX_VERSION}-alpine AS symfony_nginx

ARG INSTALL_DIR
ARG NGINX_CONF_DIR
WORKDIR /srv/${INSTALL_DIR}

COPY --from=app-builder /srv/${INSTALL_DIR}/public /srv/${INSTALL_DIR}/public
COPY ${NGINX_CONF_DIR}/conf.d/default.conf /etc/nginx/conf.d/default.conf