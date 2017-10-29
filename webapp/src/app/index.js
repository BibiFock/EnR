require('bootstrap/dist/css/bootstrap.min.css');
 require('../style/main.css');

import Vue from 'vue';
import VueResource from 'vue-resource';
import VueRouter from 'vue-router';

import App from './App';
// import RoofDetail from './Roof/Detail';

Vue.use(VueResource);
Vue.use(VueRouter);

const routes = [
    {
        path: '/', component: App, name:'home',
        children: [{
            path: '/map', component: App, name:'home-map'
        // }, {
            // name: 'roof',
            // path: 'roofs/:markerId', component: RoofDetail
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

