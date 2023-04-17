import { en, zh } from '@formkit/i18n'
// import { genesisIcons } from '@formkit/icons'
import stepNumber from '/plugins/formkit/stepNumber.js'
import addAster from '/plugins/formkit/addAster.js'
import dropdown from '/plugins/formkit/dropdown.js'
import taglist from '/plugins/formkit/taglist.js'

// 設定參考 https://formkit.com/essentials/internationalization 
// key參考 https://github.com/formkit/formkit/blob/master/packages/i18n/src/locales/en.ts
export default ({
    // icons: {
    //     ...genesisIcons,
    // },
    /**
     * Validation rules to add or override.
     * See validation docs.
     */
    rules: {},
    locales: { en, zh },
    inputs: {
        stepNumber, dropdown, taglist
    },
    plugins: [
        addAster.addAsteriskPlugin,
    ],
    messages: {
        zh: {
            ui: {
                add: '增加',
                incomplete: '抱歉，您尚未輸入完整以上資訊',
            },
            validation: {
                email: '請輸入正確的 Email 格式',
                required({ name }) {
                    return `${name}為必填`
                },
                is({ name }) {
                    return `${name} 輸入了錯誤的格式`
                },
                matches({ name }) {
                    return `${name} 輸入了錯誤的格式`
                },
                length({ name, args: [first = 0, second = Infinity] }) {
                    const min = Number(first) <= Number(second) ? first : second
                    const max = Number(second) >= Number(first) ? second : first
                    if (min == 1 && max === Infinity) {
                        return `${name} must be at least one character.`
                    }
                    if (min == 0 && max) {
                        return `${name} must be less than or equal to ${max} characters.`
                    }
                    if (min === max) {
                        return `${name} should be ${max} characters long.`
                    }
                    if (min && max === Infinity) {
                        return `${name} 至少要輸入 ${min} 個字數.`
                    }
                    return `${name} 至少要輸入 ${min} 個字數.`
                },              
            }
        }
    },
    locale: 'zh',
})