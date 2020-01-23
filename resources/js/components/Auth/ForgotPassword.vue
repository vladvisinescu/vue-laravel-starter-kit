<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <div class="card">
                    <h4 class="card-header">
                        {{ __('ui.auth.forgot_password') }}
                    </h4>
                    <div class="card-body">
                        <div class="app-login-form">
                            <form action="" @submit.prevent="tryLogin">
                                <div class="form-group">
                                    <label for="email">{{ __('ui.auth.form.username') }}</label>
                                    <input type="text" class="form-control" id="email" v-bind:class="{ 'is-invalid': errors.email }" v-model="form.email">
                                    <span class="help-block" v-if="errors.email">{{ errors.email[0] }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="d-flex justify-content-between"><span>{{ __('ui.auth.form.password') }}</span> <strong><a href="javascript:;">{{ __('ui.auth.forgot_question') }}</a></strong></label>
                                    <input type="password" class="form-control" id="password" v-bind:class="{ 'is-invalid': errors.password }" v-model="form.password">
                                    <span class="help-block" v-if="errors.password">{{ errors.password[0] }}</span>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-info btn-block">{{ __('ui.auth.login') }}</button>
                                </div>
                                <div class="form-group">
                                    <router-link class="btn btn-outline-secondary" to="/register" style="white-space: nowrap; white-space: pre">{{ __('ui.auth.register_question') }}</router-link>
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
    export default {

        data() {
            return {
                //
            }
        }
    }
</script>
