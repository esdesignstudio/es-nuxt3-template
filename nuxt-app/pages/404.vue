<template>
    <div class="error-page">
        <div class="container">
            <div class="container-inner error-page__wrap">
                <div>
                    <h1>
                        404 - Page Not Found
                    </h1>
                </div>
            </div>
        </div>
        <Footer />
    </div>
</template>
<script setup>
    const pageloaded = usePageLoaded()
    
    onMounted(() => {
        nextTick(() => {
            pageloaded.value = true
        })
    })
</script>
<style lang="scss">
    $class-name: error-page;
    .#{$class-name} {
    }
</style>