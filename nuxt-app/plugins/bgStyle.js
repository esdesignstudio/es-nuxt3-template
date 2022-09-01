export default defineNuxtPlugin( nuxtApp => {
    const setBackground = (el, binding) => {
        const { contain, lazy } = binding.modifiers
        const size = contain ? 'contain' : 'cover'

        const value = binding.value
        if (value) {
            if (!lazy) {
                el.setAttribute('data-background', '')
                el.style.background = `url('${value}') no-repeat center / ${size}`
                return
            }
        }
        el.style.backgroundImage = null
        el.style.backgroundPosition = 'center'
        el.style.backgroundSize = size
    }
    nuxtApp.vueApp.directive('bg', {
        created (el, binding) {
            setBackground(el, binding)
        },
        updated (el, binding) {
            setBackground(el, binding)
        },
    });
});