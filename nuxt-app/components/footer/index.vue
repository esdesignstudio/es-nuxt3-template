<template>
    <div class="footer">
        <div class="container">
            <div class="footer__wrap">
                <div class="footer__logo">
                    <NuxtLink to="/">
                        ES Design
                    </NuxtLink>
                </div>
                <div class="footer__navigation">
                    <FooterNavigation />
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
    
</script>
<style lang="scss">
$class-name: footer;
.#{$class-name} {

    &__wrap {
        display: flex;
        justify-content: space-between;
    }

}
</style>