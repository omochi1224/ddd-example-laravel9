COMPOSE=docker compose -f docker-compose.yml -f docker-compose.dev.yml -f docker-compose.mail.yml

init:
	bash init.sh
	@make up
	@make install
	@make refresh_seeder
	@make key
ps:
	$(COMPOSE) ps
install:
	$(COMPOSE) exec php composer install -vvv
migrate:
	$(COMPOSE) exec php php artisan migrate
refresh_seeder:
	$(COMPOSE) exec php php artisan migrate:refresh --seed
up:
	$(COMPOSE) up -d
build:
	$(COMPOSE) build
stop:
	$(COMPOSE) stop
down:
	$(COMPOSE) down

restart:
	@make down
	@make up

product-build-up:
	docker compose build
	docker compose up -d

clear:
	$(COMPOSE) exec php composer cache:clear

test:
	$(COMPOSE) exec php php artisan test

app:
	$(COMPOSE) exec php bash

tinker:
	$(COMPOSE) exec php php artisan tinker
key:
	$(COMPOSE) exec php php artisan key:generate

jwt-key:
	$(COMPOSE) exec php php artisan jwt:secret
