export default {
  namespaced: true,
  state: {
    // defaults

    // table
    'default_table_column_order': 'primary_desc',
    'default_rows_per_page': 30,
    'cell_text_display_limit': 30,

    // table_list
    'only_show_tables_with_rows': false,
    'tables_list_is_open': true,

    // help
    'do_not_show_welcome_message': false,
    'do_not_show_table_data_help_message': false,
  },
  getters: {
    getSetting: (state) => (setting_name) => {
      return state[setting_name];
    },
  },
  mutations: {
    set_setting(state, {setting_name, setting_value}) {
      state[setting_name] = setting_value;
    },
    set_settings(state, settings) {
      for(const setting_name in settings) {
        state[setting_name] = settings[setting_name];
      }
    }
  },
};
