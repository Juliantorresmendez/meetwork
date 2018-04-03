import loadash from 'lodash';
window._ = loadash;

import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

import 'bootstrap-sass';

/**
 * Vue is a modern JavaScript library for building interactive web interfaces
 * using reactive data binding and reusable components. Vue's API is clean
 * and simple, leaving you to focus on building your next great project.
 */

import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import SweetAlert from 'sweetalert';




window.Vue = Vue;
Vue.use(VueRouter);
window.axios = axios;
window.swal = SweetAlert;

import Vuex from 'vuex'

import Vueditor from 'vueditor'

import 'vueditor/dist/style/vueditor.min.css'

// your config here
let config = {
  toolbar: [
    'undo'
  ],
}; 

Vue.use(Vuex);
Vue.use(Vueditor, config);


window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest'
};


//axios.defaults.user=JSON.parse(login);
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');