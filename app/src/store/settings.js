const SET_SETTINGS = "SET_SETTINGS";

export default {
  namespaced: true,
  state: {
    // defaults
    settings: {
      'default_table_column_order': 'primary_desc',
      'default_rows_per_page': 30,
      'cell_text_display_limit': 30,
    },
  },
  getters: {
    getSetting: (state) => (setting_name) => {
      return state.settings[setting_name];
    },
    getAll(state) {
      return state.settings;
    },
  },
  mutations: {
    [SET_SETTINGS](state, settings) {
      state.settings = settings;
    }
  },
};
