import TablesAPI from "../api/tables";

const FETCHING_TABLES                      = "FETCHING_TABLES",
      FETCHING_TABLES_SUCCESS              = "FETCHING_TABLES_SUCCESS",
      FETCHING_TABLES_ERROR                = "FETCHING_TABLES_ERROR",
      FETCHING_TABLES_WITH_COLUMNS         = "FETCHING_TABLES_WITH_COLUMNS",
      FETCHING_TABLES_WITH_COLUMNS_SUCCESS = "FETCHING_TABLES_WITH_COLUMNS_SUCCESS",
      FETCHING_TABLES_WITH_COLUMNS_ERROR   = "FETCHING_TABLES_WITH_COLUMNS_ERROR",
      FETCHING_TABLES_WITH_PRIMARY_KEYS    = "FETCHING_TABLES_WITH_PRIMARY_KEYS";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    tables: [],
    tables_with_columns: [],
    tables_with_primary_keys: [],
  },
  getters: {
    isLoading(state) {
      return state.isLoading;
    },
    hasError(state) {
      return state.error !== null;
    },
    error(state) {
      return state.error;
    },
    hasTables(state) {
      return state.tables.length > 0;
    },
    tables(state) {
      return state.tables;
    },
    tablesWithColumns(state) {
      return state.tables_with_columns;
    },
    tablesWithPrimaryKeys(state) {
      return state.tables_with_primary_keys;
    },
    primaryKeyOfTable: (state) => (table) => {
      if(typeof(state.tables_with_primary_keys[table]) !== 'undefined') {
        return state.tables_with_primary_keys[table];
      }
      return false;
    }
  },
  mutations: {
    [FETCHING_TABLES](state) {
      state.isLoading = true;
      state.error     = null;
      state.tables    = [];
    },
    [FETCHING_TABLES_SUCCESS](state, tables) {
      state.error     = null;
      state.tables    = tables;
      // set a very small timeout, to let vuejs load the data before displaying (else this will briefly show "0 tables")
      setTimeout(function () {
        state.isLoading = false;
      }, 100);
    },
    [FETCHING_TABLES_ERROR](state, error) {
      state.error     = error;
      state.tables    = [];
      setTimeout(function () {
        state.isLoading = false;
      }, 100);
    },
    [FETCHING_TABLES_WITH_COLUMNS](state) {
      state.isLoading           = true;
      state.error               = null;
      state.tables_with_columns = [];
    },
    [FETCHING_TABLES_WITH_COLUMNS_SUCCESS](state, tables_with_columns) {
      state.error               = null;
      state.tables_with_columns = tables_with_columns;
      setTimeout(function () {
        state.isLoading = false;
      }, 100);
    },
    [FETCHING_TABLES_WITH_PRIMARY_KEYS](state, tables_with_primary_keys) {
      state.tables_with_primary_keys = tables_with_primary_keys;
    },
    [FETCHING_TABLES_WITH_COLUMNS_ERROR](state, error) {
      state.error               = error;
      state.tables_with_columns = [];
      setTimeout(function () {
        state.isLoading = false;
      }, 100);
    }
  },
  actions: {

    async get({commit}) {
      commit(FETCHING_TABLES);
      try {
        let response = await TablesAPI.get();
        commit(FETCHING_TABLES_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_TABLES_ERROR, error);
        return null;
      }
    },

    async getWithColumns({commit}) {
      commit(FETCHING_TABLES_WITH_COLUMNS);
      try {
        let response = await TablesAPI.getWithColumns();
        commit(FETCHING_TABLES_WITH_COLUMNS_SUCCESS, response.data.tables_with_columns);
        commit(FETCHING_TABLES_WITH_PRIMARY_KEYS, response.data.tables_with_primary_keys);
        return response.data;
      } catch (error) {
        commit(FETCHING_TABLES_WITH_COLUMNS_ERROR, error);
        return null;
      }
    },

  }
};
