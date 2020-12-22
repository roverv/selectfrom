import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from "vuex-persistedstate";

import DatabasesStore from '@/store/databases';
import TablesStore from '@/store/tables';
import QueryHistory from '@/store/queries';
import RecentTables from '@/store/recenttables';
import Searches from '@/store/searches';
import FlashMessage from '@/store/flashmessage';
import ApiError from '@/store/apierror';
import QueryEdit from '@/store/queryedit';
import Settings from "@/store/settings";

Vue.use(Vuex)

export default new Vuex.Store({

  plugins: [
    // persist these modules in session storage, meaning it will only persist for the open tab and remove when the tab closes
    // a different tab will be store separately
    createPersistedState({
      paths: ['activeDatabase', 'reloadMainComponentKey', 'databases', 'nodes_skip_on_key', 'tables', 'queryhistory', 'recenttables', 'searches'],
      storage: window.sessionStorage
    }),
    // save in local storage, for longer storage across multiple tabs
    createPersistedState({
      paths: ['settings', 'authenticated', 'csrf_token'],
      storage: window.localStorage
    }),
  ],

  modules: {
    databases: DatabasesStore,
    tables: TablesStore,
    queryhistory: QueryHistory,
    recenttables: RecentTables,
    searches: Searches,
    flashmessage: FlashMessage,
    apierror: ApiError,
    queryedit: QueryEdit,
    settings: Settings,
  },

  state: {
    activeDatabase: '',
    reloadMainComponentKey: 0,
    authenticated: false,
    csrf_token : {},
    nodes_skip_on_key: ['INPUT', 'TEXTAREA', 'SELECT'],
  },

  mutations: {
    setActiveDatabase(state, database) {
      state.activeDatabase = database;
      if(database != '') {
        this.dispatch("tables/get");
        this.dispatch("tables/getWithColumns");
      }
    },
    setAuthenticated(state, authenticated) {
      state.authenticated = authenticated;
    },
    setCsrfToken(state, csrf_token) {
      state.csrf_token = csrf_token;
    }
  },

  actions: {
    refreshTables() {
      this.dispatch("tables/get");
      this.dispatch("tables/getWithColumns");
    },
    refreshDatabases() {
      this.dispatch("databases/get");
    },
    logout() {
      this.commit("setAuthenticated", false);
      this.commit("setCsrfToken", {});
      this.commit("setActiveDatabase", '');
    },
  }
})
