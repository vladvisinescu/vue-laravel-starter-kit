<template>
    <div class="toast fade" :class="showClass" role="alert" aria-live="assertive" aria-atomic="true" @mouseenter="resetTimer" @mouseleave="destroy(false)">
        <div class="toast-header" v-if="message.title">
            <i class="fa fa-fw mr-2" :class="message.icon"></i>
            <strong class="mr-auto">{{ message.title }}</strong>
            <a href="" @click.prevent="destroy(true)" class="toast-close"><i class="fa fa-times fa-fw"></i></a>
        </div>
        <div class="toast-body">
            {{ message.body}}
        </div>
<!--        <div class="progress">-->
<!--            <div class="progress-bar" role="progressbar" :style="{width: progress + '%'}" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100"></div>-->
<!--        </div>-->
    </div>
</template>

<script>

    export default {

        props: ['message'],

        data() {
            return {
                show: false,
                timer: null
            }
        },

        computed: {
            showClass: function () {
                return {
                    'show': this.show,
                    ['toast-' + this.message.type]: this.show
                }
            }
        },

        methods: {
            display: function () {
                this.show = true
            },

            destroy(instant = false) {
                this.timer = setTimeout(() => {
                    this.show = false
                    this.$store.dispatch('messages/removeLittleMessage', this.message)
                    return
                }, instant ? 1 : this.message.duration)
            },

            resetTimer() {
                clearTimeout(this.timer)
            }
        },

        created() {
            this.display()
            this.destroy()
        }
    }
</script>
