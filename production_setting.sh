#!/bin/sh

# 引入.env檔參數
if [ -f .env ]; then
    export $(cat .env | grep -v '#' | awk '/=/ {print $1}')
fi

# 檢查是否已定義必要的變數
if [ -z "$COMPOSE_PROJECT_NAME" ] || [ -z "$WP_URL" ]; then
    echo "未定義必要的變數: COMPOSE_PROJECT_NAME PRODUCTION_DOMAIN WP_URL。請檢查 .env 檔案。"
    exit 1
fi

PRODUCTION_DOMAIN=$(echo "$WP_URL" | sed 's/.*\/\///')

echo "PRODUCTION_DOMAIN = $PRODUCTION_DOMAIN"

# 檢查Docker是否已安裝
if ! command -v docker &> /dev/null; then
    echo "********** Docker尚未安裝，開始安裝... **********"
    # 執行安裝腳本
    sudo sh production_scripts/docker_install.sh
else
    echo "Docker已安裝!!"
fi

# 檢查Nginx是否已安裝
if ! command -v nginx &> /dev/null; then
    echo "********** Nginx尚未安裝，開始安裝... **********"
    # 執行安裝腳本
    sudo sh production_scripts/nginx_install.sh
else
    echo "Nginx已安裝!!"
fi

# 建立nginx設定檔
echo "********** 建立nginx設定檔 **********"
sudo sh production_scripts/nginx_setting.sh $COMPOSE_PROJECT_NAME $PRODUCTION_DOMAIN

# 修改wp.sql中的連結
echo "********** 修改wp.sql中的連結設定 **********"
sudo sh production_scripts/repleace_db_url.sh db/production/wp.sql $WP_URL

# 啟動 docker
echo "********** 啟動 docker **********"
sudo docker-compose up -d

# 修改wp-content/uploads的資料夾權限
echo "********** 修改wp-content/uploads的資料夾權限 **********"
sudo sh production_scripts/wp_permission_setting.sh $COMPOSE_PROJECT_NAME

echo "設定完成！！"