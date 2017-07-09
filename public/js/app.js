
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));

const app = new Vue({
    el: '#app'
});


function notificarError(message){
    new Noty({
        timeout: 5000,
        type: 'error',
        layout: 'topCenter',
        text: message,
    }).show();
}
function notificarSuccess(message){
    new Noty({
        timeout: 5000,
        type: 'success',
        layout: 'topCenter',
        text: message,
    }).show();
}
function notificarWarning(message){
    new Noty({
        timeout: 10000,
        type: 'info',
        layout: 'topCenter',
        text: message,
    }).show()
}