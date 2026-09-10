.PHONY: help start-all stop-all build-all restart-all prepare-all setup

help: ## Show this help message
	@echo 'usage: make [target]'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

start-all: ## Runs all services: RabbitMQ, Redis, Register, Application, Carts, Products, Inventory, Mailer, Payments and eSIM
	make -C rabbitmq start
	make -C redis start
	make -C register start
	make -C application start
	make -C carts start
	make -C products start
	make -C inventory start
	make -C mailer start
	make -C payments start
	make -C esim start
	$(MAKE) prepare-all

prepare-all: ## Install dependencies and run migrations in all services
	make -C register prepare
	make -C application prepare
	make -C carts prepare
	make -C products prepare
	make -C inventory prepare
	make -C mailer prepare
	make -C payments prepare
	make -C esim prepare

stop-all: ## Stops all services
	make -C rabbitmq stop
	make -C redis stop
	make -C application stop
	make -C register stop
	make -C carts stop
	make -C products stop
	make -C inventory stop
	make -C mailer stop
	make -C payments stop
	make -C esim stop

build-all: ## Build all services
	make -C register build
	make -C application build
	make -C carts build
	make -C products build
	make -C inventory build
	make -C mailer build
	make -C payments build
	make -C esim build

restart-all: ## Restarts all services
	make -C rabbitmq restart
	make -C redis restart
	make -C application restart
	make -C register restart
	make -C carts restart
	make -C products restart
	make -C inventory restart
	make -C mailer restart
	make -C payments restart
	make -C esim restart

console-application: ## bash into Application Service PHP container
	make -C application console

console-register: ## bash into Register Service PHP container
	make -C register console

console-carts: ## bash into Carts Service PHP container
	make -C carts console

console-products: ## bash into Products Service PHP container
	make -C products console

console-mailer: ## bash into Mailer Service PHP container
	make -C mailer console

console-inventory: ## bash into Inventory Service PHP container
	make -C inventory console

console-payments: ## bash into Payments Service PHP container
	make -C payments console

console-esim: ## bash into eSIM Service PHP container
	make -C esim console


migrations-all: ## bash execute settings for all services
	@echo "Setting up Register migrations..."
	cd register && make migrations
	@echo "Setting up Application migrations..."
	cd application make migrations
	@echo "Setting up Carts migrations..."
	cd carts && make migrations
	@echo "Setting up Inventory migrations..."
	cd inventory && make migrations
	@echo "Setting up Mailer migrations..."
	cd mailer && make migrations
	@echo "Setting up Products migrations..."
	cd products && make migrations
	@echo "Setting up Payments migrations..."
	cd payments && make migrations
	@echo "Setting up eSIM migrations..."
	cd esim && make migrations

setup: ## bash execute settings for all services
	@echo "Setting up Register service..."
	cd register && ./setup.sh
	@echo "Setting up Application service..."
	cd application && ./setup.sh
	@echo "Setting up Carts service..."
	cd carts && ./setup.sh
	@echo "Setting up Inventory service..."
	cd inventory && ./setup.sh
	@echo "Setting up Mailer service..."
	cd mailer && ./setup.sh
	@echo "Setting up Products service..."
	cd products && ./setup.sh
	@echo "Setting up Payments service..."
	cd payments && ./setup.sh
	@echo "Setting up eSIM service..."
	cd esim && ./setup.sh