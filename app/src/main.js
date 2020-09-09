import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'
import '@/assets/css/main.css'

// true, to sent along cookies for cross domain requests
axios.defaults.withCredentials = true;

Vue.config.productionTip = false

// use: https://keycode.info/
Vue.config.keyCodes = {
  "open-search": 70, // f
  "open-recent-tables": 69, // e
  "open-database-list": 68, // d
  "open-query-history": 72, // h
  "refresh-page": 82, // r
  "to-query": 81, // q
}
//before `new Vue`
// Vue.config.devtools = true;
// Vue.config.performance = true;
// @todo: uitzetten
new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
