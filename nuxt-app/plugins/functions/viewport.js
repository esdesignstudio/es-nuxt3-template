import { debounce } from 'lodash'
import { detect } from 'detect-browser'
import mobileInnerHeight from './mobileInnerHeight'

let innerHeight
const getterKeys = ['vpWidth', 'vpHeight', 'device', 'mediaType', 'isDesktop', 'isTablet', 'isMobile', 'isPc', 'isIE']

class Viewport {
    constructor () {
        this.information = {
            width: 0,
            height: 0,
            device: detect(),
            media: {
                mobile: '(max-width: 767px)',
                tablet: '(max-width: 1199px) and (min-width: 768px)',
                desktop: '(min-width:1200px)',
            },
        }

        if (process.client) {
            innerHeight = mobileInnerHeight()
            this.refresh()
            window.addEventListener('resize', this.refresh)
        }
    }

    refresh = debounce(() => {
        this.vpWidth = window.innerWidth
        this.vpHeight = innerHeight(true)
        this.device = detect()

        this.onResize?.()
    }, 350)

    destroy () {
        window.removeEventListener('resize', this.refresh)
    }

    get info () {
        return getterKeys.reduce((acc, curr) => {
            acc[curr] = this[curr]
            return acc
        }, {})
    }

    set vpWidth (value) {
        this.information.width = value
    }

    get vpWidth () {
        return this.information.width
    }

    set vpHeight (value) {
        this.information.height = value
    }

    get vpHeight () {
        return this.information.height
    }

    set device (value) {
        this.information.device = value
    }

    get device () {
        return this.information.device
    }

    get mediaType () {
        if (process.client) {
            const map = this.information.media
            for (const key in map) {
                if (window.matchMedia(map[key]).matches) {
                    return key
                }
            }
        }
        return true
    }

    get isDesktop () {
        return this.mediaType === 'desktop'
    }

    get isTablet () {
        return this.mediaType === 'tablet'
    }

    get isMobile () {
        return this.mediaType === 'mobile'
    }

    get isPc () {
        if (process.client) {
            const touchPoints = window.navigator.maxTouchPoints || window.navigator.msMaxTouchPoints
            const { os } = this.device
            return !touchPoints && os !== 'iOS' && os !== 'Android OS'
        }
        return true
    }

    get isIE () {
        if (process.client) {
            const { name } = this.device
            return name === 'ie'
        }
        return true
    }
}

export default new Viewport()
