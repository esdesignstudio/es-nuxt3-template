<template>
    <div>
        <div class="loading">
            <div class="container">
                <div class="loading__spinner">
                    FIRST LOADING
                </div>
            </div>
        </div>
        <NuxtLayout name="default">
            <NuxtPage />
        </NuxtLayout>
    </div>
</template>
<script setup>
    const pageloaded = usePageLoaded()
    
    onMounted(() => {
        ESinit()
    })
</script>
<style lang="scss">
$class-name: loading;
.#{$class-name} {
    @include size(100%);

    top: 0;
    left: 0;
    position: fixed;
    z-index: 9999;

    > div {
        @include size(100%);
        @include typo('head', 2);

        color: map-get($colors, white);
        z-index: 1;
        display: flex;
        position: relative;
        align-items: center;
        justify-content: center;
        transition: opacity .3s, transform .3s;
    }

    &:before {
        @include size(100%);

        content: '';
        top: 0;
        left: 0;
        z-index: 0;
        position: absolute;
        transform-origin: 0 0;
        background-color: map-get($colors, black-1);
        transition: transform 1s .1s cubic-bezier(0.87, 0, 0.13, 1);
    }

}
body.isLoaded {
    .#{$class-name} {
        pointer-events: none;

        &:before {
            transform: scaleY(0);
        }

        > div {
            opacity: 0;
            transform: translateY(-5rem);
        }
    }
}
</style>