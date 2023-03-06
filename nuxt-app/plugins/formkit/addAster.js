// 必填欄位樣式
// sectionsSchema 設定參考 https://formkit.com/essentials/schema
// 範例參考 https://formkit.com/playground?fkv=1.0.0-beta.12&fileTab=formkit.config.js&files=jc%5B%28%27name%21%27Playground.vue%40%3Cscript+setupU0%3C%2FscriptU0%3CtemplateU7email6email%5EEnter+an+email+addressH0-57text6name4%2FU7Z6Z480-57Z6Z457cX6cX480-5%3C%2FtemplateU0%3Cstyle+scopedU%2F*0vanilla+CSS+can+go+here.0Keep+styles+scoped+to+avoid+multiple+files0overwriting+each+other+in+the+render+output.0*%2F0%3C%2FstyleU%27%25%2C%28%27name%21%27formkit.config.js%40%23OBNnode%2BNDtypLcX%22+%7C%7C+DtypLZ%22%7D+%26%26+DopJs00funcJ+zNnode%7DQ0-node.on%7B%22created%22%2CN%2BQI%23s_FnB+%3F%3BI%3FBNGBQ%29%2BQI-%23isRVB+DparsedRules.some%7BruleB%3E+rule.namLrV%22%7D%3BII-ifNisRV%7DQI--if%7BO%7Bnode%7D%7DQI---G.legend9---%29I--%29+elseQI---G.label9---%29I--%29I-%29I-return+s_Fn%7BG%7D%3BI%290-%29%7D0%2900export+defaultQ0-plugins%3A+%5Bz%5D0%290%27%7Eremovable%21true%25%5D-++0%5Cn4%5EWhat+is+your+nameH0-5-validaJ%3DHrVH0-%2FU6HIlabel%3DHYour+7-%3CFormKitItype%3DH8-%3AopJs%3DH%5B%22opJ1TopJ2TopJ3%22%5DH9BQI----children%3A+%5B%22%24labelT*%22%5DIB+%3DDnode.props.GsecJsS_H%5C%27I0--JtionLeB%3D%3D+%22N+%7BOisCXAndRadioMultipleQ+%28T%22%2C+%22U%3E0VequiredXheckboxZradio_chemazaddAsteriskPlugin%23const+%25%7Eadded%21true%29%2B%7DB%3E%3FDdefiniJ.s_%40%27%7Eeditor%21%27%5EHIhelp%3DH%01%5E%40%3F%2B%25%23z_ZXVUTQONLJIHGDB9876540-_&imports=jc%28%27name%21%27ImportMap%27%7Eeditor%21%27%28*+1vue%5C%211https%3A%2F%2Fcdn.jsdelivr.net%2Fnpm%2Fvue%403%2Fdist%2Fvue.esm-browser.min.js0*%29*%27%29*%5Cn0%5C%271+0%0110*_&css-framework=genesis

const isCheckboxAndRadioMultiple = (node) => (node.props.type === 'checkbox' || node.props.type === 'radio') && node.props.options

function addAsteriskPlugin (node) {
  node.on('created', () => {
    const schemaFn = node.props.definition.schema;
    node.props.definition.schema = (sectionsSchema = {}) => {
      const isRequired = node.props.parsedRules.some(rule => rule.name === 'required');
    
      if (isRequired) {
        if(isCheckboxAndRadioMultiple(node)) {
          sectionsSchema.legend = {
            children: ['$label', '*']
          }
        } else {
          if (node.props.type !== 'select') {
                sectionsSchema.label = {
                    children: ['$label', {
                        $el: 'div',
                        attrs: {
                            class: 'required',
                            'data-foo': 'bar'
                        },
                        children: ''
                    }]
                }
          }
        //   console.log('表單', node.parent.props.id)
        }
      }
      return schemaFn(sectionsSchema);
    }
  })
}

export default {
    addAsteriskPlugin
}