import DatabaseAPI from "../api/databases";

const FETCHING_DATABASES         = "FETCHING_DATABASES",
      FETCHING_DATABASES_SUCCESS = "FETCHING_DATABASES_SUCCESS",
      FETCHING_DATABASES_ERROR   = "FETCHING_DATABASES_ERROR";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    databases: []
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
    hasPosts(state) {
      return state.databases.length > 0;
    },
    databases(state) {
      return state.databases;
    }
  },
  mutations: {
    [FETCHING_DATABASES](state) {
      state.isLoading = true;
      state.error     = null;
      state.databases = [];
    },
    [FETCHING_DATABASES_SUCCESS](state, databases) {
      state.isLoading = false;
      state.error     = null;
      state.databases = databases;
    },
    [FETCHING_DATABASES_ERROR](state, error) {
      state.isLoading = false;
      state.error     = error;
      state.databases = [];
    }
  },
  actions: {
    async findAll({commit}) {
      commit(FETCHING_DATABASES);
      try {
        let response = await DatabaseAPI.findAll();
        commit(FETCHING_DATABASES_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_DATABASES_ERROR, error);
        return null;
      }
    }
  }
};
