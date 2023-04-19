# ES Nuxt3 🍳  WordPress

<img src="https://e-s.tw/wp-content/uploads/2022/11/socialuse.jpg" />

ES 開發的 Nuxt3 x WordPress 版本，專門使用在客製化專案客戶。
如有在使用上遇到困難或者有改進建議的地方歡迎到 [Issues](https://github.com/esdesignstudio/es-nuxt3-template/issues) 提交問題，或者來信 [hi@e-s.tw](mailto:hi@e-s.tw)。
免費商用，請隨意下載。

## 環境
- Node -- v16.16.0
- yarn -- 1.22.19

## 安裝步驟
1. 安裝 Docker desktop
2. 到 /.env 以及 nuxt-app/.env 檔設定基本環境
3. docker-compose up -d
4. cd nuxt-app
5. yarn && yarn dev
6. 專案啟動在
   nuxt localhost:3000,
   wordpress localhost:9000/wp-admin

## 資料庫輸出
1. `sh dump.sh` 將 docker VM 的 DB 資料匯出至 `/db/default/wp.sql`

## 相關文件
- [Nuxt3](https://nuxt.com/)
- [Nuxt Icons](https://github.com/gitFoxCode/nuxt-icons)
- [Formkit](https://formkit.com/getting-started/what-is-formkit)

## Git Commit Type 規範
1. feat: 新增/修改功能 (feature)。
2. fix: 修補 bug (bug fix)。
3. docs: 文件，增加說明 (documentation)。
4. backup: 備份。EX： SQL 檔案
5. data: 資料變化。EX：圖片、固定文案、動態資料
6. style: 格式 (不影響程式碼運行的變動 white-space, formatting, missing semi colons, etc)。
7. refactor: 重構 (既不是新增功能，也不是修補 bug 的程式碼變動)。
8. perf: 改善效能 (A code change that improves performance)。
9. test: 增加測試 (when adding missing tests)。
10. chore: 建構程序或輔助工具的變動 (maintain)。
11. revert: 撤銷回覆先前的 commit 例如：revert: type(scope): subject (回覆版本：xxxx)。
12. deploy: 部署相關檔案
