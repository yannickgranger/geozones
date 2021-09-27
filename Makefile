#!Make

.PHONY: help
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | cut -d: -f2- | sort -t: -k 2,2 | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: up
up:
	docker-compose -f /workspace/v2/docker-compose.dev.yml up

.PHONY: build
build:
	docker-compose -f /workspace/v2/docker-compose.dev.yml build --force-rm

.PHONY: down
down:
	docker-compose -f /workspace/v2/docker-compose.dev.yml down --remove-orphans

.PHONY: geo
geo:
	docker exec -ti geo_fpm zsh

.PHONY: unit-watcher
unit-watcher:
	phpunit-watcher watch

.PHONY: stan
stan:
	phpstan analyze src

.PHONY: cs-fixer
cs-fixer:
	php-cs-fixer fix src

.PHONY: phpunit
phpunit:
	bin/phpunit