import store from "../store";

const messages = {

    methods: {

        $modal() {
            return {

                hide() {
                    store.commit('messages/toggleBigMessage', '')
                },

                show(name) {
                    store.commit('messages/toggleBigMessage', name)
                },

                toggle(name) {
                    store.getters['messages/showBigMessage'] === name
                        ? store.commit('messages/toggleBigMessage', '')
                        : store.commit('messages/toggleBigMessage', name)
                }
            }
        },

        $toast() {

            return {
                show(data) {
                    store.dispatch('messages/queueLittleMessage', {...data}).then()
                },

                info(data) {
                    this.show({type: 'info', ...data})
                },

                warning(data) {
                    this.show({type: 'warning', ...data})
                },

                success(data) {
                    this.show({type: 'success', ...data})
                }
            }
        }
    }
}

export default messages
