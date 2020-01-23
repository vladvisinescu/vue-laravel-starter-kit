import Vue from 'vue'
import Vuex from 'vuex'

import auth from './store/modules/auth/auth'
import messages from "./store/modules/messages"
import global from "./store/modules/global";

Vue.use(Vuex);

const store =  new Vuex.Store({

    strict: process.env.MIX_APP_DEBUG,

    modules: {
        auth,
        messages,
        global
    },
});

export default store
