import axios from 'axios'

// initial state
const state = {
    loaders: {
        api: false
    }
}

// getters
const getters = {
    getApiLoader(state) {
        return state.loaders.api
    }
}

// actions
const actions = {
    toggleApiLoader({ commit, state }, data) {
        if (_.isEmpty(data)) {
            commit('setApiLoader', !state.loaders.api)
        } else {
            commit('setApiLoader', data)
        }
    }
}

// mutations
const mutations = {
    setApiLoader(state, data) {
        state.loaders.api = data
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
