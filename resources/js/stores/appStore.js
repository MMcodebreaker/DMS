import {defineStore} from 'pinia'
import {useToast} from "vue-toastification";

export const useAppStore = defineStore('appStore', {
    state: () => ({
        count: 0,
    }),
    getters: {
        doubleCount(state) {
            return state.count * 2
        },
    },
    actions: {
        
    }
})
