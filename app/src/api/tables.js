import axios from "axios";
import store from "../store";
import {urlencode} from '../util';

export default {
  get() {
    return axios.get(store.state.apiEndPoint + 'tables.php?db=' + urlencode(store.state.activeDatabase));
  },
  getWithColumns() {
    return axios.get(store.state.apiEndPoint + 'tables_with_columns.php?db=' + urlencode(store.state.activeDatabase));
  }

};
