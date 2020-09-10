import store from "../store";

export default {
  get() {
    return store.$axios.get(process.env.VUE_APP_API_ENDPOINT + 'database/list');
  }
};
