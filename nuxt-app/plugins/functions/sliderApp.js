import { Vector2 } from 'three'
import gsap from 'gsap'
import cover from 'canvas-image-cover'
import Sketch from '@/plugins/functions/sketch'

const IMAGE_SCALE = 1.5
const GLOBAL_TWEEN = {
    duration: 1.25,
}

class Slide {
    constructor (resource, options) {
        const { image: src, gifBuffers } = resource
        this.resource = resource
        this.options = {
            viewport: new Vector2(),
            viewSize: new Vector2(),
            position: new Vector2(),
            scale: 1,
            ...options,
        }
        const { viewSize, position, scale } = this.options
        this.viewSize = viewSize.clone()
        this.position = position.clone()
        this.scale = scale

        if (src instanceof Image) {
            this.src = src
            this.drawBuffer = this.src

            if (gifBuffers instanceof Array) {
                this.gifBuffers = gifBuffers
                this.timer = null
                this._gifFrameCount = 0
                this.gifFrame = this.gifFrame.bind(this)
                this.gifFrame()
            }
        }
    }

    clone (type) {
        const { viewport, viewSize, position } = this.options
        const slide = new Slide(this.resource, {
            viewport: viewport.clone(),
            viewSize: viewSize.clone(),
            position: position.clone(),
        })

        if (type === 'first') {
            slide.setViewSize(viewport.x, viewport.y, false)
            slide.setPosition(-viewport.x, 0, false)
            slide.setScale(IMAGE_SCALE, false)
        }
        if (type === 'last') {
            slide.setViewSize(0, viewport.y, false)
            slide.setPosition(viewport.x, 0, false)
            slide.setScale(1, false)
        }

        return slide
    }

    destroy () {
        window.clearTimeout(this.timer)
    }

    refresh (width, height) {
        this.options.viewport.set(width, height)
    }

    setViewSize (width, height, tween = true) {
        this.options.viewSize.set(width, height)

        return new Promise((resolve) => {
            if (tween) {
                gsap.to(this.viewSize, {
                    ...GLOBAL_TWEEN,
                    x: width,
                    y: height,
                    onComplete () {
                        resolve()
                    },
                })
                return
            }
            this.viewSize.set(width, height)
            resolve()
        })
    }

    setPosition (x, y, tween = true) {
        this.options.position.set(x, y)

        return new Promise((resolve) => {
            if (tween) {
                gsap.to(this.position, {
                    ...GLOBAL_TWEEN,
                    x,
                    y,
                    onComplete () {
                        resolve()
                    },
                })
                return
            }
            this.position.set(x, y)
            resolve()
        })
    }

    setScale (scale, tween = true) {
        this.options.scale = scale

        return new Promise((resolve) => {
            if (tween) {
                gsap.to(this, {
                    ...GLOBAL_TWEEN,
                    scale,
                    onComplete () {
                        resolve()
                    },
                })
                return
            }
            this.scale = scale
            resolve()
        })
    }

    update (ctx) {
        ctx.save()
        const { sx, sy, sw, sh, x, y, width, height } = cover(
            this.src,
            this.position.x, this.position.y,
            this.viewSize.x, this.viewSize.y
        ).zoom(this.scale)
        ctx.drawImage(this.drawBuffer, sx, sy, sw, sh, x, y, width, height)
        ctx.restore()
    }

    gifFrame () {
        ({ buffer: this.drawBuffer, frameDelay: this.frameDelay } = this.gifBuffers[this.gifFrameCount])

        this.timer = window.setTimeout(() => {
            this.gifFrameCount++
            this.gifFrame()
        }, this.frameDelay)
    }

    get gifFrameCount () {
        if (this._gifFrameCount !== undefined) return this._gifFrameCount
        return 0
    }

    set gifFrameCount (value) {
        if (this._gifFrameCount !== undefined) {
            this._gifFrameCount = (value + this.gifBuffers.length) % this.gifBuffers.length
        }
    }
}

export default class extends Sketch {
    constructor (el, resources = [], options) {
        let autoRender = false
        const defaultResources = resources.map(({ image }, i) => {
            if (image) {
                const ext = image.split('.').pop()
                if (ext === 'gif') autoRender = true
            }
            return {
                type: 'image',
                name: `bg${i}`,
                src: image || 'img/black.png',
                // src: `https://source.unsplash.com/random/1200x68${i}`,
            }
        })

        super(el, {
            drawingMode: 'default',
            autoRender,
            defaultResources,
        })
        if (el instanceof Element) {
            this.options = {
                sliceRatio: [0.833, 0.0833, 0.0555, 0.0277, 0],
                breakPoints: {
                    '(max-width: 1199px)': {
                        sliceRatio: [1, 0],
                    },
                },
                ...this.options,
                ...options,
            }
            this.params = {
                sliceRatio: this.options.sliceRatio,
            }
            this.slides = []
            this.currentSlides = []
            this.canSlide = true
            this._slidesIndex = 0
            this.dragStart = this.dragStart.bind(this)
            this.mousemove = this.mousemove.bind(this)
            this.touchmove = this.touchmove.bind(this)
            this.dragEnd = this.dragEnd.bind(this)

            this.refreshParams()
        }
    }

    async init () {
        await super.init()

        const { width: vpWidth, height: vpHeight } = this.viewport

        this.resources.forEach(({ resource }) => {
            this.slides.push(new Slide(resource, {
                viewport: new Vector2(vpWidth, vpHeight),
            }))
        })
        this.sliceSlides()
        this.createDragEvent()
        this.draw()

        if (!this.options.autoRender) {
            this.update()
        }
    }

    sliceSlides () {
        const { width: vpWidth, height: vpHeight } = this.viewport
        const { sliceRatio } = this.params

        while (this.currentSlides.length) {
            const currentSlides = this.currentSlides.pop()
            currentSlides.destroy()
        }

        let prevRatio = 0
        sliceRatio.forEach((ratio, i, arr) => {
            const slide = this.slides[(i + this.slideIndex) % this.slides.length].clone()
            if (slide) {
                slide.setViewSize(vpWidth * ratio, vpHeight, false)
                slide.setPosition(vpWidth * prevRatio, 0, false)
                slide.setScale(!(i % 2) ? 1 : IMAGE_SCALE, false)
                this.currentSlides.push(slide)
            }
            prevRatio += ratio
        })
    }

    slideNext () {
        const { autoRender } = this.options
        const { sliceRatio } = this.params
        if (this.slides.length && this.canSlide) {
            if (!autoRender) {
                this.render()
            }
            this.canSlide = false
            this.slideIndex++
            this.onEvents.onTransitionStart?.(this.realIndex, 1)

            const { width: vpWidth, height: vpHeight } = this.viewport

            const promises = []
            let prevRatio = 0
            for (let i = 0; i < sliceRatio.length; i++) {
                const slide = this.currentSlides[i]

                if (i > 0) {
                    const ratio = sliceRatio[i - 1]

                    promises.push(
                        slide.setViewSize(vpWidth * ratio, vpHeight),
                        slide.setPosition(vpWidth * prevRatio, 0),
                        slide.setScale((i % 2) ? 1 : IMAGE_SCALE)
                    )
                    prevRatio += ratio
                    continue
                }

                promises.push(
                    slide.setPosition(-vpWidth * sliceRatio[i], 0, true),
                    slide.setScale(IMAGE_SCALE)
                )
            }

            Promise.all(promises).then(() => {
                const slide = this.slides[(this.currentSlides.length - 1 + this.slideIndex) % this.slides.length]

                const currentSlide = this.currentSlides.shift()
                currentSlide.destroy()
                this.currentSlides.push(slide.clone('last'))

                this.canSlide = true
                this.onEvents.onTransitionEnd?.(this.realIndex, 1)
                if (!autoRender) {
                    this.stop()
                }
            })
        }
    }

    slidePrev () {
        const { autoRender } = this.options
        const { sliceRatio } = this.params

        if (this.slides.length && this.canSlide) {
            if (!autoRender) {
                this.render()
            }
            this.canSlide = false
            this.slideIndex--
            this.onEvents.onTransitionStart?.(this.realIndex, -1)

            const { width: vpWidth, height: vpHeight } = this.viewport

            const slide = this.slides[this.slideIndex]
            this.currentSlides.unshift(slide.clone('first'))

            const promises = []
            let prevRatio = 0
            for (let i = 0; i < sliceRatio.length; i++) {
                const slide = this.currentSlides[i]
                const ratio = sliceRatio[i]

                promises.push(
                    slide.setViewSize(vpWidth * ratio, vpHeight),
                    slide.setPosition(vpWidth * prevRatio, 0),
                    slide.setScale(!(i % 2) ? 1 : IMAGE_SCALE)
                )
                prevRatio += ratio
            }

            Promise.all(promises).then(() => {
                const currentSlides = this.currentSlides.pop()
                currentSlides.destroy()

                this.canSlide = true
                this.onEvents.onTransitionEnd?.(this.realIndex, -1)
                if (!autoRender) {
                    this.stop()
                }
            })
        }
    }

    createDragEvent () {
        this.previousTouch = null
        this.el.addEventListener('mousedown', this.dragStart)
        this.el.addEventListener('mousemove', this.mousemove)
        this.el.addEventListener('mouseup', this.dragEnd)
        this.el.addEventListener('mouseleave', this.dragEnd)
        this.el.addEventListener('touchstart', this.dragStart)
        this.el.addEventListener('touchmove', this.touchmove, { passive: false })
        this.el.addEventListener('touchend', this.dragEnd)
        this.el.addEventListener('touchcancel', this.dragEnd)
    }

    dragStart (e) {
        this.isDrag = true
    }

    mousemove ({ movementX }) {
        if (this.isDrag) {
            this.dragDirection = Math.sign(movementX)
        }
    }

    touchmove (e) {
        const touch = e.touches[0]
        const { clientX, clientY } = touch

        if (this.previousTouch && this.isDrag) {
            const diffY = clientY - this.previousTouch.clientY
            if (Math.abs(diffY) < 10) {
                e.preventDefault()
            }
            this.dragDirection = Math.sign(clientX - this.previousTouch.clientX)
        }
        this.previousTouch = touch
    }

    dragEnd (e) {
        this.isDrag = false
        this.previousTouch = e.touches && e.touches[0]
        if (this.dragDirection) {
            this.dragDirection > 0 ? this.slidePrev() : this.slideNext()
            this.dragDirection = 0
        }
    }

    refreshParams () {
        for (const key in this.options.breakPoints) {
            if (window.matchMedia(key).matches) {
                for (const optionKey in this.options.breakPoints[key]) {
                    this.params[optionKey] = this.options.breakPoints[key][optionKey]
                }
                continue
            }
            for (const optionKey in this.options) {
                if (this.params[optionKey] !== undefined) {
                    this.params[optionKey] = this.options[optionKey]
                }
            }
        }
    }

    resize () {
        super.resize()
        const { width: vpWidth, height: vpHeight } = this.viewport

        this.refreshParams()
        let prevRatio = 0
        const { sliceRatio } = this.params
        sliceRatio.forEach((ratio, i, arr) => {
            const slide = this.currentSlides[i % arr.length]
            if (slide) {
                slide.setViewSize(vpWidth * ratio, vpHeight, false)
                slide.setPosition(vpWidth * prevRatio, 0, false)
            }
            prevRatio += ratio
        })
        this.slides.forEach((slide, i) => {
            slide.refresh(vpWidth, vpHeight)
        })

        if (!this.options.autoRender) {
            this.update()
        }
    }

    draw () {
        this.reqRenders.push((t) => {
            for (let i = 0, len = this.currentSlides.length; i < len; i++) {
                const slide = this.currentSlides[i]
                slide.update(this.ctx)
            }
        })
    }

    destroy () {
        super.destroy()

        this.el.removeEventListener('mousedown', this.dragStart)
        this.el.removeEventListener('mousemove', this.mousemove)
        this.el.removeEventListener('mouseup', this.dragEnd)
        this.el.removeEventListener('mouseleave', this.dragEnd)
        this.el.removeEventListener('touchstart', this.dragStart)
        this.el.removeEventListener('touchmove', this.touchmove)
        this.el.removeEventListener('touchend', this.dragEnd)
        this.el.removeEventListener('touchcancel', this.dragEnd)
    }

    get slideIndex () {
        return this._slidesIndex
    }

    set slideIndex (value) {
        this._slidesIndex = (value + this.slides.length) % this.slides.length
    }

    get realIndex () {
        return this.slideIndex % this.resources.length
    }
}
