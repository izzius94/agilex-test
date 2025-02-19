.RECIPEPREFIX =
.DEFAULT_GOAL := help

.PHONY: help
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

install: ## Install the project
	cp --update=none .env.example .env || true
	cp --update=none code/.env.example code/.env || true
	docker compose create
	docker compose run --rm fpm composer install
	docker compose run --rm fpm php artisan migrate
	docker compose run --rm fpm php artisan db:seed

start: ## Start the project
	docker compose start

phpstan: ## Run phpstan
	docker compose run --rm fpm  ./vendor/bin/phpstan analyse --memory-limit=2G

pint: ## Run laravel pint
	docker compose run --rm fpm  ./vendor/bin/pint -v

test: ## Run tests
	docker compose run --rm -e DB_USERNAME=root fpm php artisan test --parallel --processes=4

coverage: ## Run coverage
	docker compose run --rm -e XDEBUG_MODE=coverage fpm ./vendor/bin/phpunit --configuration ./phpunit.xml --coverage-html ./coverage

sonar: ## Run sonar scanner
	docker compose run --rm sonar