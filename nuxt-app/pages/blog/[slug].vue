<template>
    <div class="blog-single">
        <div class="container">
            <pre>{{ pageData }}</pre>
        </div>
    </div>
</template>
<script setup>
    const route = useRoute()
    const { data: pageData } = await useAsyncData(
        'get_single_blog' + route.params.slug,
        () => $fetch( useRuntimeConfig().apiUrl + '/get_single_blog', {
            method: 'POST',
            body: {
                slug: route.params.slug,
                // locale: locale.value 多國語言要自帶
            }
        })
    )

    const pageloaded = usePageLoaded()
    if (pageData.value) {
        pageloaded.value = true
    } else {
        navigateTo('/404')
    }


    console.log('pageData', pageData.value)
</script>
<style lang="scss">
    $class-name: blog-single;
    .#{$class-name} {
        padding: 5rem 0;
    }
</style>