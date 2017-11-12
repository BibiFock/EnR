import Vue from '../index.js';
import { router } from '../index.js';

export default {
    user: {
        authenticated: false,
        profile: null
    },
    // check() {
        // let token = localStorage.getItem('id_token')
        // if (token !== null) {
            // Vue.http.get(
                // 'api/user?token=' + token,
            // ).then(response => {
                // this.user.authenticated = true
                // this.user.profile = response.data.data
            // })
        // }
    // },
    // register(context, name, email, password) {
        // Vue.http.post(
            // 'api/register',
            // {
                // name: name,
                // email: email,
                // password: password
            // }
        // ).then(response => {
            // context.success = true
        // }, response => {
            // context.response = response.data
            // context.error = true
        // })
    // },
    signin(context, name, password) {
        Vue.http.post(
            process.env.API_URL +  'auth/login',
            { name, password }
        ).then(response => {
            context.error = false
            localStorage.setItem('id_token', response.data.token)
            Vue.http.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('id_token')

            this.user.authenticated = true
            this.user.profile = response.data.data

            router.push({
                name: 'map'
            })
        }, response => {
            context.error = true
        })
    },
    signout() {
        localStorage.removeItem('id_token')
        this.user.authenticated = false
        this.user.profile = null

        router.push({
            name: 'login'
        })
    }
}
