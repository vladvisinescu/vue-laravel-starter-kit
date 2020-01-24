<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <div class="card">
                    <h4 class="card-header">
                        {{ __('ui.auth.forgot_title') }}
                    </h4>
                    <div class="card-body" v-if="!$route.params.token">
                        <p>{{ __('auth.forgot_password_text') }}</p>
                        <div class="app-login-form">
                            <form action="" @submit.prevent="tryPasswordReset">
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
                    <div class="card-body" v-else-if="$route.params.token">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A eius molestias nemo neque quaerat quia quidem soluta. Deserunt, impedit, laudantium.</p>
                        <form action="" @submit.prevent="sendNewPassword">
                            <div class="form-group">
                                <label for="email">{{ __('ui.auth.form.username') }}</label>
                                <input type="text" class="form-control" id="email" v-bind:class="{ 'is-invalid': errors.email }" v-model="form.email">
                                <span class="help-block invalid-feedback" v-if="errors.email">{{ errors.email[0] }}</span>
                            </div>
                            <div class="form-group">
                                <label for="password">{{ __('ui.auth.form.password') }}</label>
                                <input type="password" class="form-control" id="password" @input="testPassword" v-bind:class="{ 'is-invalid': errors.password || (password_check ? (password_check.score < 3) : false), 'is-valid': password_check ? (password_check.score > 2) : false }" v-model="form.password">
                                <span class="help-block invalid-feedback" v-if="errors.password">{{ errors.password[0] }}</span>
                                <span class="help-block invalid-feedback" v-if="(password_check ? (password_check.score < 3) : false)">{{ __('auth.password_complexity.weak') }}</span>
                                <div class="help-block valid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">{{ __('ui.auth.form.password_again') }}</label>
                                <input type="password" class="form-control" id="password_confirmation" v-model="form.password_confirmation">
                            </div>
                            <div class="form-group">
                                <div class="btn-group d-flex justify-content-between">
                                    <input type="hidden" name="reset_token" v-model="form.token">
                                    <button type="submit" class="btn btn-info btn-block">Update Password</button>
                                </div>
                            </div>
                        </form>
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
                    email: '',
                    password: '',
                    password_confirmation: '',
                    token: this.$route.params.token
                },

                password_check: null,
            }
        },

        beforeRouteLeave (to, from, next) {
            this.$store.commit('auth/clearForgotPasswordErrors')
            next()
        },

        methods: {
            tryPasswordReset() {
                const form = this.form
                this.$store.dispatch('auth/tryResetPassword', { form: form }).then(response => {
                    this.$router.push({ name: 'home' }).then(() => {
                        this.$toast().info({
                            title: this.__('ui.auth.success'),
                            body: this.__('auth.forgot_password_email'),
                            duration: 10000
                        })
                    })
                }).catch(error => {
                    console.log(error)
                })
            },

            sendNewPassword() {
                const form = this.form
                this.$store.dispatch('auth/trySendNewPassword', { form: form }).then(response => {
                    this.$router.push({ name: 'login' }).then(() => {
                        this.$toast().success({
                            title: this.__('ui.auth.success'),
                            body: this.__('auth.forgot_password_login'),
                        })
                    })
                }).catch(error => {
                    console.log(error)
                })
            },

            testPassword() {
                this.password_check = zxcvbn(this.form.password)
            },
        },

        mounted() {
            let recaptchaScript = document.createElement('script')
            recaptchaScript.setAttribute('src', 'https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js')
            recaptchaScript.setAttribute('type', 'text/javascript')
            recaptchaScript.setAttribute('id', 'password-meter')
            // document.head.appendChild(recaptchaScript)

            if (!document.head.querySelectorAll('#password-meter').length) {
                document.head.appendChild(recaptchaScript)
            }
        }
    }
</script>
