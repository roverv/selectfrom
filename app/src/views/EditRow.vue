<template>
  <div class="page-content-container">

    <div class="content-min-width">

      <div class="table-page-header">
        <h2>
          {{ tableid }}
        </h2>
        <table-nav :tableid="tableid"></table-nav>
        <div></div>
      </div>

      <h1 class="text-xl mb-4">
        <span v-if="$route.name == 'addrow'">Add row</span>
        <span v-else>Edit row</span>
      </h1>

      <result-message :message="query_result"></result-message>

      <form method="post" @submit.prevent="saveRow()" autocomplete="off" ref="edit_row_form">

        <div class="vertical-form" v-if="columns.length > 0">
          <div class="input-row" v-for="(column, key) in columns">

            <div class="label-box w-2/5">
              <div>{{ column.Field }}</div>
              <span class=" text-xs text-light-200 mr-3">
                {{ column.Type }}<span v-if="column.Extra != ''">, {{ column.Extra }}</span>
              </span>
            </div>

            <input type="text" v-model="row_data[column.Field]" @input="cellTextChanged($event, column.Field)" class="default-text-input flex-grow"
                   v-on:keyup.esc="focusToApp">

            <div v-if="column.Null === 'YES'" class=" pl-2 flex items-center w-16">
              <label class="custom-checkbox">
                <input type="checkbox" v-model="columns_null[column.Field]" @change="cellNullChanged($event, column.Field)" value="1" class="hidden" autocomplete="off">
                <span class="input-box"></span>
                <span><span class="text-gray-500 text-xs">NULL</span></span>
              </label>
            </div>
            <div v-else class="w-16"></div>
          </div>

        </div>

      </form>

      <div class="flex">
        <div class="w-2/5 mr-2"></div>
        <button class="btn show-focus mt-4" @click="saveRow()">Save</button>
      </div>

    </div>
  </div>
</template>

<script>

  import TableNav from '@/components/TableNav.vue'
  import sqlFormatter from "sql-formatter";
  import ApiMixin from "@/mixins/Api";
  import {has_deep_property} from "@/util";
  import ResultMessage from "@/components/ResultMessage";

  export default {
    name: 'editrow',
    props: ['tableid', 'column', 'rowid'],
    data() {
      return {
        endpoint_table_structure: 'table/structure',
        endpoint_row_data: 'row/get',
        endpoint_insert_row: 'row/insert',
        endpoint_update_row: 'row/update',
        columns: [],
        row_data: {},
        columns_null: {},
        query_result: {},
      }
    },

    components: {
      ResultMessage,
      TableNav,
    },

    mixins: [
      ApiMixin
    ],

    mounted() {
      this.getTableStructure();
      if(this.$route.name == 'editrow') {
        this.getRowData();
      }

    },

    computed: {
      active_database() {
        return this.$store.state.activeDatabase;
      },
    },

    filters: {
      format: function(query_string) {
        return sqlFormatter.format(query_string);
      }
    },

    methods: {

      getTableStructure() {
        let api_url_params = {'db': this.active_database, 'tablename': this.tableid};
        let api_url = this.buildApiUrl(this.endpoint_table_structure, api_url_params);

        this.$http.get(api_url).then(response => {
          this.columns = response.data.data;
          if(this.$route.name == 'addrow') {
            this.fillDefaults();
          }

          // set focus on first input when inputs are rendered
          this.$nextTick(function() {
            if(this.$refs.edit_row_form.getElementsByTagName('input').length > 0) {
              this.$refs.edit_row_form.getElementsByTagName('input')[0].focus();
            }
          });
        }).catch(error => {
          this.handleApiError(error);
        })
      },

      getRowData() {
        let api_url_params = {
          'db': this.active_database,
          'tablename': this.tableid,
          'column': this.column,
          'value': this.rowid
        };
        let api_url = this.buildApiUrl(this.endpoint_row_data, api_url_params);

        this.$http.get(api_url).then(response => {
          if(has_deep_property(response, 'data', 'data', 'result') === false) {
            this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", { type: 'error', message: 'Invalid response'});
            this.$router.push({name: 'table', params: {database: this.active_database, 'tableid': this.tableid}});
            return false;
          }

          if(response.data.data.result == 'error') {
            this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", { type: 'error', message: response.data.data.message});
            this.$router.push({name: 'table', params: {database: this.active_database, 'tableid': this.tableid}});
            return;
          }

          if(response.data.data.row == false) {
            this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", { type: 'error', message: 'Row not found'});
            this.$router.push({name: 'table', params: {database: this.active_database, 'tableid': this.tableid}});
            return;
          }

          this.row_data = response.data.data.row;

          for (let column_name in this.row_data) {
            this.columns_null[column_name] = (this.row_data[column_name] === null) ? true : false;
          }
        }).catch(error => {
          this.handleApiError(error);
        })
      },

      focusToApp() {
        document.getElementById('app').focus();
      },

      saveRow() {

        let api_url_params = {
          'db': this.active_database,
          'tablename': this.tableid,
          'column': this.column,
          'value': this.rowid
        };

        let api_url = '';
        if(this.$route.name == 'addrow') {
          api_url = this.buildApiUrl(this.endpoint_insert_row, api_url_params);
        }
        else {
          api_url = this.buildApiUrl(this.endpoint_update_row, api_url_params);
        }

        let vue_instance = this;

        let params = new URLSearchParams();
        for (let column_name in this.row_data) {
          params.append(column_name, (this.columns_null[column_name] === true && this.row_data[column_name] == '') ? null : this.row_data[column_name]);
        }

        this.$http.post(api_url, params).then(response => {
          if(this.validateApiPostResponse(response) === false) return;

          let api_result = response.data.data;

          if(api_result.result == 'error') {
            vue_instance.query_result = {type: 'error', message: api_result.message};
            scroll(0,0);
            return;
          }

          if(api_result.result == 'success' && typeof api_result.inserted_row_id !== 'undefined') {
            this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", {
              message: 'Row added',
              type: 'success',
              query: api_result.query
            });
            vue_instance.$router.push({name: 'table', params: {database: this.active_database, tableid : vue_instance.tableid }});
          }
          else if(api_result.result == 'success') {
            let message = 'Row updated';
            if(api_result.affected_rows == 0) {
              message += ', no data was changed';
            }
            message += '.';
            this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", {
              message: message,
              type: 'success',
              query: api_result.query
            });
            vue_instance.$router.push({name: 'table', params: {database: this.active_database, tableid : vue_instance.tableid }});
          }
          scroll(0,0);
        }).catch(error => {
          this.handleApiError(error);
        })
      },

      fillDefaults() {
        for (let column_key in this.columns) {
          let column_data = this.columns[column_key];
          this.$set(this.row_data, column_data.Field, column_data.Default);
          // set the null value to true if the field is nullable and the default is null
          this.$set(this.columns_null, column_data.Field, (column_data.Null === 'YES' && column_data.Default === null) ? true : false);
        }
      },

      toggleQuerySize(event) {
        if(event.target.classList.contains('truncate')) {
          event.target.classList.remove('w-64' ,'truncate');
        }
        else {
          event.target.classList.add('w-64' ,'truncate');
        }
      },

      cellTextChanged(event, column_key) {
        // set null checkbox unchecked if value is entered
        if(event.target.value && this.columns_null[column_key] === true) {
          this.columns_null[column_key] = false;
        }
      },

      cellNullChanged(event, column_key) {
        // set data to empty string if null is checked
        if(event.target.checked === true) {
          this.row_data[column_key] = '';
        }
      },

    }
  }
</script>


<style>

</style>

