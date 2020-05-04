const ADD_FLASH_MESSAGE = "ADD_FLASH_MESSAGE";
const ADD_FLASH_QUERY   = "ADD_FLASH_QUERY";

export default {
  namespaced: true,
  state: {
    message: null,
    query: null,
  },
  getters: {
    isset(state) {
      return state.message !== null;
    },
    message(state) {
      return state.message;
    },
    query(state) {
      return state.query;
    },
  },
  mutations: {
    [ADD_FLASH_MESSAGE](state, flash_message) {
      state.message = flash_message;
    },

    [ADD_FLASH_QUERY](state, flash_query) {
      state.query = flash_query;
    },
    empty(state) {
      state.message = null;
      state.query   = null;
    }
  },
};
