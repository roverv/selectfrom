const ADD_RECENT_TABLE = "ADD_RECENT_TABLE";

export default {
  namespaced: true,
  state: {
    tables: [],
  },
  getters: {
    hasTables(state) {
      return state.tables.length > 0;
    },
    tables(state) {
      return state.tables;
    },
  },
  mutations: {
    [ADD_RECENT_TABLE](state, table) {
        // remove duplicates
        if (state.tables.indexOf(table) >= 0) {
          state.tables.splice(state.tables.indexOf(table), 1);
        }
        // limit to 10
        while (state.tables.length > 9) {
          state.tables.splice(9, 1);
        }
        // add new table to the beginning of the array
        state.tables.unshift(table);
    }
  },
};
