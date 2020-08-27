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

      <div v-if="query_result.result == 'error'" class="error-box mb-4">
        {{ query_result.message }}
      </div>

      <form method="post" @submit.prevent="saveRow()" autocomplete="off">

        <div class="w-full" v-if="columns.length > 0">
          <div class="flex w-full mb-1" v-for="column in columns">

            <div class="bg-dark-400 flex justify-between items-center w-2/5 pl-3 flex-shrink-0 relative flex-wrap mr-2">
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

  import axios from "axios";
  import TableNav from '@/components/TableNav.vue'
  import sqlFormatter from "sql-formatter";
  import HandleApiError from '@/mixins/HandleApiError.js'
  import ApiUrl from "@/mixins/ApiUrl";

  export default {
    name: 'editrow',
    props: ['tableid', 'column', 'rowid'],
    data() {
      return {
        endpoint_table_structure: 'table_structure.php',
        endpoint_row_data: 'rowdata.php',
        endpoint_insert_row: 'insertrow.php',
        endpoint_update_row: 'updaterow.php',
        columns: [],
        row_data: {},
        columns_null: {},
        query_result: {},
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

        axios.get(api_url).then(response => {
          this.columns = response.data;
          if(this.$route.name == 'addrow') {
            this.fillDefaults();
          }

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

        axios.get(api_url).then(response => {
          this.row_data = response.data.data;
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

        axios.post(api_url, params).then(response => {
          vue_instance.query_result = response.data;
          if(vue_instance.query_result.result == 'success' && typeof vue_instance.query_result.inserted_row_id !== 'undefined') {
            this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", 'Row added.');
            this.$store.commit("flashmessage/ADD_FLASH_QUERY", vue_instance.query_result.query);
            vue_instance.$router.push({name: 'table', params: {database: this.active_database, tableid : vue_instance.tableid }});
          }
          else if(vue_instance.query_result.result == 'success') {
            let message = 'Row updated';
            if(vue_instance.query_result.affected_rows == 0) {
              message += ', no data was changed';
            }
            message += '.';
            this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", message);
            this.$store.commit("flashmessage/ADD_FLASH_QUERY", vue_instance.query_result.query);
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

