DEV_COMPOSE_FILE=docker-compose-dev.yml
PROD_COMPOSE_FILE=docker-compose.yml

up-dev:
	docker compose -f $(DEV_COMPOSE_FILE) up -d --build
	docker compose -f $(DEV_COMPOSE_FILE) exec php composer i

exec-dev:
	docker compose -f $(DEV_COMPOSE_FILE) exec php bash

monitor-dev:
	docker compose -f $(DEV_COMPOSE_FILE) exec php php spark coaster:monitor

up-prod:
	docker compose -f $(PROD_COMPOSE_FILE) up -d --build
	docker compose -f $(PROD_COMPOSE_FILE) exec php composer i


exec-prod:
	docker compose -f $(PROD_COMPOSE_FILE) exec php bash

monitor-prod:
	docker compose -f $(PROD_COMPOSE_FILE) exec php php spark coaster:monitor