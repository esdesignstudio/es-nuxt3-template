#!/bin/sh

# 引入.env檔參數
if [ -f .env ]; then
    export $(cat .env | grep -v '#' | awk '/=/ {print $1}')
fi

# 檢查是否已定義必要的變數
if [ -z "$COMPOSE_PROJECT_NAME" ] || [ -z "$WP_URL" ] || [ -z "$WP_PORT" ]; then
    echo "未定義必要的變數: COMPOSE_PROJECT_NAME、WP_URL、WP_PORT。請檢查 .env 檔案。"
    exit 1
fi

PRODUCTION_DOMAIN=$(echo "$WP_URL" | sed 's/.*\/\///; s/\/.*//')
if [ -z "$PRODUCTION_DOMAIN" ]; then
    echo "無法取得Domain設定。請檢查 .env 檔案。"
    exit 1
fi

echo "PRODUCTION_DOMAIN = $PRODUCTION_DOMAIN"

# 檢查Docker是否已安裝
if [ -x /usr/bin/docker ]; then
    echo "Docker已安裝!!"
else
    echo "********** Docker尚未安裝，開始安裝... **********"
    # 執行安裝腳本
    sudo sh deployment_scripts/docker_install.sh
fi

# 檢查Nginx是否已安裝
if [ -x /usr/sbin/nginx ]; then
    echo "Nginx已安裝!!"
else
    echo "********** Nginx尚未安裝，開始安裝... **********"
    # 執行安裝腳本
    sudo sh deployment_scripts/nginx_install.sh
fi

# 檢查port是否被佔用
if [ "$WP_PORT" -ge 1 ] && [ "$WP_PORT" -le 65535 ]; then
    if sudo lsof -Pi :$WP_PORT -sTCP:LISTEN -t >/dev/null; then
        echo "端口 $WP_PORT 被占用"
        echo "占用端口信息："
        sudo lsof -i :$WP_PORT
        exit 1  # 如果端口被占用停止執行
    fi
else
    echo "無效的端口:$WP_PORT"
    exit 1
fi

# 建立nginx設定檔
echo "********** 建立nginx設定檔 **********"
sudo sh deployment_scripts/nginx_setting.sh $COMPOSE_PROJECT_NAME $PRODUCTION_DOMAIN $WP_PORT

# 修改wp.sql中的連結
echo "********** 修改wp.sql中的連結設定 **********"
sudo sh deployment_scripts/repleace_db_url.sh db/production/wp.sql $WP_URL

# 啟動 docker
echo "********** 啟動 docker **********"
sudo docker-compose up -d

# 修改wp-content/uploads的資料夾權限
echo "********** 修改wp-content/uploads的資料夾權限 **********"
sudo sh deployment_scripts/wp_permission_setting.sh $COMPOSE_PROJECT_NAME

echo "設定完成！！"