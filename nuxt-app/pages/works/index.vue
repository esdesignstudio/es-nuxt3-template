<template>
    <div class="archive-works">
        <Title>Works ｜ ES Template</Title>
        <div class="archive-works__wrapper container">
            <h1 class="archive-works__wrapper-title">WORKS</h1>
            <transition name="fade">
                <div
                    class="archive-works__content"
                    v-if="pageData?.works"
                >
                    <ul class="archive-works__works">
                        <li
                            v-for="works in pageData?.works"
                            :key="works.id"
                            class="archive-works__works-item"
                        >
                            <nuxt-link
                                :to="`/works/${works.post.post_title}`"
                                
                            >
                                <figure>
                                    <img
                                        :src="works.cover.url"
                                        :alt="works.post.post_title"
                                    >
                                </figure>
                                <h2 class="archive-works__works-item-title">{{ works.post.post_title }}</h2>
                            </nuxt-link>
                        </li>
                    </ul>
                    <PagePagination
                        :length="pageData?.max_num_pages"
                    ></PagePagination>
                </div>
            </transition>
        </div>
    </div>
</template>
<script setup>
    import useStore from "~~/store"
    import { storeToRefs } from 'pinia'

    const store = useStore()
    const { $ESscroll, $viewportInfo } = useNuxtApp()
    const { isLoading, currentPage } = storeToRefs(store)

    const pageData = ref(null)
    const firstScroll = ref(false)

    onMounted(async() => {
        $ESscroll.stop()
        fetchData(0, 0).then(res => {
            pageData.value = res.data
            $ESscroll.start()
            $ESscroll.update()
        })

        nextTick(() => {
            $ESscroll.on('scroll', () => {
                if ($ESscroll.scroll.instance.scroll.y > 200) {
                    firstScroll.value = true
                }
            })
        })
    })

    onUnmounted(() => {
        pageData.value = null
    })

    watch(() => currentPage.value, (val) => {
        console.log(val)
        fetchData(0, val).then(res => {
            pageData.value = res.data
            $ESscroll.update()
        })
    })

    const fetchData = (term, page) => {
        return new Promise(resolve => {
            $fetch('/works', {
                method: 'POST',
                baseURL: useRuntimeConfig().public.apiBase,
                body: {
                    term_id: term,
                    per_page: 2,
                    paged: page + 1,
                }
            }).then(res => {
                console.log(res.data)
                resolve(res)
                setTimeout(() => {
                    store.addLoadingStack(store.loadImage())
                    store.waitLoading()
                }, 1)
            }).catch(err => {
                console.log(err)
            })
        })
    }
</script>
<style lang="scss">
    $class-name: archive-works;
    .#{$class-name} {
        &__wrapper {
            display: flex;
            flex-direction: column;

            @include media-breakpoint-down(tablet) {
                flex-direction: column;
            }

            &-title {
                @include typo('heading', 1);
            }
        }

        &__content {
            @include size(100%, auto);

            display: flex;
            flex-direction: column;
            padding: 40px 0;

            @include media-breakpoint-down(tablet) {
                @include size(100%, auto);
            }
        }

        &__works {
            @include set-col(4, 12, 0);

            &-item {
                margin-bottom: 24px;

                &:last-child {
                    margin-bottom: 0;
                }

                &-title {
                    @include typo('heading', 3);
                }

                figure {
                    @include size(100%, auto);

                    margin-bottom: 8px;
                    overflow: hidden;
                    border-radius: 12px;

                    img {
                        @include size(100%);

                        object-fit: cover;
                    }
                }
            }
        }
    }
</style>