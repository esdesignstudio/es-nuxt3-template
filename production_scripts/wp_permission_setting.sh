#!/bin/sh
# if [ -f .env ]; then
#     export $(cat .env | grep -v '#' | awk '/=/ {print $1}')
# fi

# 檢查是否已定義必要的變數
# if [ -z "$COMPOSE_PROJECT_NAME" ]; then
#     echo "未定義必要的變數 COMPOSE_PROJECT_NAME。請檢查 .env 檔案。"
#     exit 1
# fi

# docker exec ${COMPOSE_PROJECT_NAME}_wordpress chown -R www-data:www-data wp-content/uploads

if [ $# -ne 1 ]; then
    echo "wp_permission_setting.sh fail - 請提供1個參數：專案名稱"
    exit 1
fi

COMPOSE_PROJECT_NAME=$1


docker exec ${COMPOSE_PROJECT_NAME}_wordpress chown -R www-data:www-data wp-content/uploads