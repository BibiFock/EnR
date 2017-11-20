import Vue from '../index.js';

var Auth = {
    user: {
        authenticated: false,
        profile: null
    },
    signin(context, name, password) {
        Vue.$http.post(
            process.env.API_URL +  'auth/login',
            { name, password }
        ).then(response => {
            context.error = false
            localStorage.setItem('id_token', response.data.token)
            // Vue.http.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('id_token')

            this.user.authenticated = true
            this.user.profile = response.data.data

            Vue.$router.push({
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

        Vue.$router.push({
            name: 'login'
        })
    }
};

export default Auth;
