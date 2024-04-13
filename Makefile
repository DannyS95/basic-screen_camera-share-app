DOCKER_COMPOSE := docker compose
DOCKER_EXEC := $(DOCKER_COMPOSE) exec

# Replace 'your-container-name' with the actual name of your Docker container
CONTAINER_NAME := screen-share-app

# Makefile target to enter the Docker container and run 'composer install'
php-artisan:
	$(DOCKER_EXEC) $(CONTAINER_NAME) sh -c 'php artisan $(command)'

composer-i:
	$(DOCKER_EXEC) $(CONTAINER_NAME) sh -c 'composer install --no-interaction'

composer-u:
	$(DOCKER_EXEC) $(CONTAINER_NAME) sh -c 'composer update --no-interaction'

npm-i:
	$(DOCKER_EXEC) $(CONTAINER_NAME) sh -c 'npm install  $(command) --no-interaction'

build:
	$(DOCKER_EXEC) $(CONTAINER_NAME) sh -c 'npm run build'

install: composer-i npm-i build

sh:
	$(DOCKER_EXEC) $(CONTAINER_NAME) sh

# Add more targets as needed

.PHONY: composer-install composer-update composer-require
