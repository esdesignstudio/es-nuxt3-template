
import { createInput } from '@formkit/vue'
const icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 7"><path d="M8,6.5c-.13,0-.26-.05-.35-.15L3.15,1.85c-.2-.2-.2-.51,0-.71,.2-.2,.51-.2,.71,0l4.15,4.15L12.15,1.15c.2-.2,.51-.2,.71,0,.2,.2,.2,.51,0,.71l-4.5,4.5c-.1,.1-.23,.15-.35,.15Z" fill="currentColor"></path></svg>'

// 建立 HTML 的 Schema
const dropdownInputSchema = [
    {
        $el: 'button',
        children: [{
            $el: 'span',
            children: '$title + ： + $showtext'
        }],
        bind: '$attrs',
        attrs: {
            type: 'button',
            class: 'es-select formkit-input',
            onClick: '$handlers.openDropDown'
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
            children: [{ // 子選單
                $el: 'div',
                for: ['option', '$options'],
                attrs: {
                    class: 'es-select-box__option',
                    onClick: '$handlers.selectOption($option)',
                },
                children: ['$option.label']
            }]
        }]
    }
]

// 打開下拉選單
const addOpenDropDown = (node) => {
    
    // 增加事件
    node.on('created', () => {
        node.props.isOpen = false // props 需要在 created 事件中定義
        node.props.showtext = node.props.options[0].label


        // 關閉下拉
        let currentDom // 紀錄當前點擊的元素
        const closeDropDown = (e) => {
            if (!currentDom.contains(e.target)) {
                node.props.isopen = false;
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
            node.props.isopen = false
            node.props.showtext = option.label
            node.input(option) // input 是寫入
        }
    })

}

export default createInput( dropdownInputSchema, {
    props: ['options', 'isopen', 'showtext', 'title'],
    features: [addOpenDropDown],
  }
)