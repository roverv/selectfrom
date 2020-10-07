const ADD_FLASH_MESSAGE = "ADD_FLASH_MESSAGE";

export default {
  namespaced: true,
  state: {
    messages: [],
    // query: null,
  },
  getters: {
    isset(state) {
      return state.messages !== null && state.messages.length > 0;
    },
    messages(state) {
      return state.messages;
    },
  },
  mutations: {
    [ADD_FLASH_MESSAGE](state, flash_message) {
      state.messages.push(flash_message);
    },
    empty(state) {
      state.messages = [];
    }
  },
};
