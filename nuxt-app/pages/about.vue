<template>
    <div class="page-about">
        <div class="container">
            <div class="page-about__content">
                <h1>唯有深入思考各個角度，才能真正創造出帥氣的作品<br>- <br>佐藤可士</h1>
                <pre>{{ pageData }}</pre>
            </div>
        </div>
        <Footer />
    </div>
</template>
<script setup>

    const { data: pageData } = await useAsyncData(
        'get_page_custom-about',
        () => $fetch( useRuntimeConfig().apiBase + '/get_page_custom', {
            method: 'POST',
            body: {
                id: 1899,
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
    $class-name: page-about;
    .#{$class-name} {
        h1 {
            @include typo('heading', 1);

            padding: 5rem 0;
        }
    }
</style>