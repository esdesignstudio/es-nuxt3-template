import { en } from '@formkit/i18n'
// import { genesisIcons } from '@formkit/icons'
import stepNumber from '/plugins/formkit/stepNumber.js'
import addAster from '/plugins/formkit/addAster.js'
// import scrollToErrors from '/plugins/formkit/scrollToErrors.js'

// console.log('addAster', addAster)

// 設定參考 https://formkit.com/essentials/internationalization 
// key參考 https://github.com/formkit/formkit/blob/master/packages/i18n/src/locales/en.ts
export default ({
    icons: {
        // ...genesisIcons,
    },
    /**
     * Validation rules to add or override.
     * See validation docs.
     */
    rules: {},
    /**
     * Locales to register.
     * See internationalization docs.
     */
    locales: { en },
    /**
     * Input definitions to add or override.
     * See docs on custom inputs.
     */
    inputs: {
        stepNumber
    },
    /**
     * An array of plugin functions
     */
    plugins: [
        addAster.addAsteriskPlugin, 
        // scrollToErrors.scrollToErrors
    ],
    /**
     * Explicit locale messages to override.
     * See internationalization docs.
     */
    messages: {
        en: {
            validation: {
                email: '請輸入正確的 Email 格式',
                required({ name }) {
                    return `${name}為必填`
                }
            }
        }
    },
    /**
     * The currently active locale. This is actually a config setting, but
     * defaultConfig accepts it as a top-level value to improve the DX.
     */
    locale: 'en',
    /**
     * Any of the above node options are accepted.
     */
})