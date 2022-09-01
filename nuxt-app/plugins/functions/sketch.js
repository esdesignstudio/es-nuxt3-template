import { GifReader } from 'omggif'

export default class {
    constructor (el, options) {
        this.initDone = false
        this.options = {
            clear: true,
            autoRender: true,
            drawingMode: 'center',
            defaultResources: [],
            ...options,
        }
        this.reqRenders = []
        this.resources = []
        this.onEvents = {}
        this.render = this.render.bind(this)
        this.resize = this.resize.bind(this)

        if (el instanceof Element) {
            this.el = el
            const { width, height, dpr } = this.viewport
            this.canvas = document.createElement('canvas')
            this.canvas.width = width * dpr
            this.canvas.height = height * dpr
            this.ctx = this.canvas.getContext('2d')
        }
    }

    async init () {
        await Promise.allSettled([this.loadDefaultResources()])
        if (this.el) {
            this.el.appendChild(this.canvas)

            if (this.options.autoRender) {
                this.render()
            }

            window.addEventListener('resize', this.resize)
            this.initDone = true
        }
    }

    async loadDefaultResources () {
        return await this.addResources(this.options.defaultResources)
    }

    async addResource (payload) {
        let { type, src, name, options } = payload

        if (!src.match(/^(http|https):\/\//)) {
            src = require(`@/assets/${src}`)
        }

        let resource = this.resources.find(resource => resource.name === name)
        if (!resource) {
            resource = {
                type,
                name: name || src,
                resource: null,
            }
            if (type === 'image') {
                const data = await this.loadImage(src, options)
                resource.resource = data
            }
            return resource
        }
        return null
    }

    async addResources (payload) {
        const promises = payload.map(resource => this.addResource(resource))

        return await Promise.allSettled(promises).then((results) => {
            results.forEach(({ status, value }) => {
                if (value) {
                    this.resources.push(value)
                }
            })
        })
    }

    getResource = (() => {
        const memo = {}
        return (string, type = 'name') => {
            if (memo[string]) return memo[string]
            const target = this.resources.find(resource => resource[type] === string)
            memo[string] = target
            return target
        }
    })()

    getResources (payload, type = 'name') {
        if (typeof payload === 'function') {
            return this.resources.filter(payload)
        }
        return this.resources.filter(resource => resource[type] === payload)
    }

    loadImage (url) {
        const ext = url.split('.').pop()
        const image = new Image()
        image.src = url
        const promises = [
            new Promise((resolve) => {
                image.onload = () => {
                    resolve(image)
                }
            }),
        ]
        if (ext === 'gif') {
            promises.push(fetch(url).then(res => res.arrayBuffer()).then(arrayBuffer => this.getGifBuffer(new Uint8Array(arrayBuffer))))
        }
        return Promise.allSettled(promises).then(([image, gifBuffers]) => ({
            image: image.value,
            gifBuffers: gifBuffers && gifBuffers.value,
        }))
    }

    getGifBuffer (data) {
        if (data instanceof Uint8Array) {
            const reader = new GifReader(data)
            const frameCount = reader.numFrames()
            const generate = []

            for (let i = 0; i < frameCount; i++) {
                const frameInfo = reader.frameInfo(i)
                frameInfo.pixels = new Uint8ClampedArray(reader.width * reader.height * 4)
                reader.decodeAndBlitFrameRGBA(i, frameInfo.pixels)

                const bufferCanvas = document.createElement('canvas')
                const bufferContext = bufferCanvas.getContext('2d')
                bufferCanvas.width = frameInfo.width
                bufferCanvas.height = frameInfo.height
                const imageData = bufferContext.createImageData(reader.width, reader.height * 4)
                imageData.data.set(frameInfo.pixels)
                bufferContext.putImageData(imageData, -frameInfo.x, -frameInfo.y)
                generate.push({
                    buffer: bufferCanvas,
                    frameDelay: frameInfo.delay * 10,
                })
            }
            return generate
        }
    }

    render () {
        this.reqID = window.requestAnimationFrame(this.render)
        this.update()
    }

    update () {
        const { width, halfWidth, height, halfHeight, dpr } = this.viewport
        const { clear, drawingMode } = this.options

        if (clear) {
            this.ctx.clearRect(0, 0, width * dpr, height * dpr)
        }
        this.ctx.save()
        if (drawingMode === 'center') {
            this.ctx.translate(halfWidth * dpr, halfHeight * dpr)
        }
        this.ctx.scale(dpr, dpr)
        for (let i = 0; i < this.reqRenders.length; i++) {
            this.reqRenders[i](this.reqID)
        }
        this.ctx.restore()
    }

    resize () {
        const { width, height, dpr } = this.viewport
        this.canvas.width = width * dpr
        this.canvas.height = height * dpr
    }

    stop () {
        window.cancelAnimationFrame(this.reqID)
    }

    destroy () {
        this.stop()
        if (this.initDone && this.el) {
            this.el.removeChild(this.canvas)

            window.removeEventListener('resize', this.resize)
        }
    }

    on (name, callback) {
        if (typeof name === 'string' && typeof callback === 'function') {
            this.onEvents[name] = callback
        }
    }

    get viewport () {
        const dpr = Math.min(window.devicePixelRatio, 1.5)
        const { width, height } = this.el.getBoundingClientRect()
        return {
            width,
            height,
            halfWidth: width / 2,
            halfHeight: height / 2,
            aspect: width / height,
            dpr,
        }
    }
}
