
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

Vue.component('cohort', require('./components/Cohort.vue'));
Vue.component('module', require('./components/Module.vue'));
Vue.component('moduleform', require('./components/ModuleForm.vue'));
Vue.component('editmoduleform', require('./components/EditModuleForm.vue'));
Vue.component('modulelist', require('./components/ModuleList.vue'));
Vue.component('studentform', require('./components/StudentForm.vue'));
Vue.component('studentlist', require('./components/StudentList.vue'));
Vue.component('studentprogress', require('./components/StudentProgress.vue'));

const app = new Vue({
    el: '#app'
});
