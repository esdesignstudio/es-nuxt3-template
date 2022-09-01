<template>
    <div class="page-index">
        <Title>Index ｜ ES Template</Title>
        <div class="container">
            <div class="page-index__content">
                <h1>Index</h1>
            </div>
        </div>
    </div>
</template>
<script setup>
    import useStore from "~~/store"

    const store = useStore()
</script>
<style lang="scss">
    $class-name: page-index;
    .#{$class-name} {
        position: relative;
    }
</style>