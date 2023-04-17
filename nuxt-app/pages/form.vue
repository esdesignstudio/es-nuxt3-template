<template>
    <div class="display-form">
        <div class="container">
            <h1>表格展示</h1>
            <div class="display-form__content">
                <div>
                    <h2>登入表格範例</h2>
                    <FormKit
                        type="form"
                        id="login-submit"
                        incomplete-message="請填寫以上欄位"
                        :actions="false"
                        @submit="submitHandler"
                        :submit-attrs="{wrapperClass: 'es-button'}"
                    >
                        <FormKit 
                            label="電子郵件"
                            type="text"
                            name="username"
                            autocomplete
                            validation="required|email"
                            placeholder="請輸入電子郵件"
                        />
                        <div class="member-login__password">
                            <FormKit
                                type="password"
                                name="password"
                                label="密碼"
                                autocomplete
                                validation="required|length:6|matches:/[^a-zA-Z]/"
                                validation-visibility="live"
                            />
                            <NuxtLink to="/forgotpass">
                                {{ $t('member.forgot_pass') }}
                            </NuxtLink>
                        </div>
                    
                        <FormKit
                            type="checkbox"
                            label="記得我"
                            name="remember"
                            :value="true"
                        />
                        <div class="member-login__form-button">
                            <FormKit
                                type="submit"
                                label="登入"
                            />
                        </div>
                    </FormKit>
                </div>
                <div>
                    <h2>客製化功能範例</h2>
                    
                    <FormKit
                        type="taglist"
                        title="分類"
                        cleartext="不限制"
                        v-model="taglistData.selected"
                        :options="taglistData.options"
                    />
                    {{ taglistData.selected }}

                    <FormKit
                        type="dropdown"
                        title="下拉選單"
                        cleartext="不限制"
                        v-model="dropdownSelected"
                        :options="taglistData.options"
                    />

                </div>
            </div>
        </div>
        <Footer />
    </div>
</template>
<script setup>

    const pageloaded = usePageLoaded()
    pageloaded.value = true

    const taglistData = reactive({
            selected: [],
            options: [
                {
                    label: '選項1',
                    value: 'option1',
                }, {
                    label: '選項2',
                    value: 'option2',
                }, {
                    label: '選項3',
                    value: 'option3',
                }, {
                    label: '選項4',
                    value: 'option4',
                }, {
                    label: '選項5',
                    value: 'option5',
                }, {
                    label: '選項6',
                    value: 'option6',
                }
            ]
    })
    const dropdownSelected = ref()

    const submitHandler = (data) => {
        console.log('submitHandler', data)
    }
    

</script>
<style lang="scss">
    $class-name: display-form;
    .#{$class-name} {
        padding: 5rem 0;

        h1 {
            @include typo('head', 1);

            margin-bottom: 2rem;
        }

        &__content {
            display: flex;
            
            h2 {
                @include typo('head', 2);

                margin-bottom: 2rem;
            }

            > div {
                flex: 1;
            }
        }
    }
</style>