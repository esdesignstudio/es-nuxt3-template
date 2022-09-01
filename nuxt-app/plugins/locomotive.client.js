import LocomotiveScroll from 'locomotive-scroll'

export default defineNuxtPlugin(() => {
    return {
        provide: {
            ESscroll: new LocomotiveScroll({
                el: document.querySelector('[data-scroll-container]'),
                smooth: true,
                multiplier: 1,
                // direction: 'horizontal',
                getDirection: true,
                getSpeed: true,
                // smartphone: {
                //     smooth: true,
                //     getDirection: true,
                //     getSpeed: true,
                // },
                // tablet: {
                //     smooth: true,
                //     getDirection: true,
                //     getSpeed: true,
                // }
            }),
            lms: LocomotiveScroll,
        }
    }
})
