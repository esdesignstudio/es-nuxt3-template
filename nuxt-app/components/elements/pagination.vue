<template>
    <div class="pagination">
        <ul>
            <li
                v-for="page in data.links"
                :key="page.label"
                :class="{'active': page.active}"
            >
                <button
                    @click="pagechange(page)"
                    :class="{
                        '-hide': page.slug === false,
                        '-prev': page.label === 'prev',
                        '-next': page.label === 'next',
                    }"
                >
                    <p v-text="page.label"></p>
                </button>
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

    const emit = defineEmits(['pagechange'])
    const pagechange = (page) => {
        emit('pagechange', page.slug.split('=')[1])
        // $LCscroll.scrollTo(0, {
        //     duration: 100,
        // })
    }
</script>
<style lang="scss">
    $class-name: pagination;
    .#{$class-name} {
        ul {
            text-align: center;
        }
        li {
            display: inline-block;

            button {
                padding: 0 1rem;
            }
        }
    }
</style>