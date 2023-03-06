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
        'get_archive_blog-all',
        () => $fetch( useRuntimeConfig().apiUrl + '/get_archive_blog', {
            method: 'POST',
            body: {
                cat_slug: 'all',
                page: route.query.page || 1,
                posts_per_page: 4
                // locale: locale.value 多國語言要自帶
            }
        })
    )

    // 換頁
    router.beforeEach((to, from) => {
        $fetch(useRuntimeConfig().apiUrl + '/get_archive_blog', {
            method: 'POST',
            body: {
                cat_slug: 'all',
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
    padding-top: 5rem;
    
    &__wrap {
        li {
            a {
                @include size(100%, auto);
                @include typo('heading', 2);

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