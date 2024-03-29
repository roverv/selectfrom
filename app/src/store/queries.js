const ADD_QUERY = "ADD_QUERY";

export default {
  namespaced: true,
  state: {
    queries: [],
  },
  getters: {
    hasQueries(state) {
      return state.queries.length > 0;
    },
    queries(state) {
      return state.queries;
    },
    queries_reversed(state) {
      return state.queries.slice().reverse();
    },
    last_key(state) {
      return state.queries.length - 1;
    }
  },
  mutations: {
    [ADD_QUERY](state, query_data) {
      state.queries.push(query_data);
      // limit to 15
      if(state.queries.length > 15) {
        state.queries.splice(0, 1);
      }
    }
  },
};
