import axios from "axios";
import store from "../store";
import {urlencode} from '../util';

export default {
  get() {
    return axios.get(process.env.VUE_APP_API_ENDPOINT + 'tables.php?db=' + urlencode(store.state.activeDatabase));
  },
  getWithColumns() {
    return axios.get(process.env.VUE_APP_API_ENDPOINT + 'tables_with_columns.php?db=' + urlencode(store.state.activeDatabase));
  }

};
