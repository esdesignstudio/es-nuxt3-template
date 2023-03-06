<template>
    <div class="blog-archive category">
        <div class="container">
            <h1>{{ pageData?.cate_title }}</h1>
            <ul class="blog-archive__wrap">
                <transition-group name="list">
                <li
                    v-for="post in pageData?.posts"
                    :key="post.id"
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
            />
        </div>
        <Footer />
    </div>
</template>
<script setup>
    const route = useRoute()
    const router = useRouter()

    const { data: pageData } = await useAsyncData(
        'get_archive_blog' + route.params.slug,
        () => $fetch( useRuntimeConfig().apiUrl + '/get_archive_blog', {
            method: 'POST',
            body: {
                cat_slug: route.params.slug,
                page: 1,
                posts_per_page: 4
                // locale: locale.value 多國語言要自帶
            }
        })
    )
    console.log('pageData', pageData.value)
    // 換頁
    router.beforeEach((to, from) => {
        $fetch(useRuntimeConfig().apiUrl + '/get_archive_blog', {
            method: 'POST',
            body: {
                cat_slug: route.params.slug,
                page: to.query.page || 1,
                posts_per_page: 4
            }
        }).then(res => {
            pageData.value = res
        })
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
    h1 {
        @include typo('heading', 2);

        text-align: center;
        margin-bottom: 2rem;
    }
    &.category {

    }
}
</style>