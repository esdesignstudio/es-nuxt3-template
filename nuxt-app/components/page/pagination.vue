<template>
    <div
        class="page-pagination"
        v-if="length > 1"
    >
        <ul class="page-pagination__list">
            <li
                class="page-pagination__list-item -en"
                :class="{ '-active': store.globalState.currentPage === key }"
                v-for="(item, key) in length"
                :key="key"
                @mouseover="store.changeCursorState('hover', '')"
                @mouseleave="store.changeCursorState('default', '')"
                @click="store.changeCurrentPage(key)"
            >
                {{ key + 1 }}
            </li>
        </ul>
    </div>
</template>
<script setup>

    const store = useStore()
    const props = defineProps({
        length: {
            type: Number,
            default: 1,
        }
    })
</script>
<style lang="scss">
    $class-name: page-pagination;
    .#{$class-name} {
        display: flex;
        justify-content: center;

        &__list {
            display: flex;

            &-item {
                @include typo('head', 2);

                margin-right: 12px;
                opacity: 0.5;
                transition: opacity 0.3s;
                cursor: pointer;

                &:last-child {
                    margin-right: 0;
                }

                &:hover {
                    opacity: 1;
                }

                &.-active {
                    opacity: 1;
                }
            }
        }
    }
</style>