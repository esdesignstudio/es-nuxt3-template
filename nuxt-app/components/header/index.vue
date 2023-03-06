<template>
    <div class="header-index">
        <div class="container">
            <div class="header-index__wrap">
                <NuxtLink 
                    to="/"
                    class="header-index__logo"
                >
                    <nuxt-icon name="logo" />
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
        display: flex;
        padding: 2rem 0;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(0,0,0,.2);

        span {
            margin-right: 1rem;
            display: inline-block
        }
    }
    &__logo {
        @include typo('display', 2);
    }
}
</style>