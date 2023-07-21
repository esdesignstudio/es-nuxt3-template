FROM node:16

# 安装 PM2
RUN yarn global add pm2

WORKDIR /app

# 將 package.json 和 yarn.lock 複製到容器中
COPY package.json yarn.lock ./

# 使用 yarn 安装依赖
RUN yarn install

# 將當前目錄複製到容器中
COPY . .

# build（可視情況是否要在server上build）
# RUN yarn build

# 設定對外端口
EXPOSE 4000
# 注意！！！因為pm2設定檔裡有指定port，所以這裡開的port要跟ecosystem.config.js中指定的port一樣
# 用pm2啟動
CMD pm2-runtime start ecosystem.config.js --env prod
# CMD yarn start
