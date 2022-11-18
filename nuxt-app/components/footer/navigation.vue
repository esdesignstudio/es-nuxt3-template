<template>
    <div class="footer-navigation">
        <ul>
            <li
                v-for="(item, index) in navs"
                :key="index"
            >
                <NuxtLink
                    :to="item.link"
                >
                    {{ item.name }}
                </NuxtLink>
            </li>
        </ul>
    </div>
</template>
<script setup>
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
        },
    ])
</script>
<style lang="scss">
    $class-name: footer-navigation;
    .#{$class-name} {
        ul {
            display: flex;
            li {
                a {
                    padding-left: 1rem;
                    &:hover {
                        opacity: .5;
                    }
                }
            }
        }
    }
</style>