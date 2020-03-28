import Vue from 'vue'
import Vuex from 'vuex'
import DatabasesStore from './store/databases';

Vue.use(Vuex)

export default new Vuex.Store({

  modules: {
    databases: DatabasesStore
  },


  state: {
    apiEndPoint: 'http://localhost/rove/api/',
    activeDatabase: '',
    reloadMainComponentKey: 0
  },

  mutations: {
      setActiveDatabase(state, database) {
        state.activeDatabase = database;
      },
  },
  actions: {

  }
})
