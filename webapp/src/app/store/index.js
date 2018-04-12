import Vue from 'vue'
import Vuex from 'vuex'
import authStore from './modules/auth';
import roofsStore from './modules/roofs';
import mapStore from './modules/map';

Vue.use(Vuex)
Vue.config.devtools = true;
const store = new Vuex.Store({
    modules: {
        auth: authStore,
        roofs: roofsStore,
        map: mapStore
    },
    actions: {
        loadCacheInfos: ({ commit }) => {
            commit('auth/loadCacheInfos');
            setTimeout(() => commit('map/loadCacheInfos'), 10);
        }
    }
});

// if (module.hot) {
//     // accept actions and mutations as hot modules
//     module.hot.accept(['./modules/auth', './modules/roofs'], () => {
//         // require the updated modules
//         // have to add .default here due to babel 6 module output
//         const newAuth = require('./modules/auth').default;
//         const newRoofs = require('./modules/roofs').default;
//         // swap in the new actions and mutations
//         store.hotUpdate({
//             modules: {
//                 auth: newAuth,
//                 roofs: newRoofs,
//             }
//         })
//     })
// }

export default store
