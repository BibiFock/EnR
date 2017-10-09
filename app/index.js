require('bootstrap/dist/css/bootstrap.min.css');
require('../css/main.css');

import Vue from 'vue';
import VueResource from 'vue-resource';
import VueRouter from 'vue-router';

import App from './App';

Vue.use(VueResource);
Vue.use(VueRouter);

const routes = [
    {
        path: '/', component: App, name:'home',
        children: [{
            name: 'markers',
            path: 'markers/:markerId', component: App
        }]
    },
];

var router = new VueRouter({
    mode: 'history',
    routes: routes
});

new Vue({
    router
}).$mount('#app');

