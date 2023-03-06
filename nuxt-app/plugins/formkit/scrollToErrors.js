/**
 * A little plugin that automatically scrolls to the first error.
 **/
function scrollToErrors(node) {
    if (node.props.type === 'form') {
      function scrollTo(node) {
        const el = document.getElementById(node.props.id)
        const parent = document.getElementById(node.props.id).closest("form")
        if (parent !== undefined || parent !== null) {
            // 先擋一下footer滾動到最上面的問題
            if (parent.offsetTop !== 107) {
                window.scrollTo({
                    top: parent.offsetTop,
                    behavior: 'smooth'
                })
            }
            // console.log('parent', parent)
            // console.log('parent.offsetTop', parent.offsetTop)
        } else {
            window.scrollTo({
                top: el.offsetTop,
                behavior: 'smooth'
            })
        }
      }
    
      function scrollToErrors () {
        node.walk(child => {
          // Check if this child has errors
          if (child.ledger.value('blocking') || child.ledger.value('errors')) {
            // We found an input with validation errors
            scrollTo(child)
            // Stop searching
            return false
          }
        }, true)
      }
      node.props.onSubmitInvalid = scrollToErrors
      node.on('unsettled:errors', scrollToErrors)

    //   console.log('表單錯誤', node.props)
    }
    return false
  }
  
  
  export default {
    scrollToErrors
  }