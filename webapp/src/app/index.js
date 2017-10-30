require('bootstrap/dist/css/bootstrap.min.css');
require('../style/main.scss');

import Vue from 'vue';
import VueResource from 'vue-resource';
import VueRouter from 'vue-router';

import App from './App';
import RoofResume from './roof/Resume';



Vue.use(VueResource);
Vue.use(VueRouter);

const routes = [
    { path: '/', component: App, name:'home' },
    { path: '/map', component: App, name:'home-map' },
    { name: 'roof', path: '/roofs/:roofId', component: RoofResume },
];

var router = new VueRouter({
    mode: 'history',
    routes: routes
});

new Vue({
    router
}).$mount('#app');

