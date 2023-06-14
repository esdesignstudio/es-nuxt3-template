import { resolve, dirname } from 'node:path'
import en from './lang/en/index.js'
import zh from './lang/zh/index.js'

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
                { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
                {
                    href: 'https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;700&display=swap',
                    rel: 'stylesheet',
                },
            ],
            noscript: [
                { children: '😚' + process.env.APP_NAME + '：此網站必須啟用 ✪ Javascript ✪' }
            ], 
            script: [
                { src: 'https://static.line-scdn.net/liff/edge/2/sdk.js'}
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
        },
        server: { // 解決開發時 websocket 問題
            hmr: {
                protocol: 'ws',
                host: 'localhost'
            }
        }
    },
    modules: [
        // translateModule,
        '@nuxtjs/i18n',
        '@formkit/nuxt',
        'nuxt-icons'
    ],
    i18n: {
        defaultLocale: 'zh',
        detectBrowserLanguage: {
            useCookie: false
        },
        vueI18n: {
            messages: {
                en: en,
                zh: zh
            },
          fallbackLocale: 'zh',
        }
    },
    runtimeConfig: {
        public: {
            siteUrl: process.env.SITE_URL,
            apiUrl: process.env.API_URL + '/wp-json/api',
            siteName: process.env.APP_NAME
        },
    },
    // debug: true,
})
