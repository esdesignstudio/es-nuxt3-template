# ES Nuxt3 WordPress

## 安裝步驟
1. 到 .env 檔設定基本環境
2. docker-compose up -d
3. cd nuxt-app
4. yarn && yarn dev
5. 專案啟動在 
    nuxt localhost:3000,
    wordpress localhost:9000/wp-admin

## 資料庫輸出
1. `sh dump.sh` 將 docker VM 的 DB 資料匯出至 `/db/default/wp.sql`

## 前端上傳步驟
1. stash 未commit的檔案後切換至 `deploy` 分支
2. rebase `deploy` 至 `master`
3. 確認於 `deploy` 分支下 `yarn build`
4. commit `/.output` 所有檔案
5. 連線主機
6. 於專案資料夾下`/project-name` 輸入 `git reset --hard origin/deploy` 、 `git pull origin deploy`
7. 開啟 `pm2` 可以先 `pm2 delete project-name`，或者直接 `pm2 restart project-name`
8. 沒有開過 `pm2` 則 `cd /project-name/nuxt-app` 輸入 `pm2 start --env prod`

## Git Commit Type 規範

1. feat: 新增/修改功能 (feature)。
2. fix: 修補 bug (bug fix)。
3. docs: 文件，增加說明 (documentation)。
4. backup: 備份。EX： SQL檔案
5. data: 資料變化。EX：圖片、固定文案、動態資料
6. style: 格式 (不影響程式碼運行的變動 white-space, formatting, missing semi colons, etc)。
7. refactor: 重構 (既不是新增功能，也不是修補 bug 的程式碼變動)。
8. perf: 改善效能 (A code change that improves performance)。
9. test: 增加測試 (when adding missing tests)。
10. chore: 建構程序或輔助工具的變動 (maintain)。
11. revert: 撤銷回覆先前的 commit 例如：revert: type(scope): subject (回覆版本：xxxx)。
12. deploy: 部署相關檔案


