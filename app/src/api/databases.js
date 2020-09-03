import axios from "axios";
import store from "../store";

export default {
  get() {
    return axios.get(process.env.VUE_APP_API_ENDPOINT + 'databases.php');
  }
};
