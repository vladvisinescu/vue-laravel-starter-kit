import Vue from 'vue'
import VueRouter from 'vue-router'
import User from '../common/auth/user';

Vue.use(VueRouter)

// Vue Views
import Home from '../views/Home'
import Hello from '../views/Hello'
import Countries from '../views/Countries';
import UsersIndex from '../views/UsersIndex.vue'

import Login from '../components/Auth/Login'
import Register from '../components/Auth/Register';
import ForgotPassword from "../components/Auth/ForgotPassword";

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home
    },
    {
        path: '/hello',
        name: 'hello',
        component: Hello
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
    },
    {
        path: '/register',
        name: 'register',
        component: Register
    },
    {
        path: '/forgot_password',
        name: 'forgot_password',
        component: ForgotPassword
    },
    {
        path: '/users',
        name: 'usersIndex',
        component: UsersIndex,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: '/countries',
        name: 'countriesIndex',
        component: Countries,
        meta: {
            requiresAuth: true,
            requiresRoles: ['administrator']
        }
    },
];

const router = new VueRouter({
    mode: 'history',
    routes: routes,
});

router.beforeEach((to, from, next) => {
    // Route needs auth, bro?
    if (to.matched.some(record => record.meta.requiresAuth)) {
        return User.isLoggedIn().then(() => {
            if (to.meta.requiresRoles) {
                User.hasRoles(to.meta.requiresRoles)
                    .then(() => next())
                    .catch(() => {
                        // Apparently you get access to the Vue instance through `router.app`. The more you know.
                        router.app.$toast().warning({
                            title: router.app.__('ui.auth.unauthorized'),
                            body: router.app.__('auth.unauthorized')
                        })
                        next('/')
                    })
            }
            next()
        }).catch(() => {
            User.refreshSession()
                .then(() => next())
                .catch(error => {
                    router.app.$console(error)
                    next({name: 'login', query: {redirect: to.path}})
                })
        })
    } else {
        next()
    }
})

// router.onError()

export default router
