<template>
    <div>
        <div class="loading">
            <div class="container">
                <div class="loading__logo">
                    <h2>Loading</h2>
                </div>
            </div>
        </div>
        <NuxtLayout name="default">
            <NuxtPage />
        </NuxtLayout>
    </div>
</template>
<script setup>
    import useStore from "~~/store"

    const store = useStore()
</script>
<style lang="scss">
$class-name: loading;
    .#{$class-name} {
        @include size(100vw, 100vh);

        position: fixed;
        top: 0;
        left: 0;
        z-index: 999;
        background-color: map-get($colors, theme);
        opacity: 1;
        transition: opacity .6s;

        @include media-breakpoint-down(tablet) {
            @include size(100vw, 100%);
        }

        &__logo {
            @include size(144px, auto);

            position: absolute;
            bottom: 80px;

            @include media-breakpoint-down(tablet) {
                @include size(104px, auto);

                bottom: 19px;
            }
        }

        .landed & {
            pointer-events: none;
            opacity: 0;
        }
    }
</style>