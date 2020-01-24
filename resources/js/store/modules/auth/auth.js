import axios from 'axios'

const state = {
    token: null,
    errors: {
        user: {},
        login: {},
        register: {},
        forgot_password: {}
    },
    user: {
        id: null,
        name: null,
        roles: []
    },
    isAuthenticated: false
}

const getters = {

    getForgotPasswordErrors(state) {
        return state.errors.forgot_password
    },

    getLoginErrors(state) {
        return state.errors.login
    },

    getRegisterErrors(state) {
        return state.errors.register
    },

    isAuthenticated(state) {
        return state.isAuthenticated
    },

    getToken(state) {
        return state.token
    },

    getUser(state) {
        return state.user
    },

    getRoles(state) {
        return state.user ? state.user.roles : null
    }
}

const actions = {

    tryLogin({ commit, state }, { form }) {
        return new Promise((resolve, reject) => {
            axios.post(route('api.login'), form).then(response => {
                commit('setAuth', response.data)
                commit('clearLoginErrors', {})
                resolve(response.data)
            }).catch(error => {
                commit('setLoginErrors', error.response.data.errors || {})
                reject(error)
            })
        })
    },

    tryRegister({ commit, state }, { form }) {
        return new Promise((resolve, reject) => {
            axios.post(route('api.register'), form).then(response => {
                commit('setAuth', response.data)
                commit('clearRegisterErrors', {})
                resolve()
            }).catch(error => {
                commit('setRegisterErrors', error.response.data.errors || {})
                reject(error)
            })
        })
    },

    tryRefresh({ commit, dispatch }, data) {
        return new Promise((resolve, reject) => {
            axios.post(route('api.refresh')).then(response => {
                commit('setAuth', response.data)
                dispatch('getUser', {})
                resolve(response.data)
            }).catch(error => {
                // TODO: Your session has expired blah blah blah
                reject(error)
            })
        })
    },

    tryResetPassword({ commit, dispatch }, { form }) {
        return new Promise((resolve, reject) => {
            axios.post(route('api.password.reset'), form).then(response => {
                // commit('', {})
                resolve(response.data)
            }).catch(error => {
                commit('setForgotPasswordErrors', error.response.data.errors || {})
                reject(error)
            })
        })
    },

    tryLogout({ commit, state }, data) {
        return new Promise((resolve, reject) => {
            axios.post(route('api.logout')).then(response => {
                commit('clearAuth')
                resolve()
            }).catch(error => {
                reject(error)
            })
        })
    },

    getUser({ commit, state }, { data }) {
        return new Promise((resolve, reject) => {
            axios.get(route('api.user'), data).then(response => {
                commit('setUser', response.data)
                resolve(response.data)
            }).catch(error => {
                commit('setUserErrors', error.response.data.errors || {})
                this._vm.$console('getUser', error)
                reject(error)
            })
        })
    }
}

const mutations = {

    /**
     * Log the user into the application
     * @param state
     * @param data
     */
    setAuth(state, data) {
        window.localStorage.setItem('jwt_token', data.access_token)
        if (data.user) window.localStorage.setItem('user', JSON.stringify(data.user))
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + data.access_token
        state.token = data.access_token
        state.user = data.user
        state.isAuthenticated = true
    },

    /**
     * Log the user out of the application
     * @param state
     */
    clearAuth(state) {
        // Reset state variables
        state.isAuthenticated = false
        state.token = null
        state.user = {}

        // Null localStorage variables
        window.localStorage.setItem('user', null)
        window.localStorage.setItem('jwt_token', null)

        // Delete axios auth header
        axios.defaults.headers.common['Authorization'] = ''
    },

    setErrors(state, errors) {
        state.errors = errors
    },

    clearLoginErrors(state, errors) {
        state.errors.login = {}
    },

    setLoginErrors(state, errors) {
        state.errors.login = errors
    },

    clearRegisterErrors(state, errors) {
        state.errors.register = {}
    },

    setRegisterErrors(state, errors) {
        state.errors.register = errors
    },

    clearUserErrors(state, errors) {
        state.errors.user = {}
    },

    setUserErrors(state, errors) {
        state.errors.user = errors
    },

    setToken(state, token) {
        state.token = token
    },

    setUser(state, user) {
        window.localStorage.setItem('user', JSON.stringify(user))
        state.user = user
    },

    setIsAuthenticated(state, data) {
        state.isAuthenticated = data
    },

    setForgotPasswordErrors(state, data) {
        state.errors.forgot_password = data
    },

    clearForgotPasswordErrors(state, errors) {
        state.errors.forgot_password = {}
    },
}

export default {
    namespaced: true,
    actions,
    state,
    getters,
    mutations
}
