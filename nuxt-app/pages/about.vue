<template>
    <div class="page-about">
        <div class="container">
            <div class="page-about__content">
                <h1>唯有深入思考各個角度，才能真正創造出帥氣的作品</h1>
            </div>
        </div>
    </div>
</template>
<script setup>

    const { data: pageData } = await useAsyncData(
        'get_page_custom',
        () => $fetch( useRuntimeConfig().apiBase + '/get_page_custom', {
            method: 'POST',
            body: {
                id: 1899,
                // locale: locale.value 多國語言要自帶
            }
        }), {
            transform: (res) =>{ 
                return res.data
            }
        }
    )

    const pageloaded = usePageLoaded()
    if (pageData.value) {
        pageloaded.value = true
    } else {
        navigateTo('/404')
    }
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