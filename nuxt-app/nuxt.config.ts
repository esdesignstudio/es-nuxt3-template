import { resolve, dirname } from 'node:path'

export default defineNuxtConfig({
    app: {
        pageTransition: { name: 'page', mode: 'out-in' },
        head: {
            htmlAttrs: {
                lang: 'zh-TW',
            },
            charset: 'utf-8',
            title: process.env.APP_NAME,
            titleTemplate: '%s ✷ ' + process.env.APP_NAME,
            meta: [
                { name: 'theme-color', content: '#000000' },
                { name: 'distribution', content: 'Taiwan Taipei' },
                { name: 'copyright', content: 'ES Design 壹慎設計有限公司' },
                { name: 'viewport', content: 'width=device-width, initial-scale=1' },
                { name: 'description', content: '我們專注在【視覺設計、品牌識別、網頁設計、特效開發】全方位客製化設計解決方案，強調視覺與互動的細節體驗，讓內容可以超越形式的存在，嘗試打造突能破框架的品牌價值' },
                { property: 'og:type', content: 'website' },
                { hid: 'og:image', property: 'og:image', content: 'https://e-s.tw/wp-content/uploads/2022/10/socialshare.jpg' },
                { hid: 'og:url', property: 'og:url', content: '' },
                { hid: 'og:site_name', property: 'og:site_name', content: process.env.APP_NAME },
                { property: 'og:image:width', content: '1200' },
                { property: 'og:image:height', content: '630' },
                { name: 'twitter:card', content: 'summary_large_image' },
            ],
            link: [
                { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico?v1' },
                {
                    href: 'https://fonts.googleapis.com/css2?family=Arimo:wght@400;700&family=Poppins:wght@400;600&display=swap',
                    rel: 'stylesheet',
                },
            ],
            noscript: [
                { children: '😚 ES Design：此網站必須啟用 ✪ Javascript ✪' }
            ]
        }
    },
    // experimental: {
    //     writeEarlyHints: false,
    // },
    css: [
        '@/style/_main.scss', // global css
    ],
    vite: {
        css: {
            preprocessorOptions: {
                scss: {
                    additionalData: '@import "@/style/mixins/_mixin.scss";',
                },
            },
        },
        resolve:{
            alias: {
                '~': resolve(__dirname, './assets/')
            }
        }
    },
    modules: [
        'nuxt-jsonld',
        '@intlify/nuxt3'
    ],
    intlify: {
        vueI18n: {
            locale: 'zh',
            fallbackLocale: 'zh'
        }
    },
    // i18n 目前不支援 nuxt3 routing，要自行創建 en 連結
    // https://github.com/nuxt/framework/discussions/5901
    hooks: { 
        // 'pages:extend': (pages) => {
        //     // pages.push({
        //     //     name: 'works-slug',
        //     //     path: '/works/:slug',
        //     //     file: resolve(__dirname, './pages/works/_slug.vue')
        //     // })
        //     pages.push({
        //         name: 'en-index',
        //         path: '/en',
        //         file: resolve(__dirname, './pages/index.vue')
        //     })
        // }
    },
    runtimeConfig: {
        public: {
            apiBase: process.env.API_DEV_URL + '/wp-json/api',
            siteName: process.env.APP_NAME
        },
    },
    debug: false
})
