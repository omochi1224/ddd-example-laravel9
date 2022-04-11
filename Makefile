init:
	bash init.sh
	@make up
	@make install
	@make refresh_seeder
	@make key
ps:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml -f docker-compose.mail.yml ps
install:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml -f docker-compose.mail.yml exec php composer install -vvv --ignore-platform-reqs
migrate:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml -f docker-compose.mail.yml exec php php artisan migrate
refresh_seeder:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml -f docker-compose.mail.yml exec php php artisan migrate:refresh --seed
up:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml -f docker-compose.mail.yml up -d
build:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml -f docker-compose.mail.yml build
stop:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml -f docker-compose.mail.yml stop
down:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml -f docker-compose.mail.yml down

restart:
	@make down
	@make up

product-build-up:
	docker-compose build
	docker-compose up -d

clear:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml -f docker-compose.mail.yml exec php composer cache:clear

testing:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml -f docker-compose.mail.yml exec php php artisan test

exec_php:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml -f docker-compose.mail.yml exec php bash

tinker:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml -f docker-compose.mail.yml exec php php artisan tinker
key:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml -f docker-compose.mail.yml exec php php artisan key:gen

jwt-key:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml -f docker-compose.mail.yml exec php php artisan jwt:secret
