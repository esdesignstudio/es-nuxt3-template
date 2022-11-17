import VueGtag from 'vue-gtag-next'

export default defineNuxtPlugin((nuxtApp) => {
    nuxtApp.vueApp.use(VueGtag, {
        property: {
            id: 'YOUR-GA4-ID'
        }
    }, nuxtApp.vueApp.router)
})