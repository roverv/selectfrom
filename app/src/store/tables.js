import TablesAPI from "../api/tables";

const FETCHING_TABLES         = "FETCHING_TABLES",
      FETCHING_TABLES_SUCCESS = "FETCHING_TABLES_SUCCESS",
      FETCHING_TABLES_ERROR   = "FETCHING_TABLES_ERROR";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    tables: []
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
    }
  },
  mutations: {
    [FETCHING_TABLES](state) {
      state.isLoading = true;
      state.error     = null;
      state.tables = [];
    },
    [FETCHING_TABLES_SUCCESS](state, tables) {
      state.isLoading = false;
      state.error     = null;
      state.tables = tables;
    },
    [FETCHING_TABLES_ERROR](state, error) {
      state.isLoading = false;
      state.error     = error;
      state.tables = [];
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
    }
  }
};
