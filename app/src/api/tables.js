import axios from "axios";
import store from "../store";

export default {
  get() {
    return axios.get(store.state.apiEndPoint + 'tables.php?db=' + store.state.activeDatabase);
  }
};
