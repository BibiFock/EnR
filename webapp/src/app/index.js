import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import 'font-awesome/css/font-awesome.css';
import '../style/main.scss';

import Vue from 'vue';
import Vuex from 'vuex';
import VueResource from 'vue-resource';
import VueRouter from 'vue-router';
import Notifications from 'vue-notification';
import VueCookie from 'vue-cookie';
import BootstrapVue from 'bootstrap-vue';
import * as VueGoogleMaps from 'vue2-google-maps';

import App from './components/App';
import Map from './components/Map';
import RoofEdit from './components/roof/Edit';
import RoofEditForm from './components/roof/edit/Form';
import RoofEditHistorical from './components/roof/edit/Historical';
import Login from './components/auth/Login';
// import Auth from './service/auth.js';
import Http from './service/http.js';
import store from './store';

Vue.use(VueResource);
Vue.use(VueRouter);
Vue.use(Notifications);
Vue.use(VueCookie);
Vue.use(BootstrapVue);
Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyAtBdbbDY8RT5P5T4r2ATknGrxGUbJuIVI',
    libraries: 'places,geometry', // This is required if you use the Autocomplete plugin
    // OR: libraries: 'places,drawing'
    // OR: libraries: 'places,drawing,visualization'
    // (as you require)
  }
})

Vue.http.interceptors.push(Http.interceptor);

store.dispatch('loadCacheInfos');

if (process.env.ENV === 'dev') {
    Vue.config.devtools = true;
}

const routes = [
    { path: '', component: App,
        children: [
            { name: 'map', path: '', component: Map, meta: { needGuard: true } },
            {
                path: 'roofs/:roofId',
                component: RoofEdit, meta: { needGuard: true },
                children: [
                    { name: 'roof-edit', path: '', component: RoofEditForm },
                    { name: 'roof-historical', path: 'historical', component: RoofEditHistorical }
                ]
            },
            { name: 'login', path: 'auth/login', component: Login },
            { path: '*', redirect: 'map' }
        ]
    }
];

var router = new VueRouter({
    mode: 'history',
    routes: routes
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(m => m.meta.needGuard) && !store.getters['auth/authenticated']) {
        next('/auth/login')
    } else {
        next()
    }
})

export default new Vue({
    router,
    store
}).$mount('#app');

