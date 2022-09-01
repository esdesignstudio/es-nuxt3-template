const getHeightInfo = () => {
    const dims = { w: 0, h: 0 }
    const axis = Math.abs(window.orientation)
    let measuringEl = document.createElement('div')

    measuringEl.style.position = 'fixed'
    measuringEl.style.height = '100vh'
    measuringEl.style.width = 0
    measuringEl.style.top = 0

    document.documentElement.appendChild(measuringEl)

    dims.w = axis === 90 ? measuringEl.offsetHeight : window.innerWidth
    dims.h = axis === 90 ? window.innerWidth : measuringEl.offsetHeight

    document.documentElement.removeChild(measuringEl)
    measuringEl = null
    return { axis, dims }
}

export default () => {
    let { axis, dims } = getHeightInfo()
    return (refresh) => {
        if (refresh) ({ axis, dims } = getHeightInfo())
        if (axis !== 90) return dims.h
        return dims.w
    }
}
