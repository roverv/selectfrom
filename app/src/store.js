import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from "vuex-persistedstate";

import DatabasesStore from './store/databases';
import TablesStore from './store/tables';

Vue.use(Vuex)

export default new Vuex.Store({

  plugins: [createPersistedState({ storage: window.sessionStorage })],

  modules: {
    databases: DatabasesStore,
    tables: TablesStore,
  },


  state: {
    apiEndPoint: 'http://localhost/rove/api/',
    activeDatabase: '',
    reloadMainComponentKey: 0
  },

  mutations: {
      setActiveDatabase(state, database) {
        state.activeDatabase = database;
        this.dispatch("tables/get");
        this.dispatch("tables/getWithColumns");
      },
  },
  actions: {

  }
})
