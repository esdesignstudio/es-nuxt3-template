<template>
    <div class="navigation-main">
        <ul>
            <li
                v-for="(item, index) in navs"
                :key="index"
            >
                <NuxtLink
                    :to="item.link"
                    :title="item.title"
                >
                    {{ item.name }}
                </NuxtLink>
            </li>
        </ul>
    </div>
</template>
<script setup>
    const props = defineProps({
        template: {
            type: Object,
            default: {},
        },
    })
    const navs = ref([
        {
            name: 'Home',
            link: '/',
        }, {
            name: 'About',
            link: '/about',
        }, {
            name: 'Blog',
            link: '/blog',
        }, {
            name: 'Form',
            link: '/form',
        },
    ])
</script>
<style lang="scss">
$class-name: navigation-main;
.#{$class-name} {
    ul {
        display: flex;

        li {
            a {
                @include typo('head', 2);

                padding-left: 1rem;

                &:hover {
                    opacity: .5;
                }
            }
        }
    }
}
</style>