<template>
    <div class="pagination">
        <ul>
            <li
                v-for="page in data.links"
                :key="page.label"
                :class="{'active': page.active}"
            >
                <NuxtLink
                    v-if="page.slug !== false"
                    :to="route.path + page.slug"
                >
                    {{ page.label }}
                </NuxtLink>
            </li>
        </ul>
    </div>
</template>
<script setup>
    const route = useRoute()
    const props = defineProps({
        data: {
            type: Object,
            default: {},
        },
    })

</script>
<style lang="scss">
    $class-name: pagination;
    .#{$class-name} {
        ul {
            text-align: center;
        }
        li {
            display: inline-block;

            a {
                padding: 0 1rem;
            }
        }
    }
</style>