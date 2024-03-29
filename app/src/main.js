import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'
import '@/assets/css/main.css'
import Vuex from "vuex";
import '@/mixins/global';

const axios_instance =  axios.create({
  baseURL: process.env.VUE_APP_API_ENDPOINT,
  // true, to sent along cookies for cross domain requests
  withCredentials: true,
 })

// make axios available on VUE object
Vue.prototype.$http = axios_instance;
// make axios available on VUEX STORE object
Vuex.Store.prototype.$axios = axios_instance;

Vue.config.productionTip = false

// use: https://keycode.info/
Vue.config.keyCodes = {
  "open-search": 70, // f
  "open-recent-tables": 69, // e
  "open-database-list": 68, // d
  "open-query-history": 72, // h
  "refresh-page": 82, // r
  "to-query": 81, // q
  "level-up": 85, // u
  "open-context-menu": 67, // c
  "open-inventory": 73, // i
}

// Register a global custom directive called `v-focus`
Vue.directive('focus', {
  // When the bound element is inserted into the DOM...
  inserted: function (el) {
    // Focus the element
    el.focus()
  }
});

//before `new Vue`
// Vue.config.devtools = true;
// Vue.config.performance = true;
new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
