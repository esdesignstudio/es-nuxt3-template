
export const usePageLoaded = () => useState<boolean>('isPageloaded', () => false)

export const ESinit = (data:{ scroll: Function }) => {
    console.log("%c ★ Made by ES design ★ We are looking for awsome Front-End developer ↓", "font-weight:600;background:#ffe800;padding:5px 15px;border-radius:5px; color:#000000;font-size:14px;")
    console.log("%c https://www.cakeresume.com/companies/es-design/jobs !! JOIN US !!! ", "background:#000000;padding:5px 15px;border-radius:5px; color:#ffffff;font-size:10px;")
    console.log("%c 架構採用 Nuxt3 X WordPress，網站目前正在測試中～如果有任何問題歡迎回報給我們 hi@e-s.tw ", "background:#000000;padding:5px 15px;border-radius:5px; color:#ffffff;font-size:10px;")

    // const route = useRoute()
    // const { hook } = useNuxtApp()

    let isFirstLand = false
    const pageloaded = usePageLoaded()
    watch(pageloaded, (next) => {
        if (next) {
            if (!isFirstLand) {
                isFirstLand = true
                document.body.classList.add('isLoaded')
                setTimeout(() => {
                    document.querySelector('.loading')?.remove()
                }, 1000)
            }
        }
    }, {
        deep: true,
        immediate: true
    })
//     watch(route, () => {
//         data.value.destroy()
//     })
//     hook('page:start', () => {
//         console.lang('page:start')
//     })
//     hook('page:finish', () => {
//         console.lang('page:finish')
//     })
}
// export const useGlobal = () => useState<Object>('globalOption', () => {
//     const { data } = useAsyncData(
//         'get_globa_api',
//         () => $fetch( useRuntimeConfig().apiBase + '/get_global'), {
//             transform: (res) => {
//                 return res['data'] // res['data'] typescript
//             }
//         }
//     )
//     return data 
// })

