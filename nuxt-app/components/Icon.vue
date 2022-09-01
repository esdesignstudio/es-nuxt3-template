<template>
    <svg class="icon">
        <use :xlink:href="'#' + name" />
    </svg>
</template>

<script>

export default {
    name: 'Icon',
    props: {
        name: {
            type: String,
            required: true,
        },
    },
}
</script>

<style lang='scss'>
.icon {
    @include size(1rem);

    overflow: hidden;
    fill: currentColor;
}
</style>
