<template>
    <div
        class="dev container"
        data-scroll
        data-scroll-sticky
        data-scroll-target="#wrapper"
    >
        <div class="dev__wrapper">
            <div class="dev__grid">
                <div v-for="num in 12" :key="num" class="dev__grid-col -desktop" />
                <div v-for="num in 8" :key="num" class="dev__grid-col -tablet" />
                <div v-for="num in 4" :key="num" class="dev__grid-col -mobile" />
            </div>
        </div>
    </div>
</template>
<style lang="scss">
    .dev {
        @include size(100vw, 100vh);

        position: fixed;
        display: flex;
        flex-direction: column;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        pointer-events: none;

        &__wrapper {
            @include size(100%);

            position: relative;

            > * {
                margin-bottom: 24px;
            }
        }

        &__grid {
            @include size(100%);

            position: absolute;
            top: 0;
            left: 0;
            display: flex;
            justify-content: space-between;
            pointer-events: none;

            &-col {
                background-color: rgba(255,255,255,.8);
                opacity: 0.1;

                &.-desktop {
                    @include size(calc((100% - (11 * 24px)) / 12), 100%);

                    @include media-breakpoint-down(medium) {
                        display: none;
                    }
                }

                &.-tablet {
                    @include size(calc((100% - (7 * 24px)) / 8), 100%);

                    display: none;

                    @include media-breakpoint-down(medium) {
                        @include media-breakpoint-up(tablet) {
                            display: block;

                        }
                    }
                }

                &.-mobile {
                    @include size(calc((100% - (3 * 24px)) / 4), 100%);

                    display: none;

                    @include media-breakpoint-down(tablet) {
                        display: block;
                    }
                }
            }
        }
    }
</style>