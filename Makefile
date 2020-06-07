start:
	php artisan serve --host 0.0.0.0

setup:
	install
	env-prepare
	key
	db-prepare

install:
	composer install
	npm install

db-prepare:
	php artisan migrate --seed

env-prepare:
	cp -n .env.example .env || true

sqlite-prepare:
	touch database/database.sqlite

key:
	php artisan key:gen --ansi

watch:
	npm run watch

migrate:
	php artisan migrate

console:
	php artisan tinker

log:
	tail -f storage/logs/laravel.log

test:
	php artisan test

deploy:
	git push heroku master

lint:
	composer phpcs

lint-fix:
	composer phpcbf
