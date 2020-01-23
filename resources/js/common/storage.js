import store from 'store'
import localStorage from 'store/storages/localStorage'
import cookieStorage from 'store/storages/cookieStorage'
import update from 'store/plugins/update'
import expire from 'store/plugins/expire'

// Alias so it doesn't collide with Vuex
let storage = store.createStore([localStorage, cookieStorage], [update, expire])

export default storage
