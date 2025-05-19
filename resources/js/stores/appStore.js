import {defineStore} from 'pinia'
import {useToast} from "vue-toastification";

export const useAppStore = defineStore('appStore', {
    state: () => ({
        user_info: {},
    }),
    actions: {
        setUserInfo(user) {
            console.log("User Info")
            console.log(user)
            this.user_info = user
        },
        
        getUserInfo() {
            this.user_info
        },
        
        clearUserInfo() {
            this.user_info = {}
        },
    },
})
