import store from "../store";

export default {
  get() {
    return store.$axios.get('database/list');
  }
};
