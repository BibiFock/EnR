import Vue from '../index.js';

var Auth = {
    user: {
        authenticated: true,
    },
    signin(id, password) {
        return Vue.$http.post(
            process.env.API_URL +  'auth/login',
            { id, password }
        ).then(response => {
            localStorage.setItem('id_token', response.data.token)

            this.user.authenticated = true

            Vue.$router.push({
                name: 'map'
            })
        }, response => {
            Vue.$notify({
                type: 'error',
                title: 'login failed',
                text: response.data,
            });
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
