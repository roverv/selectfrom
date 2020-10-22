const ADD_QUERY_EDIT            = "ADD_QUERY_EDIT";
const ACTIVATE_DIRECT_EXECUTION = "ACTIVATE_DIRECT_EXECUTION";

export default {
  namespaced: true,
  state: {
    query: null,
    direct_execution: false
  },
  getters: {
    isset(state) {
      return state.query !== null;
    },
    execute(state) {
      return state.direct_execution;
    },
    query(state) {
      return state.query;
    },
  },
  mutations: {
    [ADD_QUERY_EDIT](state, query) {
      state.query            = query;
    },

    [ACTIVATE_DIRECT_EXECUTION](state) {
      state.direct_execution = true;
    },

    empty(state) {
      state.query            = null;
      state.direct_execution = false;
    }
  },
};
