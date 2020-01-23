<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <div class="card">
                    <h4 class="card-header">
                        {{ __('ui.auth.register') }}
                    </h4>
                    <div class="card-body">
                        <div class="app-login-form">
                            <form action="" @submit.prevent="tryRegister" @keyup.prevent="formValidation">
                                <div class="form-group">
                                    <label for="name">{{ __('ui.auth.form.name') }}</label>
                                    <input type="text" class="form-control" id="name" v-bind:class="{ 'is-invalid': errors.name }" v-model="form.name">
                                    <span class="help-block invalid-feedback" v-if="errors.name">{{ errors.name[0] }}</span>
                                </div>
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
                                        <button type="submit" class="btn btn-info btn-block">{{ __('ui.auth.register') }}</button>
                                        <router-link class="btn btn-secondary" to="/login">{{ __('ui.auth.login') }}</router-link>
                                    </div>
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

    import { mapGetters } from 'vuex'

    export default {

        computed: {
            ...mapGetters({
                errors: 'auth/getRegisterErrors'
            })
        },

        data() {
            return {
                form: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                },

                name_check: null,
                password_check: null,
                email_check: null,
            }
        },

        beforeRouteLeave (to, from, next) {
            this.$store.commit('auth/clearRegisterErrors')
            next()
        },

        methods: {
            tryRegister() {

                if (!_.isEmpty(this.form.password) && (this.password_check.score < 3)) {
                    this.$toast().warning({
                        title: 'Error',
                        body: 'Please provide a stronger password,',
                        icon: 'fa-lock'
                    })

                    return
                }

                const form = this.form
                const router = this.$router
                this.$store.dispatch('auth/tryRegister', {form: form}).then(() => {
                    router.push({ name: 'home' })
                }).catch(error => {
                    this.$console('Vue:Register:tryRegister', error)
                })
            },

            testPassword() {
                this.password_check = zxcvbn(this.form.password)
            },

            formValidation() {
                // console.log(this.form)
            }
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

