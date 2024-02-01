.DEFAULT_GOAL := help
SHELL = /bin/bash

NO_COLOR = \x1b[0m
INFO_COLOR = \x1b[34m
SUCCESS_COLOR = \x1b[32m
WARNING_COLOR = \x1b[33m
ERROR_COLOR = \x1b[31m

ifneq (,$(wildcard ./docker/.env))
    include ./docker/.env
	export $(shell sed 's/=.*//' ./docker/.env)
endif

UC = $(shell echo '$1' | tr '[:lower:]' '[:upper:]')

PLATFORM := $(shell uname)
LINUX_PLATFORM = Linux
MAC_PLATFORM = Darwin

SED_COMMAND = sed -i
ifeq ($(PLATFORM),$(MAC_PLATFORM))
SED_COMMAND = sed -i ''
endif

DOCKER_COMPOSE_BASE := docker-compose -f ./docker-compose.yml

build: ## Build containers
	$(DOCKER_COMPOSE_BASE) build

create: build ## Create containers
	$(DOCKER_COMPOSE_BASE) up --no-start

start: ## Start containers
	$(DOCKER_COMPOSE_BASE) up -d  --remove-orphans
	docker exec wea_shop_mysql mysql -uroot -proot -e "GRANT ALL PRIVILEGES ON *.* TO 'weaShop'@'%'; FLUSH PRIVILEGES;"

stop: ## Stop containers
	$(DOCKER_COMPOSE_BASE) stop

logs: ## tails docker logs
	$(DOCKER_COMPOSE_BASE) logs -f

pull: ## pull docker images
	$(DOCKER_COMPOSE_BASE) pull

down: ## Stops containers and removes oa containers, networks, volumes, and images created by up
	$(DOCKER_COMPOSE_BASE) down

sh_nginx: ## Connect to nginx container
	docker exec -it -u `id -u ${USER}` wea_shop_nginx sh

sh_php_fpm: ## Connect to php_fpm container
	docker exec -it -u `id -u root` wea_shop_php_fpm zsh

var-%:
	@: $(if $(value $*),,$(error $* is undefined))

help: ## This help dialog.
	@awk -F ':|##' '/^[^\t].+?:.*?##/ {printf "\033[36m%-30s\033[0m %s\n", $$1, $$NF}' $(MAKEFILE_LIST)

phpstan:
	docker rm phpstan || true
	docker run  --name phpstan -it -w /app/ -v ${PWD}:/app/ jakzal/phpqa:1.66-php8.1 phpstan analyse --level 3 /app/app/
	docker rm phpstan || true

phpmd:
	docker rm phpmd || true
	docker run  --name phpmd -it -v ${PWD}:/app/ sider/runner_phpmd ls /app/app/
	docker run  --name phpmd -it -v ${PWD}:/app/ sider/runner_phpmd phpmd src/ text codesize
	docker run  --name phpmd -it -v ${PWD}:/app/ sider/runner_phpmd phpmd src/ text controversial
	docker run  --name phpmd -it -v ${PWD}:/app/ sider/runner_phpmd phpmd src/ text design
	docker run  --name phpmd -it -v ${PWD}:/app/ sider/runner_phpmd phpmd src/ text unusedcode
	docker rm phpmd || true

phpcs:
	docker rm phpcs || true
	docker run --name phpcs -it -v ${PWD}:/app/ cytopia/phpcs:latest --error-severity=5 --warning-severity=8 --standard=PSR12 --extensions=php -p /app/app/
	docker rm phpcs || true

phpcbf:
	docker rm phpcbf || true
	docker run --name phpcbf -it -v ${PWD}:/app/ cytopia/phpcbf:latest --error-severity=5 --warning-severity=8 --standard=PSR12 --extensions=php -p /app/app/
	docker rm phpcbf || true

phpmd-mn:
	docker rm phpqa || true
	docker run --init  --name phpqa -it -w /app/ -v ${PWD}:/app/ jakzal/phpqa phpmnd /app/app/
	docker rm phpqa || true

phpmd-cc:
	docker rm phpmd-cc || true
	docker run --init --name phpmd-cc -it -w /app/ -v ${PWD}:/app/ jakzal/phpqa phpmd /app/app/ text cleancode
	docker rm phpmd-cc || true

phpmd-cs:
	docker rm phpmd-cs || true
	docker run --init --name phpmd-cs -it -w /app/ -v ${PWD}:/app/ jakzal/phpqa phpmd /app/app/ text codesize
	docker rm phpmd-cs || true

phpmd-cv:
	docker rm phpmd-cv || true
	docker run --init --name phpmd-cv -it -w /app/ -v ${PWD}:/app/ jakzal/phpqa phpmd /app/app/ text controversial
	docker rm phpmd-cv || true

phpmd-ds:
	docker rm phpmd-ds || true
	docker run --init --name phpmd-ds -it -w /app/ -v ${PWD}:/app/ jakzal/phpqa phpmd /app/app/ text design
	docker rm phpmd-ds || true

phpmd-nm:
	docker rm phpmd-nm || true
	docker run --init --name phpmd-nm -it -w /app/ -v ${PWD}:/app/ jakzal/phpqa phpmd /app/app/ text naming
	docker rm phpmd-nm || true

phpmd-uc:
	docker rm phpmd-uc || true
	docker run --init --name phpmd-uc -it -w /app/ -v ${PWD}:/app/ jakzal/phpqa phpmd /app/app/ text unusedcode
	docker rm phpmd-uc || true

quality:
	make phpmd-mn || true
	make phpmd-cc || true
	make phpmd-cs || true
	make phpmd-cv || true
	make phpmd-ds || true
	make phpmd-nm || true
	make phpmd-uc || true
	make phpstan || true