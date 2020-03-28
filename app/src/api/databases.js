import axios from "axios";
import store from "../store";

export default {
  findAll() {
    return axios.get(store.state.apiEndPoint + 'databases.php');
  }
};
