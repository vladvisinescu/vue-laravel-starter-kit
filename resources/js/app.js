import axios from "axios";

window._ = require('lodash')
require('./bootstrap')

import store from './store'
import User from "./common/auth/user";
import router from './router'
import Vue from 'vue'
import App from './views/App'
import {sync} from './common/vuex-router-sync'

// Sync router data to Vuex store
const unsync = sync(store, router)

// Stop Vue from annoying the console
Vue.config.productionTip = false

window.axios = require('axios')
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
window.language = document.documentElement.getAttribute('lang')
window.axios.defaults.withCredentials = true

// axios.interceptors.request.use(request => {
//     console.info('Axios request interceptor', request)
//
//     return request
// })

axios.interceptors.response.use(
    res => {
        return res
    },
    (error) => {

        /*
        * This is the catch before the actual catch.
        *
        * If we get an authentication error, we should try and refresh the JWT expiry date (aka getting a new token)
        * using the refresh token stored in the `refresh_token` httpOnly cookie
        * and then rerun the same request again.
        *
        * Note: This interceptor is currently not at its best implementation
        */

        // First backup original request to be able to re-run it, duuuh
        const originalRequest = error.config;

        // Second, check error code
        if (error.response.status === 401) {

            if (error.response.data.message == 'Invalid credentials.') {
                return new Promise((resolve, reject) => reject(error))
            }

            // It looks like the user is not authenticated or his credentials have expired / are invalid (login)
            // Try and refresh the user's JWT...
            return User.refreshSession().then(data => {

                // ... if successful, set Axios's auth header accordingly
                originalRequest.headers['Authorization'] = 'Bearer ' + data.access_token;

                // ... and rerun the same request again
                return new Promise((resolve, reject) => {
                    axios
                        .request(originalRequest)
                        .then(response => resolve(response)) // ... can't believe it actually worked!
                        .catch(error => reject(error)) // ... F^)$!!"$~@:#!
                })
            }).catch(() => {
                // ... OK, refreshing the JWT didn't work so we do a bit of clean-up for security blah blah blah
                return User.logout('/').then(() => {
                    // ... and fail miserably
                    return new Promise((resolve, reject) => reject(error))
                })
            })
        } else {

            return Promise.reject(error)

            // return User.logout().then(() => {
            //     return new Promise((resolve, reject) => reject(error))
            // })
        }
    }
)

Vue.mixin(require('./common/translations').default)
Vue.mixin(require('./common/messages').default)
Vue.mixin(require('./common/debug').default)

Vue.component('big-message', require('./components/Messages/BigMessage').default)
Vue.component('little-message', require('./components/Messages/LittleMessage').default)
Vue.component('main-navigation', require('./components/Navigation').default)

const app = new Vue({
    el: '#app',
    components: {App},
    router,
    store,

    beforeCreate() {
        if (!_.includes([null, 'null'], window.localStorage.getItem('jwt_token')))
            User.login({access_token: window.localStorage.getItem('jwt_token')})
    },

    beforeDestroy() {
        unsync()
    }
})
