<template>
    <div id="wrapper" class="wrapper">
        <main data-scroll-container>
            <slot></slot>   
        </main>
        <PageDev/>
    </div>
</template>

<script setup>
    import useStore from "~~/store"
    import { storeToRefs } from 'pinia'

    const store = useStore()
    const { isLoading } = storeToRefs(store)

    watch(() => isLoading.value, (val) => {
        if (!val) {
            setTimeout(() => {
                document.body.classList.add('landed')
            }, 1200)
        }
    })

    onMounted(() => {
        let vh = window.innerHeight * 0.01
        document.documentElement.style.setProperty('--vh', `${vh}px`)

        store.addLoadingStack(store.loadImage())
        store.waitLoading()
    })
</script>