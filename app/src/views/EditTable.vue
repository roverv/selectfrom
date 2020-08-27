<template>
  <div class="page-content-container">

    <div class="edit-table-container">

      <div class="table-page-header" v-if="page_is_edit">
        <h2>
          {{ tableid }}
        </h2>
        <table-nav :tableid="tableid"></table-nav>
        <div></div>
      </div>

      <h1 class="text-xl mb-4">
        <span v-if="page_is_create">Create new table</span>
        <span v-else>Edit table</span>
      </h1>

      <div v-if="query_result.result == 'error'" class="error-box mb-4">
        {{ query_result.message }}
      </div>

      <form method="post" autocomplete="off">

        <div class="mb-8">
          <div class="flex w-full mb-1">

            <div class="bg-dark-400 flex justify-between items-center w-64 pl-3 flex-shrink-0 relative flex-wrap mr-2">
              <div>Table name</div>
            </div>

            <input type="text" class="default-text-input w-64" v-on:keyup.esc="focusToApp" v-model="table_name">
          </div>

          <div class="flex w-full mb-1">

            <div class="bg-dark-400 flex justify-between items-center w-64 pl-3 flex-shrink-0 relative flex-wrap mr-2">
              <div>Engine</div>
            </div>

            <select class="default-select w-64" v-model="engine">
              <option value=""></option>
              <option v-for="engine in engines">
                {{ engine }}
              </option>
            </select>
          </div>

          <div class="flex w-full mb-1">

            <div class="bg-dark-400 flex justify-between items-center w-64 pl-3 flex-shrink-0 relative flex-wrap mr-2">
              <div>Collation</div>
            </div>

            <select name="Collation" class="default-select w-64" v-model="collation">
              <option value=""></option>

              <optgroup v-for="(collation_group, charset) in collations" :label="charset">
                <option v-for="collation in collation_group">
                  {{ collation }}
                </option>
              </optgroup>
            </select>
          </div>

          <div class="flex w-full mb-1">

            <div class="bg-dark-400 flex justify-between items-center w-64 pl-3 flex-shrink-0 relative flex-wrap mr-2">
              <div>Comment</div>
            </div>

            <input type="text" class="default-text-input w-64" v-on:keyup.esc="focusToApp" v-model="comment">
          </div>

          <div class="flex w-full mb-1">

            <div class="bg-dark-400 flex justify-between items-center w-64 pl-3 flex-shrink-0 relative flex-wrap mr-2">
              <div>Auto increment value</div>
            </div>

            <input type="number" step="1" class="default-number-input w-32" v-on:keyup.esc="focusToApp"
                   v-model="auto_increment_value">
          </div>

        </div>

        <h2 class="mb-3 text-lg">Columns</h2>


        <div class="columns-table mb-8" :class="{ 'edit' : page_is_edit }">
          <span class="head">Name</span>
          <span class="head">Type</span>
          <span class="head">Length</span>
          <span class="head">Attributes</span>
          <span class="head">NULL</span>
          <span class="head">Auto increment</span>
          <span class="head">Default value</span>
          <span class="head">Comment</span>
          <span class="head" v-if="page_is_edit">Change position</span>
          <span class=""></span>


          <template v-for="(column_row, index) in column_rows">
            <div class="columns-table-cell">
              <div>
                <input type="hidden" v-model="column_row.original_field_name">
              </div>
              <input type="text" v-model="column_row.name" class="default-text-input w-48"
                     v-on:keyup.esc="focusToApp">
            </div>

            <div class="columns-table-cell">
              <select class="default-select w-32" v-model="column_row.type">
                <optgroup v-for="(data_types_group, data_type_group) in data_types" :label="data_type_group">
                  <option v-for="data_type in data_types_group">
                    {{ data_type }}
                  </option>
                </optgroup>
              </select>
            </div>

            <div class="columns-table-cell ">
              <input type="text" v-model="column_row.length" class="default-text-input w-16"
                     v-on:keyup.esc="focusToApp">
            </div>

            <div class="columns-table-cell">
              <select class="default-select w-32" v-model="column_row.attribute"
                      v-if="columnIsNumeric(column_row.type)">
                <option></option>
                <option selected="">unsigned</option>
                <option>zerofill</option>
                <option>unsigned zerofill</option>
              </select>

              <select class="default-select w-48" v-model="column_row.attribute"
                      v-if="columnHasCollation(column_row.type)">
                <option value=""></option>

                <optgroup v-for="(collation_group, charset) in collations" :label="charset">
                  <option v-for="collation in collation_group">
                    {{ collation }}
                  </option>
                </optgroup>
              </select>
            </div>

            <div class="columns-table-cell justify-center">
              <label class="custom-checkbox no-label">
                <input type="checkbox" value="1" class="opacity-0" autocomplete="off" v-model="column_row.null">
                <span class="input-box"></span>
              </label>
            </div>

            <div class="columns-table-cell justify-center">
              <label class="custom-checkbox no-label">
                <input type="checkbox" :value="index" autocomplete="off" :true-value="index"
                       :false-value="!index"
                       v-model="auto_increment_column_row_index">
                <span class="input-box"></span>
              </label>
            </div>

            <div class="columns-table-cell">
              <label class="custom-checkbox no-label mr-2">
                <input type="checkbox" value="1" class="opacity-0" autocomplete="off"
                       v-model="column_row.has_default_value">
                <span class="input-box"></span>
              </label>
              <input type="text" class="default-text-input w-24" v-on:keyup.esc="focusToApp"
                     v-model="column_row.default_value">
            </div>

            <div class="columns-table-cell">
              <input type="text" class="default-text-input w-32" v-on:keyup.esc="focusToApp"
                     v-model="column_row.comment">
            </div>

            <div class="columns-table-cell" v-if="page_is_edit">
              <select class="default-select w-32" v-model="column_row.after_column"
                      v-if="column_row.hasOwnProperty('original_field_name')">
                <option></option>
                <option v-for="(column_name_after) in getAvailableAfterColumns(column_row, index)"
                        :value="column_name_after">
                  {{ column_name_after }}
                </option>
              </select>
            </div>

            <div class="flex items-start pt-1">
              <button @click="addColumn(index)" class="btn btn-icon ml-2" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 fill-current">
                  <path class="text-light-300" fill-rule="evenodd"
                        d="M17 11a1 1 0 0 1 0 2h-4v4a1 1 0 0 1-2 0v-4H7a1 1 0 0 1 0-2h4V7a1 1 0 0 1 2 0v4h4z"></path>
                </svg>
              </button>
              <a @click="removeColumn(index)" class="btn btn-icon ml-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 fill-current">
                  <rect width="12" height="2" x="6" y="11" class="text-light-300" rx="1"></rect>
                </svg>
              </a>
            </div>

          </template>

        </div>


      </form>

      <div class="flex justify-center">
        <button class="btn show-focus" @click="saveTable()">Save</button>
      </div>

    </div>
  </div>
</template>

<script>

  import axios from "axios";
  import TableNav from '@/components/TableNav.vue'
  import sqlFormatter from "sql-formatter";
  import HandleApiError from '@/mixins/HandleApiError.js'
  import {clone} from '../util'
  import ApiUrl from "@/mixins/ApiUrl";

  var default_column_row = {
    name: '',
    type: '',
    length: '',
    attribute: '',
    null: false,
    has_default_value: false,
    default_value: '',
    comment: '',
  };

  export default {
    name: 'edittable',
    props: ['tableid'],
    data() {
      return {
        endpoint_create_table: 'createtable.php',
        endpoint_alter_table: 'altertable.php',
        endpoint_table_creation_data: 'table_creation_data.php',
        collations: [],
        engines: [],
        data_types: [],
        data_type_attributes: [],
        table_name: '',
        auto_increment_column_row_index: false,
        engine: '',
        collation: '',
        comment: '',
        auto_increment_value: '',
        query_result: {},
        column_rows: [clone(default_column_row)],
      }
    },

    components: {
      TableNav,
    },

    mixins: [
      HandleApiError,
      ApiUrl
    ],

    mounted() {
      this.getTableCreationData();
    },

    computed: {
      active_database() {
        return this.$store.state.activeDatabase;
      },
      page_is_create() {
        return (this.$route.name == 'addtable');
      },
      page_is_edit() {
        return (this.$route.name == 'edittable');
      }
    },

    filters: {
      format: function (query_string) {
        return sqlFormatter.format(query_string);
      }
    },

    methods: {

      getTableCreationData() {

        let api_url_params = { 'db': this.active_database };
        if (this.page_is_edit) {
          api_url_params.tablename = this.tableid;
        }
        let api_url = this.buildApiUrl(this.endpoint_table_creation_data, api_url_params);

        axios.get(api_url).then(response => {
          this.collations           = response.data.collations;
          this.engines              = response.data.engines;
          this.data_types           = response.data.data_types;
          this.data_type_attributes = response.data.data_type_attributes;
          if (this.page_is_edit) {
            this.table_name                      = response.data.table_data.name;
            this.engine                          = response.data.table_data.engine;
            this.collation                       = response.data.table_data.collation;
            this.comment                         = response.data.table_data.comment;
            this.auto_increment_value            = response.data.table_data.auto_increment_value;
            this.auto_increment_column_row_index = false;
            for (let column_index in response.data.table_data.columns) {
              if (response.data.table_data.columns[column_index].hasOwnProperty('is_auto_increment') && response.data.table_data.columns[column_index].is_auto_increment === true) {
                this.auto_increment_column_row_index = parseInt(column_index);
              }
            }
            this.column_rows = response.data.table_data.columns;
            this.column_rows.map(function (column_row) {
              column_row.original_field_name = column_row.name;
              return column_row;
            });
          }
        }).catch(error => {
          this.handleApiError(error);
        })
      },

      focusToApp() {
        document.getElementById('app').focus();
      },

      addColumn(from_index) {
        let new_column_row = clone(default_column_row);
        if (this.page_is_edit) {
          new_column_row.after_column = this.column_rows[from_index].name;
        }
        this.column_rows.splice(from_index + 1, 0, new_column_row);
      },

      removeColumn(index) {
        this.column_rows.splice(index, 1);
        if (this.column_rows.length == 0) {
          this.addColumn(-1);
        }
      },

      columnIsNumeric(column_data_type) {
        return this.data_type_attributes.hasOwnProperty(column_data_type) && this.data_type_attributes[column_data_type] == 'numeric';
      },

      columnHasCollation(column_data_type) {
        return this.data_type_attributes.hasOwnProperty(column_data_type) && this.data_type_attributes[column_data_type] == 'collation';
      },

      saveTable() {

        let api_url_params = { 'db': this.active_database };
        if (this.page_is_edit) {
          api_url_params.tablename = this.tableid;
        }

        let api_url = '';
        if(this.page_is_create) {
          api_url = this.buildApiUrl(this.endpoint_create_table, api_url_params);
        }
        else {
          api_url = this.buildApiUrl(this.endpoint_alter_table, api_url_params);
        }

        let vue_instance = this;

        let params = new URLSearchParams();
        params.append('table_name', this.table_name);
        params.append('engine', this.engine);
        params.append('collation', this.collation);
        params.append('comment', this.comment);
        params.append('auto_increment_value', this.auto_increment_value);

        let auto_increment_field = 'false';
        if (typeof parseInt(this.auto_increment_column_row_index) == 'number') {
          auto_increment_field = this.column_rows[this.auto_increment_column_row_index]['name'];
        }

        params.append('auto_increment_field', auto_increment_field);
        for (let column_index in this.column_rows) {
          for (let column_field in this.column_rows[column_index]) {
            params.append('columns[' + column_index + '][' + column_field + ']', this.column_rows[column_index][column_field]);
          }
        }

        axios.post(api_url, params).then(response => {
          vue_instance.query_result = response.data;
          if (vue_instance.query_result.result == 'success' && this.$route.name == 'addtable') {
            this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", 'Table created.');
            this.$store.commit("flashmessage/ADD_FLASH_QUERY", vue_instance.query_result.query);
            this.$store.dispatch('refreshTables');
            vue_instance.$router.push({name: 'table', params: { database: this.active_database, tableid: vue_instance.table_name}});
          } else if (vue_instance.query_result.result == 'success') {
            let message = 'Table updated';
            this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", message);
            this.$store.commit("flashmessage/ADD_FLASH_QUERY", vue_instance.query_result.query);
            this.$store.dispatch('refreshTables');
            vue_instance.$router.push({name: 'table', params: { database: this.active_database, tableid: vue_instance.table_name}});
          }
          scroll(0, 0);
        }).catch(error => {
          this.handleApiError(error);
        })
      },

      getAvailableAfterColumns(current_column, index) {
        // filter out new columns
        let columns_after = this.column_rows.filter(column_row => column_row.hasOwnProperty('original_field_name'));
        columns_after     = columns_after.map(column_row => column_row.name);
        // filter out self
        columns_after     = columns_after.filter(column_name => column_name != current_column.name);
        // add first
        if (index !== 0) {
          columns_after.unshift('FIRST');
        }

        return columns_after;
      }

    }
  }
</script>

<style>

  .columns-table {
    display:               grid;
    grid-template-columns: repeat(9, auto);
  }

  .columns-table.edit {
    grid-template-columns: repeat(10, auto);
  }

  .columns-table .head {
    @apply bg-dark-400 py-3 px-2 mb-3 font-bold;
  }

  .columns-table-cell {
    @apply flex items-center mb-2 border-b border-light-100 pb-2 px-2;
  }


  .edit-table-container {
    min-width: 1100px;
  }

</style>
