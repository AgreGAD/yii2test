all: run

run: deps up ps

deps:
	composer install

up:
	docker-compose up -d

ps:
	docker ps

check: check-styles check-code test

check-code:
	./vendor/bin/phpstan analyse components/ tests/

check-styles:
	./vendor/bin/ecs check --fix components/ tests/

test:
	./vendor/bin/phpunit
