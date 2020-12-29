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
    tablesWithColumnNames(state) {
      // return from each table an array with only the column names, like: { tablename : [id, firstname, lastname etc..] }
      let tables_with_column_names = {};
      Object.entries(state.tables_with_columns).forEach(function(table_entry) {
        tables_with_column_names[table_entry[0]] = table_entry[1].map(column => column.column_name)
      });
      return tables_with_column_names;
    },
    columnsOfTable: (state) => (table_name) => {
      if(state.tables_with_columns.hasOwnProperty(table_name) === false) return [];
      return state.tables_with_columns[table_name];
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
      // depending of which api call result is first in (table/list or table/listsizedata)
      // not yet set
      if(state.tables.length == 0) {
        state.tables = tables;
      }
      // already set, merge data
      else {
        state.tables = state.tables.map(function (table, index) {
          return Object.assign(table, tables[index]);
        });
      }
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
    },
    addSizeData(state, tables_size_data) {
      // depending of which api call result is first in (table/list or table/listsizedata)
      // not yet set
      if(state.tables.length == 0) {
        state.tables = tables_size_data;
      }
      // already set, merge data
      else {
        state.tables = state.tables.map(function (table, index) {
          return Object.assign(table, tables_size_data[index]);
        });
      }
    }
  },
  actions: {

    async get({commit}) {
      commit(FETCHING_TABLES);
      try {
        let response = await TablesAPI.get();
        commit(FETCHING_TABLES_SUCCESS, response.data.data);
        return response.data.data;
      } catch (error) {
        commit(FETCHING_TABLES_ERROR, error);
        return null;
      }
    },

    async getWithColumns({commit}) {
      commit(FETCHING_TABLES_WITH_COLUMNS);
      try {
        let response = await TablesAPI.getWithColumns();
        commit(FETCHING_TABLES_WITH_COLUMNS_SUCCESS, response.data.data.tables_with_columns);
        commit(FETCHING_TABLES_WITH_PRIMARY_KEYS, response.data.data.tables_with_primary_keys);
        return response.data.data;
      } catch (error) {
        commit(FETCHING_TABLES_WITH_COLUMNS_ERROR, error);
        return null;
      }
    },

  }
};
