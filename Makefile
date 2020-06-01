install-dev: composer-install-dev env-dev

composer-install-dev:
	composer install

env-dev:
	echo "APPLICATION_ENV=development" > .env

server:
	cd public && php -S 127.0.0.1:8000