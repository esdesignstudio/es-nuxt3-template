<template>
    <div id="wrapper" class="wrapper">
        <NuxtLoadingIndicator />
        <Header />
        <main data-scroll-container>
            <NuxtPage />
        </main>
        <PageDev/>
    </div>
</template>

<script setup>
    // watch(() => isLoading.value, (val) => {
    //     if (!val) {
    //         setTimeout(() => {
    //             document.body.classList.add('landed')
    //         }, 1200)
    //     }
    // })

    // onMounted(() => {
    //     let vh = window.innerHeight * 0.01
    //     document.documentElement.style.setProperty('--vh', `${vh}px`)

    //     store.addLoadingStack(store.loadImage())
    //     store.waitLoading()
    // })
</script>