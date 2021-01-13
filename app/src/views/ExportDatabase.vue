<template>
  <div class="page-content-container">
    <div class="export-database-container">

      <h1 class="text-xl mb-4">Export tables</h1>

      <result-message :message="dump_result"></result-message>

      <h3 class="text-lg mb-4 border-b border-light-200 flex justify-between">
        <span>Options</span>
      </h3>

      <div class="vertical-form mb-2">

        <div class="input-row">
          <div class="label-box">
            <div>Filetype</div>
          </div>
          <select class="default-select w-64" disabled>
            <option value="both">For now only SQL is supported</option>
          </select>
        </div>

        <div class="input-row">
          <div class="label-box">
            <div>Structure and/or data</div>
          </div>
          <select class="default-select w-64" v-model="structure_data" v-on:keyup.esc="focusToApp">
            <option value="both">Structure and data</option>
            <option value="data">Data</option>
            <option value="structure">Structure</option>
          </select>
        </div>

        <div v-show="advanced_settings_open">

          <div class="input-row">
            <div class="label-box">
              <div>Default character set</div>
            </div>
            <select class="default-select w-64" v-model="default_character_set" v-on:keyup.esc="focusToApp">
              <option value="utf8">utf8</option>
              <option value="utf8mb4">utf8mb4</option>
            </select>
          </div>

          <div class="input-row">
            <div class="label-box">
              <div>Compress as gzip</div>
            </div>
            <label class="custom-checkbox no-label">
              <input type="checkbox" autocomplete="off" v-model="compress_gzip">
              <span class="input-box"></span>
            </label>
          </div>

          <div class="input-row">
            <div class="label-box with-description-text">
              <div>
                CREATE TABLE IF NOT EXISTS
                <br>
                <span class="description-text"></span>
              </div>
            </div>
            <label class="custom-checkbox no-label">
              <input type="checkbox" autocomplete="off" v-model="if_not_exists">
              <span class="input-box"></span>
            </label>
          </div>

          <div class="input-row">
            <div class="label-box with-description-text">
              <div>
                Reset auto_increment
                <br>
                <span class="description-text">Removes the AUTO_INCREMENT option from the database definition</span>
              </div>
            </div>
            <label class="custom-checkbox no-label">
              <input type="checkbox" autocomplete="off" v-model="reset_auto_increment">
              <span class="input-box"></span>
            </label>
          </div>

          <div class="input-row">
            <div class="label-box with-description-text">
              <div>
                Add drop table
                <br>
                <span class="description-text">Write a DROP TABLE statement before each CREATE TABLE statement</span>
              </div>
            </div>
            <label class="custom-checkbox no-label">
              <input type="checkbox" autocomplete="off" v-model="add_drop_table">
              <span class="input-box"></span>
            </label>
          </div>

          <div class="input-row">
            <div class="label-box with-description-text">
              <div>
                Add locks
                <br>
                <span class="description-text">Surround each table dump with LOCK TABLES and UNLOCK TABLES statements. This results in faster inserts when the dump file is reloaded</span>
              </div>
            </div>
            <label class="custom-checkbox no-label">
              <input type="checkbox" autocomplete="off" v-model="add_locks">
              <span class="input-box"></span>
            </label>
          </div>

          <div class="input-row">
            <div class="label-box with-description-text">
              <div>
                Lock tables
                <br>
                <span class="description-text">Lock all tables (MyISAM) before dumping them</span>
              </div>
            </div>
            <label class="custom-checkbox no-label">
              <input type="checkbox" autocomplete="off" v-model="lock_tables">
              <span class="input-box"></span>
            </label>
          </div>
        </div>

        <a class="text-gray-400" @click="advanced_settings_open = true" v-show="!advanced_settings_open">+ Show advanced
          options</a>
        <a class="text-gray-400" @click="advanced_settings_open = false" v-show="advanced_settings_open">- Hide advanced
          options</a>

      </div>

      <spinner v-if="fetching_tables" delay="200"></spinner>

      <div v-if="!fetching_tables">

        <result-message :message="query_result"></result-message>

        <flash-message></flash-message>

        <div class="flex justify-between mb-3 mt-5">
          <input type="text" class="default-text-input w-48 py-0 px-1 mt-1" placeholder="Filter tables"
                 v-model="filter_tables_text">
          <a @click="exportTables()" class="btn">
            <div v-if="is_fetching_export_data" class="flex">
              <spinner class="w-5 mr-2"></spinner>
              Generating dump...
            </div>
            <span v-else>Export</span>
          </a>
        </div>

        <table cellspacing="0" class="table-data" v-if="tables.length > 0">
          <thead>
          <tr>
            <th class="toggle-row">

              <div class="flex flex-col items-center">
                <label for="check-all-rows">
                  <input type="checkbox" id='check-all-rows' class="hidden" v-model="toggle_all_rows" />
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 fill-current">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path
                        d="M10 14.59l6.3-6.3a1 1 0 0 1 1.4 1.42l-7 7a1 1 0 0 1-1.4 0l-3-3a1 1 0 0 1 1.4-1.42l2.3 2.3z"></path>
                  </svg>
                </label>
              </div>

            </th>
            <th>Name</th>
            <th>Engine</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(table, table_index) in tables_filtered" :key="table.name">
            <td class="toggle-row">
              <div class="flex flex-col items-center">
                <label>
                  <input type="checkbox" class="hidden" :value="table.name" v-model="selected_tables" />
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 fill-current">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path
                        d="M10 14.59l6.3-6.3a1 1 0 0 1 1.4 1.42l-7 7a1 1 0 0 1-1.4 0l-3-3a1 1 0 0 1 1.4-1.42l2.3 2.3z"></path>
                  </svg>
                </label>
              </div>
            </td>
            <td class="table-data-row" @click="$event.target.focus()">
              <router-link :to="{ name: 'table', params: { database: active_database, tableid: table.name } }"
                           class="inline-block whitespace-normal">
                {{ table.name }}
              </router-link>
            </td>
            <td>
              <span v-if="table.type == 'VIEW'">View</span>
              <span v-else>{{ table.engine }}</span>
            </td>
          </tr>
          </tbody>
        </table>

        <div class="row-actions sticky bottom-0 left-0 z-30 w-full"
             v-if="tables.length > 0 && selected_tables.length > 0">
          <div class="py-3 px-3  flex items-center bg-dark-600 text-white">
            <div class="font-bold mr-6">
              {{ selected_tables.length }} tables
            </div>
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

import {has_deep_property, number_format} from '@/util'
import Spinner from "@/components/Spinner";
import ConfirmModal from "@/components/ConfirmModal";
import ConfirmModalMixin from "@/mixins/ConfirmModal";
import FlashMessage from "@/components/FlashMessage";
import ApiMixin from "@/mixins/Api";
import ResultMessage from "@/components/ResultMessage";

export default {
  name: 'ExportDatabase',

  data() {
    return {
      selected_tables: [],
      order_by: 'name',
      order_direction: 'asc',
      query_result: {},
      toggle_all_rows: false,
      is_fetching_export_data: false,
      endpoint_database_export: 'database/export',
      filter_tables_text: '',
      advanced_settings_open: false,
      structure_data: 'both',
      compress_gzip: false,
      if_not_exists: false,
      reset_auto_increment: false,
      add_drop_table: false,
      add_locks: true,
      default_character_set: 'utf8',
      lock_tables: true,
      dump_result: {},
    }
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

  mounted() {

  },

  computed: {
    table_list_headers: function () {
      return {
        // key => label
        "name": 'Name',
        "engine": 'Engine',
        "collation": 'Collation',
        "size": 'Size',
        "rows": 'Rows',
        "auto_increment": 'Auto_increment',
      };
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

    tables_filtered() {
      if (!this.filter_tables_text) return this.tables;
      let vue = this;
      return this.tables.filter((table) => {
        return this.fussySearchMatch(vue.filter_tables_text, table.name);
      });
    },

  },

  watch: {
    toggle_all_rows() {
      if (this.toggle_all_rows) {
        this.selected_tables = [];
        for (let row_index in Object.keys(this.tables_filtered)) {
          this.selected_tables.push(this.tables_filtered[row_index].name);
        }
      } else {
        this.selected_tables = [];
      }
    }
  },

  methods: {

    exportTables() {
      if (this.is_fetching_export_data === true) return;

      let api_url_params = {'db': this.active_database};
      let api_url        = this.buildApiUrl(this.endpoint_database_export, api_url_params);

      let vue_instance = this;

      let params = new URLSearchParams();
      params.append('structure_data', this.structure_data);
      params.append('compress_gzip', this.compress_gzip);
      params.append('if_not_exists', this.if_not_exists);
      params.append('reset_auto_increment', this.reset_auto_increment);
      params.append('add_drop_table', this.add_drop_table);
      params.append('add_locks', this.add_locks);
      params.append('default_character_set', this.default_character_set);
      params.append('default_character_set', this.default_character_set);
      params.append('lock_tables', this.lock_tables);
      this.selected_tables.forEach(function (table_name) {
        let table_data = vue_instance.tables.find(table_data => table_data.name == table_name);
        if (table_data.type === 'VIEW') {
          params.append('views[]', table_name);
        } else {
          params.append('tables[]', table_name);
        }
      });

      vue_instance.is_fetching_export_data = true;
      this.$http.post(api_url, params, {
            responseType: 'arraybuffer', // we use arraybuffer, because with blob we cannot read the json response type (in case of error)
            timeout: 30000
          }
      ).then(response => {

        vue_instance.is_fetching_export_data = false;

        if (response.headers['content-type'] === "application/json") {
          // if(this.validateApiPostResponse(response) === false) return;

          let response_json_data = JSON.parse(Buffer.from(response.data).toString('utf8'));

          if (response_json_data.data.result == 'error') {
            vue_instance.dump_result = {type: 'error', message: response_json_data.data.message};
            scroll(0, 0);
            return;
          }
        }

        // put the data on a link and trigger a click on the link, downloading the file
        // let blob        = Buffer.from(response.data).toString('utf8');
        const url       = window.URL.createObjectURL(new Blob([response.data]));
        const a         = document.createElement('a');
        a.style.display = 'none';
        a.href          = url;
        // get the filename from the headers
        var filename    = response.headers['x-suggested-filename'];
        a.download      = filename;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
      }).catch(error => {
        this.handleApiError(error);
        vue_instance.is_fetching_export_data = false;
      })
    },

    fussySearchMatch: function (search, text) {
      search = search.toUpperCase();
      text   = text.toUpperCase();

      let j = -1; // remembers position of last found character

      // consider each search character one at a time
      for (let i = 0; i < search.length; i++) {
        let l = search[i];
        if (l == ' ') continue;     // ignore spaces

        j = text.indexOf(l, j + 1);     // search for character & update position
        if (j == -1) return false;  // if it's not found, exclude this item
      }
      return true;
    },

  },
}
</script>


<style scoped>
.export-database-container {
  min-width: 700px;
}

.label-box {
  width: 350px;
}

.label-box.with-description-text {
  @apply py-2;
}

.description-text {
  @apply text-gray-600;
}

.custom-checkbox {
  @apply py-2;
}
</style>
