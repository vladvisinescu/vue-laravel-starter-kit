import axios from 'axios'

// initial state
const state = {
    users: {
        data: [],
    },
}

// getters
const getters = {
    allUsers: state => {
        return state.users
    },
}

// actions
const actions = {
    getAllUsers ({ commit }, {}) {
        return new Promise((resolve, reject) => {
            axios.get(route('api.users')).then(response => {
                commit('setUsers', response.data)
                resolve()
            }).catch(error => {
                console.log('getAllUsers', error)
                reject()
            })
        })
    },
}

// mutations
const mutations = {
    setUsers (state, users) {
        state.users.data = users
    },
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
