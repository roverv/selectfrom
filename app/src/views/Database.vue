<template>
  <div class="page-content-container">
    <div>

      <spinner v-if="fetching_tables" delay="200"></spinner>

      <div v-if="!fetching_tables">

        <div class="flex justify-between items-center mb-3">
          <h2 class="text-xl">
            <span class="text-gray-500 text-lg">{{ tables.length }}</span>
            Tables
          </h2>

          <router-link :to="{ name: 'addtable', params: { database: active_database } }" class="btn">
            Create table
          </router-link>
        </div>

        <result-message :message="query_result"></result-message>

        <flash-message></flash-message>

        <table cellspacing="0" class="table-data" v-if="tables.length > 0">
          <thead>
          <tr>
            <th class="toggle-row">
              <label for="check-all-rows">
                <input type="checkbox" id='check-all-rows' class="hidden" @change="toggleAllRows($event.target.checked)" />
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 fill-current">
                  <circle cx="12" cy="12" r="10"></circle>
                  <path
                      d="M10 14.59l6.3-6.3a1 1 0 0 1 1.4 1.42l-7 7a1 1 0 0 1-1.4 0l-3-3a1 1 0 0 1 1.4-1.42l2.3 2.3z"></path>
                </svg>
              </label>
            </th>
            <th v-for="(table_list_header, index) in table_list_headers" :key="index">
              <a @click="orderByColumn(table_list_header)" class="column-order-link">
                <span>{{ table_list_header }}</span>
                <svg viewBox="0 0 24 24" class="w-5 ml-2 fill-current"
                     v-if="order_by == table_list_header && order_direction == 'asc'">
                  <path class="text-highlight-400"
                        d="M6 11V4a1 1 0 1 1 2 0v7h3a1 1 0 0 1 .7 1.7l-4 4a1 1 0 0 1-1.4 0l-4-4A1 1 0 0 1 3 11h3z"></path>
                  <path class="text-highlight-700"
                        d="M21 21H8a1 1 0 0 1 0-2h13a1 1 0 0 1 0 2zm0-4h-9a1 1 0 0 1 0-2h9a1 1 0 0 1 0 2zm0-4h-5a1 1 0 0 1 0-2h5a1 1 0 0 1 0 2z"></path>
                </svg>
                <svg viewBox="0 0 24 24" class="w-5 ml-2 fill-current"
                     v-if="order_by == table_list_header && order_direction == 'desc'">
                  <path class="text-highlight-400"
                        d="M18 13v7a1 1 0 0 1-2 0v-7h-3a1 1 0 0 1-.7-1.7l4-4a1 1 0 0 1 1.4 0l4 4A1 1 0 0 1 21 13h-3z"></path>
                  <path class="text-highlight-700"
                        d="M3 3h13a1 1 0 0 1 0 2H3a1 1 0 1 1 0-2zm0 4h9a1 1 0 0 1 0 2H3a1 1 0 1 1 0-2zm0 4h5a1 1 0 0 1 0 2H3a1 1 0 0 1 0-2z"></path>
                </svg>
              </a>
            </th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(table, table_index) in ordered_tables" :key="table_index">
            <td class="toggle-row">
              <label>
                <input type="checkbox" class="hidden" :value="table_index" v-model="selected_rows" />
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 fill-current">
                  <circle cx="12" cy="12" r="10"></circle>
                  <path
                      d="M10 14.59l6.3-6.3a1 1 0 0 1 1.4 1.42l-7 7a1 1 0 0 1-1.4 0l-3-3a1 1 0 0 1 1.4-1.42l2.3 2.3z"></path>
                </svg>
              </label>
            </td>
            <td class="table-data-row" v-for="(table_list_header, index) in table_list_headers" :key="index"
                @click="$event.target.focus()" tabindex="1"
                :class="{ ' sticky-first-row-cell' : (index == 0)}">
              <router-link v-if="table_list_header == 'Name'"
                           :to="{ name: 'table', params: { database: active_database, tableid: table[table_list_header] } }"
                           class="inline-block whitespace-normal">
                {{ table[table_list_header] }}
              </router-link>
              <span v-else-if="table_list_header == 'Size'" v-html="showTableSize(table)"></span>
              <span v-else-if="table_list_header == 'Rows' || table_list_header == 'Auto_increment'">{{
                  table[table_list_header] | formatNumber
                }}</span>
              <span v-else>{{ table[table_list_header] }}</span>
            </td>
          </tr>
          </tbody>
        </table>

        <div class="row-actions sticky bottom-0 left-0 z-30 w-full"
             v-if="tables.length > 0 && selected_rows.length > 0">

          <div class="py-3 px-3  flex items-center bg-dark-600 text-white">

            <div class="font-bold mr-6">
              {{ selected_rows.length }} tables
            </div>

            <a class="rows-action" @click="confirmTruncateTables()">
              <span>Truncate</span>
            </a>

            <a class="rows-action" @click="confirmDropTables()">
              <span>Drop</span>
            </a>

          </div>
        </div>

      </div>

      <confirm-modal v-if="confirm_modal_open" v-on:close="closeConfirmModal()"
                     v-on:confirm="confirmConfirmModal()">
        {{ confirm_modal_message }}
      </confirm-modal>

    </div>
  </div>
</template>

<script>

import {number_format} from '@/util'
import Spinner from "@/components/Spinner";
import ConfirmModal from "@/components/ConfirmModal";
import ConfirmModalMixin from "@/mixins/ConfirmModal";
import FlashMessage from "@/components/FlashMessage";
import ApiMixin from "@/mixins/Api";
import ResultMessage from "@/components/ResultMessage";

export default {
  name: 'TableList',

  data() {
    return {
      selected_rows: [],
      order_by: 'name',
      order_direction: 'asc',
      query_result: {},
      toggle_all_rows: false,
    }
  },

  created() {
    this.$emit('setcontextoptions', [
      {
        'shortkey': '1',
        'label': 'Create table',
        'action': 'createTable'
      },
      {
        'shortkey': '2',
        'label': 'Select all tables',
        'action': 'selectAllTables'
      },
      {
        'shortkey': '3',
        'label': 'Truncate selected tables',
        'action': 'confirmTruncateTables'
      },
      {
        'shortkey': '4',
        'label': 'Drop selected tables',
        'action': 'confirmDropTables'
      }
    ]);
  },

  components: {
    ResultMessage,
    Spinner,
    ConfirmModal,
    FlashMessage,
  },

  mixins: [
    ConfirmModalMixin,
    ApiMixin
  ],

  filters: {
    formatNumber(number) {
      return number_format(number, 0, ',', '.');
    }
  },

  computed: {
    table_list_headers: function () {
      return ["Name", "Engine", "Collation", "Size", "Rows", "Auto_increment"];
      // Auto_increment:1
      // Avg_row_length:0
      // Check_time:null
      // Checksum:null
      // Collation:"utf8_general_ci"
      // Comment:""
      // Create_options:""
      // Create_time:"2020-03-25 15:13:31"
      // Data_free:0
      // Data_length:16384
      // Engine:"InnoDB"
      // Index_length:32768
      // Max_data_length:0
      // Name:"auction_bids"
      // Row_format:"Dynamic"
      // Rows:0
      // Update_time:null
      // Version:10
    },

    active_database() {
      return this.$store.state.activeDatabase;
    },

    tables() {
      return this.$store.getters["tables/tables"];
    },

    fetching_tables() {
      return this.$store.getters["tables/isLoading"];
    },

    ordered_tables() {
      if (this.tables.length == 0) return [];

      // create a clone of tables, we dont want to sort the vuex state
      let ordered_tables = JSON.parse(JSON.stringify(this.tables));

      let reverse = this.order_direction == 'asc' ? 1 : -1;

      let vue_instance = this;

      // sort numeric
      if (this.order_by == 'Sort' || this.order_by == 'Auto_increment') {
        ordered_tables.sort(function (a, b) {
          return reverse * (a[vue_instance.order_by] - b[vue_instance.order_by]);
        });
      }
      // sort by function
      else if (this.order_by == 'Size') {
        ordered_tables.sort(function (a, b) {
          return reverse * (vue_instance.getSize(a) - vue_instance.getSize(b));
        });
      }
      // sort by string
      else {
        ordered_tables.sort(function (a, b) {
          if (a[vue_instance.order_by] == b[vue_instance.order_by]) return 0;
          return reverse * ((a[vue_instance.order_by] > b[vue_instance.order_by]) - (b[vue_instance.order_by] > a[vue_instance.order_by]));
        });
      }

      return ordered_tables;
    }

  },

  methods: {

    createTable() {
      this.$router.push({ name: 'addtable', params: { database: this.active_database } });
    },

    selectAllTables() {
      this.toggleAllRows(true);
    },

    getSize(table_info) {
      return parseInt(table_info.Data_length) + parseInt(table_info.Index_length);
    },

    showTableSize(table_info) {
      let size = this.getSize(table_info) / 1024;
      if (size > 1000) {
        size /= 1024;
        return number_format(size, 2, ',', '.') + ' <span class="text-gray-400">MB</span>';
      }
      return size.toFixed(0) + ' <span class="text-gray-500">KB</span>';
    },

    toggleAllRows(checked) {
      if (checked) {
        this.selected_rows = [];
        for (let row_index in Object.keys(this.tables)) {
          this.selected_rows.push(row_index);
        }
      } else {
        this.selected_rows = [];
      }
    },

    orderByColumn(column) {
      this.order_by        = column;
      this.order_direction = (this.order_direction == '' || this.order_direction == 'desc') ? 'asc' : 'desc';
      // undo selection because of indexes
      this.selected_rows = [];
    },

    confirmTruncateTables() {
      this.confirm_modal_message = 'Truncate ' + this.selected_rows.length + ' table';
      if (this.selected_rows.length > 1) {
        this.confirm_modal_message += 's';
      }
      this.confirm_modal_open   = true;
      this.confirm_modal_action = 'truncateTables';
    },

    confirmDropTables() {
      this.confirm_modal_message = 'Drop ' + this.selected_rows.length + ' table';
      if (this.selected_rows.length > 1) {
        this.confirm_modal_message += 's';
      }
      this.confirm_modal_open   = true;
      this.confirm_modal_action = 'dropTables';
    },

    truncateTables() {
      let params = new URLSearchParams();
      for (let row_index in this.selected_rows) {
        params.append('tables[]', this.ordered_tables[this.selected_rows[row_index]].Name);
      }

      let vue_instance = this;
      let api_url_params = {'db': this.active_database};
      let api_url        = this.buildApiUrl('table/truncate', api_url_params);
      this.$http.post(api_url, params).then(response => {

        if(this.validateApiResponse(response) === false) return;

        if(response.data.data.result == 'error') {
          vue_instance.query_result = {type: 'error', message: response.data.data.message};
          scroll(0,0);
          return;
        }

        let message = response.data.data.affected_tables;
        message += (response.data.data.affected_tables == 1) ? ' table truncated.' : ' tables truncated.';
        this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", {
          type: 'success',
          message: message,
          query: response.data.data.query
        });
        this.$store.dispatch('refreshTables');
        vue_instance.$router.push({name: 'database', params: {database: vue_instance.active_database}});
      }).catch(error => {
        this.handleApiError(error);
      })
    },

    dropTables() {
      let params = new URLSearchParams();
      for (let row_index in this.selected_rows) {
        params.append('tables[]', this.ordered_tables[this.selected_rows[row_index]].Name);
      }

      let vue_instance = this;
      let api_url_params = {'db': this.active_database};
      let api_url        = this.buildApiUrl('table/drop', api_url_params);
      this.$http.post(api_url, params).then(response => {

        if(this.validateApiResponse(response) === false) return;

        if(response.data.data.result == 'error') {
          vue_instance.query_result = {type: 'error', message: response.data.data.message};
          scroll(0,0);
          return;
        }

        let message = response.data.data.affected_tables;
        message += (response.data.data.affected_tables == 1) ? ' table dropped.' : ' tables dropped.';
        this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", {
          type: 'success',
          message: message,
          query: response.data.data.query
        });
        this.$store.dispatch('refreshTables');
        vue_instance.$router.push({name: 'database', params: {database: vue_instance.active_database}});
      }).catch(error => {
        this.handleApiError(error);
      })
    },

  },
}
</script>
