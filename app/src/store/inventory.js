const ADD_ROW= "ADD_ROW";
const REMOVE_TABLE = "REMOVE_TABLE";
const REMOVE_ROW = "REMOVE_ROW";

export default {
  namespaced: true,
  state: {
    items: [],
  },
  getters: {
    hasData(state) {
      return state.items.length > 0;
    },
    items(state) {
      return state.items;
    },
  },
  mutations: {
    [ADD_ROW](state, row_data) {
      let existing_table_index = state.items.findIndex(item => item.table == row_data.table);
      if(existing_table_index >= 0) {
        row_data.rows.forEach(function (row) {
          state.items[existing_table_index].rows.push(row);
        });
      }
      else {
        state.items.push(row_data);
      }
    },

    [REMOVE_TABLE](state, table_name) {
      let existing_table_index = state.items.findIndex(item => item.table == table_name);
      if(existing_table_index < 0) return;

      state.items.splice(existing_table_index, 1);
    },
    [REMOVE_ROW](state, data) {
      state.items[data.item_index].rows.splice(data.row_index, 1);
    }
  },
};
