import store from "../store";
import {urlencode} from '../util';

export default {
  get() {
    return store.$axios.get('table/list?db=' + urlencode(store.state.activeDatabase));
  },
  getWithColumns() {
    return store.$axios.get('table/listwithcolumns?db=' + urlencode(store.state.activeDatabase));
  }

};
