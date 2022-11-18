<template>
    <div class="blog-category">
        <nav>
            <ul>
                <li
                    v-for="(cate, index) in data"
                    :key="index"
                >
                    <NuxtLink :to="'/blog-category/' + cate.slug">
                        {{ cate.name }}
                    </NuxtLink>
                </li>
            </ul>
        </nav>
    </div>
</template>
<script setup>
    const props = defineProps({
        data: {
            type: Object,
            default: {},
        },
    })
</script>
<style lang="scss">
    $class-name: blog-category;
    .#{$class-name} {
        margin-bottom: 2rem;
        ul {
            text-align: center;
        }
        li {
            display: inline-block;
        }
        a {
            padding: 10px 1.5rem;
            margin: 0 1rem 1rem 0;
            border: 1px solid rgb(0,0,0, .2);

            &:hover {
                opacity: .5;
            }
        }
    }
</style>