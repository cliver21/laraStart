/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
//import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)
//moment
import moment from 'moment';
//progressbar
import VueProgressBar from 'vue-progressbar'
//sweetalert
import Swal from 'sweetalert2'
window.swal=Swal;

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});
//on definir le toast goblalement
window.Toast=Toast;

window.Fire= new Vue();

Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: 'red',
  speed: 0.7,
  height: '3px'
})

//vform import
import {
    Form,
    HasError,
    AlertError
} from 'vform';
//initialisation de vform en global

window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

// tableau des routes
import dashboard from './components/Dashboard.vue'

let routes = [{
        path: '/dashboard',
        component: dashboard
    },
    {
        path: '/profile',
        component: require('./components/Profile.vue').default
    },
    {
        path: '/users',
        component: require('./components/Users.vue').default
    }
]

//creation de l'intance de route
const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
})

//filltre de l'application
Vue.filter('upText', function (text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
});
Vue.filter('myDate', function (created) {
    return moment(created).format('MMMM Do YYYY');
});
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});
