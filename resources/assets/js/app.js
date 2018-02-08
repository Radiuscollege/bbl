
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
Vue.component('cohortchart', require('./components/CohortChart.vue'));
Vue.component('markmodal', require('./components/MarkModal.vue'));
Vue.component('moduleform', require('./components/ModuleForm.vue'));
Vue.component('modulelist', require('./components/ModuleList.vue'));
Vue.component('fileupload', require('./components/FileUpload.vue'));
Vue.component('statistics', require('./components/Statistics.vue'));
Vue.component('student', require('./components/Student.vue'));
Vue.component('studentamountchart', require('./components/StudentAmountChart.vue'));
Vue.component('studentprogresschart', require('./components/StudentProgressChart.vue'));
Vue.component('studentchart', require('./components/StudentChart.vue'));
Vue.component('studentform', require('./components/StudentForm.vue'));
Vue.component('studentlist', require('./components/StudentList.vue'));
Vue.component('studentprogress', require('./components/StudentProgress.vue'));
Vue.component('studentsearch', require('./components/StudentSearch.vue'));
Vue.component('studentsearchresult', require('./components/StudentSearchResult.vue'));

const app = new Vue({
    el: '#app'
});
