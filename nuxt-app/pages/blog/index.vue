<template>
    <div class="blog-archive">
        <div class="container">
            <h1>全部</h1>
            <ElementsBlogCategory :data="pageData?.categories" />
            <ul class="blog-archive__wrap">
                <transition-group name="list">
                <li
                    v-for="post in pageData?.posts"
                    :key="post.id"
                    v-inview
                    v-fade
                >
                    <NuxtLink
                        :to="'/blog/' + post.slug"
                    >
                        {{ post.title }}
                    </NuxtLink>
                </li>
                </transition-group>
            </ul>

            <ElementsPagination
                v-if="pageData?.paginations"
                :data="pageData?.paginations"
                @pagechange="changePage"
            />
        </div>
        <Footer />
    </div>
</template>
<script setup>
    const route = useRoute()
    const router = useRouter()

    const { data: pageData, pending, refresh: refreshData } = await useAsyncData(
        'get_archive_blog-all',
        () => $fetch( useRuntimeConfig().apiUrl + '/get_archive_blog', {
            method: 'POST',
            body: {
                page: route.query.page || 1,
                posts_per_page: 4
                // locale: locale.value 多國語言要自帶
            }
        })
    )

    // 換頁
    const changePage = (page) => {
        navigateTo({
            path: '/blog',
            query: {
                ...route.query,
                page: page
            }
        })
    }

    // 網址變化重新整理資料
    watch(() => route.query, async (query) => {
        document.querySelectorAll('.card-product').forEach((item) => {
            item.classList.remove('is-inview')
        })
        await setTimeout(() => {
            refreshData()
        }, 100);
    })
    
    const pageloaded = usePageLoaded()
    if (pageData.value) {
        pageloaded.value = true
    } else {
        navigateTo('/404')
    }

</script>
<style lang="scss">
$class-name: blog-archive;
.#{$class-name} {
    padding-top: 5rem;
    
    &__wrap {
        li {
            a {
                @include size(100%, auto);
                @include typo('head', 2);

                padding: 2rem;
                margin-bottom: 2rem;
                border: 1px solid rgba(0,0,0,.2);

                &:hover {
                    opacity: 0.5;
                }
            }
        }
    }
}
</style>