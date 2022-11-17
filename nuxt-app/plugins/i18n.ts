export default defineNuxtPlugin((nuxtApp) => {
    const { hook } = nuxtApp
    const route = useRoute()
    const cookieLanguage = useCookie('language')

    // 存 Cookie
    switch (route.path.split('/')[1]) {
        case '' :
            cookieLanguage.value = 'zh'
            nuxtApp.vueApp.config.globalProperties.$i18n.locale = 'zh'
            break
        case 'en' :
            cookieLanguage.value = 'en'
            nuxtApp.vueApp.config.globalProperties.$i18n.locale = 'en'
            break
    }

    // console.log('nuxtApp.vueApp.config', nuxtApp.vueApp)
})