import axios from 'axios'
import store from "../../store";
import router from "../../router";

class User {

    static isLoggedIn() {
        return new Promise((resolve, reject) => {
            store.getters['auth/isAuthenticated'] ? resolve() : reject()
        })
    }

    static getData(key) {
        return key ? store.getters['auth/getUser'][key] : store.getters['auth/getUser']
    }

    static getToken() {
        return store.getters['auth/getToken']
    }

    static login(data) {
        store.commit('auth/setAuth', data)
        store.dispatch('auth/getUser', {}).then()
    }

    static refreshSession() {
        return new Promise((resolve, reject) => {
            store.dispatch('auth/tryRefresh', {}).then(data => {
                resolve(data)
            }).catch((error) => {
                reject(error)
            })
        })
    }

    static logout(redirect = '/login') {
        return new Promise((resolve, reject) => {
            store.dispatch('auth/tryLogout').then(() => {
                router.push({path: redirect}).then()
                resolve()
            })
        })
    }

    static hasRoles(roles = []) {
        return new Promise((resolve, reject) => {
            if(_.difference(roles, store.getters['auth/getRoles']).length == 0) {
                resolve()
            } else {
                reject()
            }
        })
    }

    static isAdmin() {
        return User.isLoggedIn() && User.hasRoles(['administrator'])
    }

    static isUser() {
        return User.isLoggedIn() && User.hasRoles(['user'])
    }

    static isGuest() {
        return !User.isLoggedIn()
    }
}

export default User
