<template>
    <div class="header-index">
        <div class="container">
            <div class="header-index__wrap">
                <NuxtLink 
                    to="/"
                    class="header-index__logo"
                >
                    ES TEMPLATE
                </NuxtLink>
                <div class="header-index__navigation">
                    <HeaderNavigation />
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
    const props = defineProps({
        template: {
            type: Object,
            default: {},
        },
    })
</script>
<style lang="scss">
$class-name: header-index;
.#{$class-name} {
    &__wrap {
        padding: 2rem 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    &__logo {
        @include typo('display', 1);
    }
}
</style>