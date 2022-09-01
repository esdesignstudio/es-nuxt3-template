
import useStore from "~~/store"

export const ESinit = (data:{ scroll: Function }) => {
    const store = useStore()
    const route = useRoute()
    const { hook } = useNuxtApp()

    watch(route, () => {
        data.value.destroy()
    })
    hook('page:start', () => {
        data.value.init()
    })
    hook('page:finish', () => {
        store.changeCursorState('default', '')
        setTimeout(() => {
            data.value.update()
            document.body.classList.add('landed')
        }, 700) // 暫時找不到為什麼 page:finish 同樣不是在 dom ready，可能還是測試功能
    })
}
