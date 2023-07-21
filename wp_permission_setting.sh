#!/bin/sh

docker exec ${COMPOSE_PROJECT_NAME}_wordpress chown -R www-data:www-data wp-content/