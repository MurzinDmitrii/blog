COMPOSE := docker compose

.DEFAULT_GOAL := start

.PHONY: start up setup install-dev down logs check check-docker help

start: up setup
	@echo "http://localhost:8080"

up:
	$(COMPOSE) up -d --build

setup:
	$(COMPOSE) exec -T web sh -c 'mkdir -p vendor && composer install --no-dev --no-interaction --prefer-dist'
	$(COMPOSE) exec -T web php bin/migrate.php
	$(COMPOSE) exec -T web php bin/seed.php

install-dev:
	$(COMPOSE) exec -T web sh -c 'mkdir -p vendor && composer install --no-interaction --prefer-dist'

down:
	$(COMPOSE) down

logs:
	$(COMPOSE) logs -f web db

check:
	composer install
	composer check

check-docker: install-dev
	$(COMPOSE) exec -T web composer check

help:
	@echo "make              up + composer --no-dev + migrate + seed"
	@echo "make up           контейнеры"
	@echo "make setup        зависимости (prod), migrate, seed"
	@echo "make install-dev  полный composer в контейнере"
	@echo "make check        composer check на хосте"
	@echo "make check-docker install-dev + composer check в контейнере"
	@echo "make down / logs"
