<template>
    <nav class="navbar navbar-expand-lg fixed-top navbar-light bg-light shadow-sm">
        <div class="container">

            <router-link :to="{ name: 'home' }" v-slot="{ href, navigate }">
                <a class="navbar-brand" :href="href" @click="navigate">SmartSearch</a>
            </router-link>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <router-link :to="{ name: 'hello' }" v-slot="{ href, route, navigate, isActive, isExactActive }">
                        <li class="nav-item" :class="[isExactActive && 'active']">
                            <a class="nav-link" :href="href" @click="navigate">Hello World</a>
                        </li>
                    </router-link>
                    <router-link :to="{ name: 'usersIndex' }" v-slot="{ href, route, navigate, isActive, isExactActive }">
                        <li class="nav-item" :class="[isExactActive && 'active']">
                            <a class="nav-link" :href="href" @click="navigate">Users</a>
                        </li>
                    </router-link>
                    <router-link :to="{ name: 'countriesIndex' }" v-slot="{ href, route, navigate, isActive, isExactActive }">
                        <li class="nav-item" :class="[isExactActive && 'active']">
                            <a class="nav-link" :href="href" @click="navigate">Countries</a>
                        </li>
                    </router-link>
                </ul>
                <ul class="navbar-nav ml-auto" v-if="$store.getters['auth/isAuthenticated']">
                    <li class="nav-item">
                        <span class="navbar-text pr-2">{{ __('ui.auth.welcome', { name: $store.getters['auth/getUser'] ? $store.getters['auth/getUser']['name'] : __('ui.auth.guest')  })}}</span>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:;" class="nav-link" @click.prevent="logoutUser()">{{ __('ui.auth.logout') }}</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto" v-else>
                    <router-link :to="{ name: 'login' }" v-slot="{ href, route, navigate, isActive, isExactActive }">
                        <li class="nav-item" :class="[isActive && 'active']">
                            <a class="nav-link" :href="href" @click="navigate">{{ __('ui.auth.login') }}</a>
                        </li>
                    </router-link>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
    import User from "../common/auth/user";

    export default {
        methods: {
            logoutUser() {
                User.logout().then(() => {
                    this.$toast().info({
                        title: this.__('ui.auth.logout'),
                        body: this.__('auth.logout.toast.success'),
                        icon: 'fa-sign-out'
                    })
                })
            }
        }
    }
</script>
