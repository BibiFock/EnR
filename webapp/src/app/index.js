require('bootstrap/dist/css/bootstrap.min.css');
require('../style/main.scss');

import Vue from 'vue';
import VueResource from 'vue-resource';
import VueRouter from 'vue-router';

import App from './components/App';
import Map from './components/Map';
import RoofResume from './components/roof/Resume';
import Login from './components/auth/Login';
import Auth from './service/auth.js';

Vue.use(VueResource);
Vue.use(VueRouter);

export default Vue;
// Vue.http.headers.common['X-CSRF-TOKEN'] = document.getElementsByName('csrf-token')[0].getAttribute('content');
Vue.http.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('id_token');
// Vue.http.options.root = 'http://laravel.dev:8080';

const routes = [
    { path: '', component: App,
        children: [
            // { name: 'map', path: 'map', component: Map },
            { name: 'map', path: '', component: Map, meta: { needGuard: true } },
            { name: 'roof', path: 'roofs/:roofId', component: RoofResume, meta: { needGuard: true }},
            { name: 'login', path: 'auth/login', component: Login },
            { path: '*', redirect: 'map' }
        ]
    }
];

export var router = new VueRouter({
    mode: 'history',
    routes: routes
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(m => m.meta.needGuard) && Auth.user.authenticated) {
        next('/auth/login')
    } else {
        next()
    }
})

new Vue({
    router,
}).$mount('#app');

