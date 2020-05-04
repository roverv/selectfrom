export default {
  namespaced: true,
  state: {
    error_message: null,
  },
  getters: {
    isset(state) {
      return state.error_message !== null;
    },
    error_message(state) {
      return state.error_message;
    },
  },
  mutations: {
    add_error_message(state, error_message) {
      state.error_message = error_message;
    },
    empty(state) {
      state.error_message = null;
    }
  },
};
