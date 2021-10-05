#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

mkdir -p /srv/app/var/cache /srv/app/var/log /srv/app/src/Infrastructure/Symfony5/Storage/Cache /srv/app/src/Infrastructure/Symfony5/Storage/Files
setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX /srv/app/var/cache
setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX /srv/app/var/cache
setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX /srv/app/var/log
setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX /srv/app/var/log
setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX /srv/app/src/Infrastructure/Symfony5/Storage
setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX /srv/app/src/Infrastructure/Symfony5/Storage

  if [ -d /srv/app/src/Infrastructure/Translation/ ]; then
      setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX /srv/app/src/Infrastructure/Translation
      setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX /srv/app/src/Infrastructure/Translation/
  fi


if [ "$1" = 'php-fpm' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then

	if [ "$APP_ENV" != 'dev' ]; then
	    # Remove app_dev.php entrypoint
      rm -rf /composer
	fi

  # The first time volumes are mounted, the vendors needs to be reinstalled
  if [ ! -d vendor/ ]; then
      composer install --prefer-dist --no-progress --no-interaction --no-dev
  fi

	if [ "$APP_ENV" = 'dev' ]; then
	  mkdir -p /composer
    setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX /composer
    setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX /composer

    setfacl -R -m u:"$USER":rwX -m u:"$(whoami)":rwX /srv/app/cache
    setfacl -dR -m u:"$USER":rwX -m u:"$(whoami)":rwX /srv/app/cache
    setfacl -R -m u:"$USER":rwX -m u:"$(whoami)":rwX /srv/app/log
    setfacl -dR -m u:"$USER":rwX -m u:"$(whoami)":rwX /srv/app/log

    chown -R "${UID}":"${GID}" /srv/app /composer /home/"$USER"
    composer install --prefer-dist --no-progress --no-interaction
	fi
fi


exec docker-php-entrypoint "$@"
