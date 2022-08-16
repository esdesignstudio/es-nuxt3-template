#!/bin/sh

set -e
. .env
NOW=$(date +'%Y-%m-%d-%H:%M:%S')

# export wordPress database
mv db/default/wp.sql db/backup/wp-"$NOW".sql # backup
docker exec -it ${COMPOSE_PROJECT_NAME}_mysql-maria sh -c "MYSQL_PWD=$WP_MYSQL_ROOT_PASSWORD mysqldump -uroot $WP_MYSQL_DATABASE" > db/default/wp.sql # export to db/default/wp.sql