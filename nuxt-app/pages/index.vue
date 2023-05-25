<template>
    <div class="page-index">
        <div class="container">
            <div class="page-index__content">
                <h1>細節不單只是細節，它們成就了設計<br>我們追求那小小會心一驚的設計<br>The details are not the details. They make the design.</h1>
                <!-- <pre>{{ pageData }}</pre> -->
            </div>
        </div>
        <Footer />
    </div>
</template>
<script setup>

    // const { data: pageData } = await useAsyncData(
    //     'get_page_home',
    //     () => $fetch( useRuntimeConfig().apiUrl + '/get_page_home', {
    //         method: 'POST',
    //         body: {
    //             id: 47,
    //             // locale: locale.value 多國語言要自帶
    //         }
    //     }), {
    //         transform: (res) =>{ 
    //             return res.data
    //         }
    //     }
    // )

    const pageloaded = usePageLoaded()
    // if (pageData.value) {
        pageloaded.value = true
    // } else {
    //     navigateTo('/404')
    // }


    // console.log('pageData', pageData.value)
</script>
<style lang="scss">
    $class-name: page-index;
    .#{$class-name} {
        &__content {
            h1 {
                @include typo('head', 1);

                padding: 5rem 0;
            }
        }
    }
</style>