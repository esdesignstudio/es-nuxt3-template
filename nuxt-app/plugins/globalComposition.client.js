import viewport from '@/plugins/functions/viewport'

export default defineNuxtPlugin(() => {
    // const store = useStore()
    const vp = ref(viewport)


    const viewportInfo = computed(() => vp.value.info)
    // onMounted(() => {
    //     store.dispatch('GET_USER_INFO')
    // })
    // onBeforeUnmount(() => {
    //     vp.value.destroy()
    // })
    
    return {
        provide: {
            viewportInfo: viewportInfo
        }
    }
})
