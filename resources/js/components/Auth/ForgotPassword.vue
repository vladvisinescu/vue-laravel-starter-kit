<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <div class="card">
                    <h4 class="card-header">
                        {{ __('ui.auth.forgot_title') }}
                    </h4>
                    <div class="card-body">
                        <p>{{ __('auth.forgot_password_text') }}</p>
                        <div class="app-login-form">
                            <form action="" @submit.prevent="tryPasswordRefresh">
                                <div class="form-group">
                                    <label for="email">{{ __('ui.auth.form.username') }}</label>
                                    <input type="text" class="form-control" id="email" v-bind:class="{ 'is-invalid': errors.email }" v-model="form.email">
                                    <span class="help-block invalid-feedback" v-if="errors.email">{{ errors.email[0] }}</span>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-info btn-block">{{ __('ui.auth.forgot_submit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from "vuex";

    export default {

        computed: {
            ...mapGetters({
                errors: 'auth/getForgotPasswordErrors'
            })
        },

        data() {
            return {
                form: {
                    email: ''
                }
            }
        },

        beforeRouteLeave (to, from, next) {
            this.$store.commit('auth/clearForgotPasswordErrors')
            next()
        },

        methods: {
            tryPasswordRefresh() {
                const form = this.form
                this.$store.dispatch('auth/tryResetPassword', { form: form }).then(response => {
                    console.log(response)
                }).catch(error => {
                    console.log(error)
                })
            }
        }
    }
</script>
