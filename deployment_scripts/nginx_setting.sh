#!/bin/bash

# if [ -f .env ]; then
#     export $(cat .env | grep -v '#' | awk '/=/ {print $1}')
# fi

# 檢查是否已定義必要的變數
# if [ -z "$PRODUCTION_PROJECT_NAME" ] || [ -z "$DOMAIN" ]; then
#     echo "未定義必要的變數 project_name 或 domainame_com。請檢查 .env 檔案。"
#     exit 1
# fi

if [ $# -ne 2 ]; then
    echo "nginx_setting.sh fail - 請提供2個參數：專案名稱和域名"
    exit 1
fi

PROJECT_NAME=$1
DOMAIN=$2

# 檢查是否有權限寫入到 /etc/nginx/sites-enabled/
if [ -w "/etc/nginx/sites-enabled/" ]; then
    # 設定檔案路徑及內容，使用參數
    config_path="/etc/nginx/sites-enabled/$PROJECT_NAME"
    config_content="server {
        listen 80;
        server_name $DOMAIN;

        location / {
            proxy_pass http://127.0.0.1:9000;

            proxy_redirect                      off;
            proxy_set_header Host               \$host;
            proxy_set_header X-Real-IP          \$remote_addr;
            proxy_set_header X-Forwarded-For    \$proxy_add_x_forwarded_for;
            proxy_set_header X-Forwarded-Proto  \$scheme;
        }
    }"

    # 將設定檔內容寫入指定路徑
    echo "$config_content" | sudo tee "$config_path" > /dev/null
    echo "nginx設定檔已創建在 $config_path"
    echo "重新啟動 nginx"
    sudo systemctl restart nginx
else
    echo "沒有權限寫入到 /etc/nginx/sites-enabled/，請確保你有足夠的權限執行此腳本"
fi
