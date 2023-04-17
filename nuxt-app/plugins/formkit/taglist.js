import { createInput } from '@formkit/vue'
import { reactive } from 'vue'

const icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 7"><path d="M8,6.5c-.13,0-.26-.05-.35-.15L3.15,1.85c-.2-.2-.2-.51,0-.71,.2-.2,.51-.2,.71,0l4.15,4.15L12.15,1.15c.2-.2,.51-.2,.71,0,.2,.2,.2,.51,0,.71l-4.5,4.5c-.1,.1-.23,.15-.35,.15Z" fill="currentColor"></path></svg>'
const taglistInputSchema = [
    {
        $el: 'button',
        children: [{
            $el: 'span',
            children: '$title + ： + $showtext'
        }],
        attrs: {
            type: 'button',
            class: 'es-taglist formkit-input',
            onClick: '$handlers.openDropDown',
            'data-id': '$id',
            'data-name': '$name'
        }
    }, {
        $el: 'span',
        attrs: {
            class: 'formkit-select-icon formkit-icon',
            innerHTML: icon,
        }
    }, {
        $el: 'div',
        if: '$isopen',
        attrs: {
            class: 'es-select-box',
        },
        children: [{
            $el: 'div',
            attrs: {
                class: 'es-select-box__wrap'
            },
            children: [
                { // 清除選項
                    $el: 'div',
                    attrs: {
                        onClick: '$handlers.clearSelections',
                        class: 'es-select-box__option -clear',
                        'data-checked' : '$clearall'
                    },
                    children: [
                        {
                            $el: 'span',
                            attrs: {
                                class: 'es-select-box__option-icon'
                            }
                        }, 
                        '$cleartext'
                    ]
                }, { // 子選單
                    $el: 'div',
                    for: ['option', '$options'],
                    attrs: {
                        class: 'es-select-box__option',
                        onClick: '$handlers.selectOption($option)',
                        'data-checked' : '$option.checked'
                    },
                    children: [
                        {
                            $el: 'span',
                            attrs: {
                                class: 'es-select-box__option-icon'
                            }
                        }, 
                        '$option.label'
                    ]
                }
            ]
        }]
    }
]

const addOpenDropDown = (node) => {
    node.on('created', () => {
        node.props.isOpen = false // props 需要在 created 事件中定義
        node.props.clearall = true
        node.props.selections = reactive([])
        node.props.showtext = node.props.cleartext


        // 關閉下拉
        let currentDom // 紀錄當前點擊的元素
        const closeDropDown = (e) => {
            const dropbox = currentDom.nextElementSibling.nextElementSibling
            if ( !currentDom.contains(e.target) && !dropbox.contains(e.target) ) {
                node.props.isopen = false;
                document.removeEventListener('click', closeDropDown)
            }
        }
        // 開關下拉選單
        node.context.handlers.openDropDown = (e) => {
            node.props.isopen = !node.props.isopen
            currentDom = e.currentTarget // 找不到 formkit 怎麼帶入當前元素，只好操作 dom 解決
            
            if (node.props.isopen) {
                document.addEventListener('click', closeDropDown)
            } else {
                document.removeEventListener('click', closeDropDown)
            }
        }
        
        // 點擊子選單
        node.context.handlers.selectOption = (option) => () => {

            // 取消清除全部
            node.props.clearall = false
            
            // 選取
            if (option.checked) {
                option.checked = false
                node.props.selections.splice(node.props.selections.indexOf(option), 1)
            } else {
                option.checked = true
                node.props.selections.push(option)
            }

            // 組裝顯示文字
            let newLabelText = ''
            node.props.selections.forEach((item, index) => {
                newLabelText += item.label
                if (index < node.props.selections.length -1) {
                    newLabelText += '、'
                }
            })
            node.props.showtext = newLabelText

            // 推回父層
            node.input(node.props.selections)
        }
        // 清除選項
        node.context.handlers.clearSelections = (e) => {
            if (!node.props.clearall) {
                node.props.showtext = node.props.cleartext
                node.props.clearall = true
                node.props.options.forEach(item => {
                    item.checked = false
                })
                node.props.selections.splice(0, node.props.selections.length) // 因為是 reactive 不能直接等於，要自己剪去
            }
        }
    })
}

export default createInput( taglistInputSchema, {
    props: ['options', 'isopen', 'showtext', 'title', 'selections', 'clearall', 'cleartext'],
    features: [addOpenDropDown],
  }
)