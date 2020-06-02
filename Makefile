install-dev: composer-install-dev env-dev

start: start-server watch-logs

composer-install-dev:
	composer install

env-dev:
	echo "APPLICATION_ENV=development" > .env

watch-logs:
	tail -f logs/development.log

start-server:
	php -S 127.0.0.1:8000 -t public/