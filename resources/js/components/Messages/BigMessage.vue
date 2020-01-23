<template>
    <div class="" v-if="$store.getters['messages/showBigMessage'] == name">
        <transition name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper" @click.prevent="$store.commit('messages/toggleBigMessage', '')">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><slot name="header">default header</slot></h5>
                                <button type="button" class="close" @click.prevent="$store.commit('messages/toggleBigMessage', '')">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <slot name="body">
                                    default body
                                </slot>
                            </div>
                            <div class="modal-footer">
                                <slot name="footer">
                                    <button type="button" class="btn btn-secondary" @click.prevent="$store.commit('messages/toggleBigMessage', '')">Close</button>
                                </slot>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {

        props: ['name'],

        data () {
            return {

            }
        },

        computed: {
            //
        },

        created() {

        }
    }
</script>
