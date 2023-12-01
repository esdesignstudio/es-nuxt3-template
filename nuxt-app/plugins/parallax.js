export default defineNuxtPlugin( nuxtApp => {
    const pageloaded = usePageLoaded() 
    const setParallax = (el, binding) => {
        const { top, bottom } = el.getBoundingClientRect()
        const vHeight = (window.innerHeight || document.documentElement.clientHeight)
        const speed = binding.value || 0.1
        const originalPosition = top
        if (window.$scroll) {
            window.$scroll.on('scroll', () => {
                const inView = el.classList.contains('is-inview')
                if (inView) {
                    const scroll = window.$scroll.scroll.instance.scroll.y
                    el.style.transform = `translate3d(0, ${scroll * -speed}px, 0)`
                }
            })
        } else {
            window.addEventListener('scroll', () => {
                const inView = el.classList.contains('is-inview')
                if (inView) {
                    const scroll = window.pageYOffset
                    el.style.transform = `translate3d(0, ${scroll * -speed}px, 0)`
                }
            })
        }
    }
    nuxtApp.vueApp.directive('parallax', {
        created (el, binding) {
            el.style.transform = `translate3d(0, 0, 0)`
            watch(pageloaded, (val) => {
                if (val) {
                    setParallax(el, binding)
                }
            }, {
                deep: true,
                immediate: true
            })
        },
        updated (el, binding) {
        },
    });
});