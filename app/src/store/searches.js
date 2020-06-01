const ADD_SEARCH= "ADD_SEARCH";

export default {
  namespaced: true,
  state: {
    searches: [],
  },
  getters: {
    hasSearches(state) {
      return state.searches.length > 0;
    },
    searches(state) {
      return state.searches;
    },
  },
  mutations: {
    [ADD_SEARCH](state, search) {
        // remove duplicates
        if (state.searches.indexOf(search) >= 0) {
          state.searches.splice(state.searches.indexOf(search), 1);
        }
        // limit to 15
        while (state.searches.length > 14) {
          state.searches.splice(14, 1);
        }
        // add new search to the beginning of the array
        state.searches.unshift(search);
    }
  },
};
