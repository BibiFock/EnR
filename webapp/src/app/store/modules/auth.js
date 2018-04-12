import Vue from '../../index.js'

const state = {
    user: null
}

const getters = {
    authenticated: state => (state.user !== null),
    token: state => (state.user ? state.user.token : false)
}

const actions = {
    signIn : ({commit, state}, login) => {
        return new Promise((resolve, reject) => {
            Vue.$http.post(
                process.env.API_URL +  'auth/login',
                { id: login.id, password: login.password }
            ).then(response => {
                commit(
                    'userSigned',
                    { id: login.id, token: response.data.token }
                );
                resolve();
            }, response => {
                reject(response.data);
            })
        });
    },

    signOut : ({commit, state}) => commit('userUnsigned')
}

const mutations = {
    loadCacheInfos: (state) => {
        let jsonUser = localStorage.getItem('user');
        if (!jsonUser) {
            return false;
        }

        state.user = JSON.parse(jsonUser);
    },

    userSigned: (state, user) => {
        state.user = user;
        localStorage.setItem('user', JSON.stringify(state.user));
        // TODO send event for load map infos
    },

    userUnsigned: (state) => {
        state.user = null;
        localStorage.removeItem('user');
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
