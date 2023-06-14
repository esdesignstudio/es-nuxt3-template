export default defineNuxtPlugin( nuxtApp => {
    const checkInView = (el) => {
        const { top, bottom } = el.getBoundingClientRect()
        const vHeight = (window.innerHeight || document.documentElement.clientHeight)

        if (top > vHeight || bottom < 0 - vHeight) {
            el.classList.remove('is-inview')
        } else {
            el.classList.add('is-inview')
        }
    }

    nuxtApp.vueApp.directive('inview', {
        created (el, binding) {
            setTimeout(() => {
                checkInView(el)
                if (window.$scroll) {  // locomotive-scroll
                    window.$scroll.on('scroll', () => {
                        checkInView(el)
                    })
                } else {
                    window.addEventListener('scroll', () => {
                        checkInView(el)
                    })
                }
                window.addEventListener('resize', () => {
                    checkInView(el)
                })
            }, 701)

        },
        updated (el, binding) {
        },
    });
});