const state = {
    messages: {
        big: {
            active: ''
        },
        little: {
            icons: {
                info: 'fa-info',
                warning: 'fa-exclamation-circle',
                success: 'fa-check'
            },
            queue: []
        }
    }
}

const getters = {
    showBigMessage(state) {
        return state.messages.big.active
    },

    getLittleMessages(state) {
        return state.messages.little.queue
    }
}

const actions = {
    queueLittleMessage({ commit, state }, data) {
        commit('addLittleMessage', {
            id: state.messages.little.queue.length + 1,
            type: data.type ? data.type : '',
            title: data.title || '',
            body: data.body || '',
            duration: data.duration || 3000,
            icon: data.icon ? data.icon : (data.type ? state.messages.little.icons[data.type] : 'fa-check')
        })
    },

    removeLittleMessage({ commit }, data) {
        commit('removeLittleMessage', data)
    }
}

const mutations = {
    toggleBigMessage(state, name) {
        state.messages.big.active = name
    },

    addLittleMessage(state, message) {
        state.messages.little.queue.unshift(message)
    },

    removeLittleMessage(state, data) {
        const item = state.messages.little.queue.findIndex(item => item.id == data)
        state.messages.little.queue.splice(item, 1)
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
