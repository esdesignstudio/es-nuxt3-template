<template>
    <div class="single-works container">
        <Title>{{ pageName }} ｜ ES Template</Title>
        <div class="single-works__content">
            <h1>{{ pageName }}</h1>
        </div>
    </div>
</template>
<script setup>
    import ScrollTrigger from 'gsap/ScrollTrigger'
    import useStore from "~~/store"
    import { storeToRefs } from 'pinia'

    const route = useRoute()
    const store = useStore()
    const pageName = ref('')
    pageName.value = route.params.name

    const { $ESscroll, $viewportInfo } = useNuxtApp()
    const { isLoading } = storeToRefs(store)

    const pageData = ref(null)

    watch(() => isLoading.value, (val) => {
        if (!val) {
            ScrollTrigger.refresh()
        }
    })

    onMounted(() => {
        $ESscroll.stop()
        fetchData().then(res => {
            pageData.value = res.data
            $ESscroll.start()
        })
    })

    const fetchData = () => {
        return new Promise(resolve => {
            $fetch('/works-post/' + pageName.value, {
                method: 'GET',
                baseURL: useRuntimeConfig().public.apiBase
            }).then(res => {
                resolve(res)
                setTimeout(() => {
                    store.addLoadingStack(store.loadImage())
                    store.waitLoading()
                }, 1)
            })
        })
    }
</script>
<style lang="scss">
    $class-name: single-works;
    .#{$class-name} {
        position: relative;

        &__content {
            padding: 100px 0;
        }
    }
</style>