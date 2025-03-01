.PHONY: help start-all stop-all build-all restart-all ssh-register ssh-application ssh-mailer

help: ## Show this help message
	@echo 'usage: make [target]'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

start-all: ## Runs all services: RabbitMQ, Register, Application and Mailer
	make -C register start
	make -C application start
	make -C carts start
	make -C products start
	make -C inventory start
	make -C mailer start
	make -C rabbitmq start
	$(MAKE) prepare-all

prepare-all: ## Install dependencies and run migrations in all services
	make -C register prepare
	make -C application prepare
	make -C carts prepare
	make -C products prepare
	make -C inventory prepare
	make -C mailer prepare

stop-all: ## Stops all services: RabbitMQ, Register, Application and Mailer
	make -C rabbitmq stop
	make -C application stop
	make -C register stop
	make -C carts stop
	make -C products stop
	make -C inventory stop
	make -C mailer stop

build-all: ## Build all services: RabbitMQ, Register, Application and Mailer
	make -C register build
	make -C application build
	make -C carts build
	make -C products build
	make -C inventory build
	make -C mailer build

restart-all: ## Restarts all services: RabbitMQ, Register, Application and Mailer
	make -C rabbitmq restart
	make -C application restart
	make -C register restart
	make -C carts restart
	make -C products restart
	make -C inventory restart
	make -C mailer restart

console-application: ## bash into Register Service PHP container
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