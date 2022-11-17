<template>
    <div class="error-page">
        <div class="container">
            <div class="container-inner error-page__wrap">
                <div>
                    <h1>
                        <PageSlideupEachLine :data="['Page', 'not found']" />
                    </h1>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
    const pageloaded = usePageloaded()
    
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