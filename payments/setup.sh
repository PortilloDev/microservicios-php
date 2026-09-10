#!/bin/bash

# Ruta del directorio del servicio
SERVICE_DIR=$(dirname "$0")

# Copiar ficheros .env.dist y docker-compose.yaml.dist si no existen
if [ ! -f "$SERVICE_DIR/.env" ]; then
  cp "$SERVICE_DIR/.env.dist" "$SERVICE_DIR/.env"
fi

if [ ! -f "$SERVICE_DIR/docker-compose.yaml" ]; then
  cp "$SERVICE_DIR/docker-compose.yaml.dist" "$SERVICE_DIR/docker-compose.yaml"
fi

# Levantar los contenedores Docker
docker-compose -f "$SERVICE_DIR/docker-compose.yaml" up -d

# Instalar dependencias de composer
docker-compose -f "$SERVICE_DIR/docker-compose.yaml" run --rm php composer install

# Correr migraciones
docker-compose -f "$SERVICE_DIR/docker-compose.yaml" run --rm php bin/console doctrine:migrations:migrate --no-interaction

# Configurar transports para Symfony Messenger
docker-compose -f "$SERVICE_DIR/docker-compose.yaml" run --rm php bin/console messenger:setup-transports
