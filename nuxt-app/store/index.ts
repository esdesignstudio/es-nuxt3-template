import { defineStore, _ActionsTree, _GettersTree } from "pinia";
import ImagesLoaded from 'imagesloaded'

const LOADING = Object.freeze({
    MIN_LOAD_TIME: 500,
    LOADING_TYPE_DEFAULT: 'default',
    LOADING_TYPE_AJAX: 'ajax',
})

export interface State {
    isFirstEnter: boolean,
    loadingConfig: object,
    loadingProgress: number,
    loadingStack: Array<any>,
    globalOptions: object,
    cursorState: string,
    cursorText: string,
    currentPage: number,
}

const globalState: State = {
    isFirstEnter: true,
    loadingConfig: {
        waiting: false,
        minTime: LOADING.MIN_LOAD_TIME,
        type: LOADING.LOADING_TYPE_DEFAULT,
    },
    loadingProgress: 0,
    loadingStack: [],
    globalOptions: null,
    cursorState: 'default',
    cursorText: '',
    currentPage: 0,
}

const useStore = defineStore("main", {
  state : () => ({
    globalState
  }),
  actions: {
    addLoadingStack(payload) {
        if (Array.isArray(payload)) {        
            const promise = Promise.all(payload.filter(p => p instanceof Promise))
            this.globalState.loadingStack.push(promise)
            
            return promise
        }
        if (payload instanceof Promise) {
            this.globalState.loadingStack.push(payload)
            
            return payload
        }
    },
    delLoadingStack() {
        this.globalState.loadingStack.shift()
    },
    waitLoading() {
        const startTime = Date.now()
        return Promise.all(this.globalState.loadingStack).then(results => {
            const endTime = Date.now()
            setTimeout(() => {
                results.forEach(result => this.delLoadingStack())
            }, this.globalState.loadingConfig.minTime - (endTime - startTime))
        })
    },
    loadImage() {
        return new Promise((resolve) => {
            new ImagesLoaded('#__nuxt',{ background: '[data-background]' } ,(instance) => {
                resolve(instance)
            }).on('progress', (instance, image) => {
                this.globalState.loadingProgress = instance.progressedCount / instance.images.length
            })
        })
    },
    changeCursorState(state, text) {
        this.globalState.cursorState = state
        this.globalState.cursorText = text
    },
    changeCurrentPage(page) {
        this.globalState.currentPage = page
    }
  },
  getters: {
    isLoading: (state):boolean => {
        return !!state.globalState.loadingStack.length
    },
    loadingProgress: (state):number => {
        return state.globalState.loadingProgress
    },
    cursorState: (state):string => {
        return state.globalState.cursorState
    },
    cursorText: (state):string => {
        return state.globalState.cursorText
    },
    currentPage: (state):number => {
        return state.globalState.currentPage
    },
  },
})

export default useStore;
