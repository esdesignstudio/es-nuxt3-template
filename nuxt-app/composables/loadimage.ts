import ImagesLoaded from 'imagesloaded'

export const loadImage = () => {
    return new Promise((resolve) => {
        new ImagesLoaded('#__nuxt', (instance) => {
            resolve(instance)
        }).on('progress', (instance, image) => {
            console.log(instance.progressedCount / instance.images.length)
        })
    })
}