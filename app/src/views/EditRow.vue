<template>
  <div class="flex justify-center">

    <div style="width: 900px;">

      <h1 class="text-xl mb-4">Edit row of {{ tableid }}</h1>

      <div v-if="query_result.result == 'error'" class="error-box mb-4">
        {{ query_result.message }}
      </div>

      <div v-if="query_result.result == 'success'" class="success-box mb-3">
          Row updated<span v-if="query_result.affected_rows == 0">, no data was changed</span>.
      </div>

      <div v-if="query_result.result" class="flex items-center mb-8">
        <div @click="toggleQuerySize($event)" class="cursor-pointer inline-block text-sm text-gray-300 border border-dashed border-gray-500 py-1 px-2 bg-light-200 whitespace-pre w-64 truncate ">{{ query_result.query | format }}</div>
        <a>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 ml-2 fill-current">
            <path class="text-gray-400"
                  d="M4 14a1 1 0 0 1 .3-.7l11-11a1 1 0 0 1 1.4 0l3 3a1 1 0 0 1 0 1.4l-11 11a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-3z"></path>
            <rect width="20" height="2" x="2" y="20" class="text-gray-500" rx="1"></rect>
          </svg>
        </a>
      </div>

      <form method="post" @submit.prevent="endpoint_update_row()" autocomplete="off">

        <div class="w-full" v-if="columns.length > 0">
          <div class="flex w-full mb-1" v-for="column in columns">

            <div class="bg-dark-400 flex justify-between items-center w-2/5 pl-3 flex-shrink-0 relative flex-wrap mr-2">
              <div>{{ column.Field }}</div>
              <span class=" text-xs text-light-200 mr-3">{{ column.Type }}</span>
            </div>

            <input type="text" v-model="row_data[column.Field]" @input="cellTextChanged($event, column.Field)" class="default-text-input pl-4 flex-grow"
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
        <button class="btn mt-4" @click="saveRow()">Save</button>
      </div>

    </div>
  </div>
</template>

<script>


  import axios from "axios";
  import sqlFormatter from "sql-formatter";

  export default {
    name: 'editrow',
    props: ['tableid', 'column', 'rowid'],
    data() {
      return {
        endpoint_table_structure: 'table_structure.php?db=',
        endpoint_row_data: 'rowdata.php?db=',
        endpoint_update_row: 'updaterow.php?db=',
        columns: [],
        row_data: {},
        columns_null: {},
        query_result: {},
      }
    },

    mounted() {
      this.getTableStructure();
      this.getRowData();
    },

    computed: {
      active_database() {
        return this.$store.state.activeDatabase;
      },
      api_endpoint() {
        return this.$store.state.apiEndPoint;
      },
    },

    filters: {
      format: function(query_string) {
        return sqlFormatter.format(query_string);
      }
    },

    methods: {

      getTableStructure() {
        let api_url = this.api_endpoint;
        if (this.tableid) {
          api_url += this.endpoint_table_structure + this.active_database + '&tablename=' + this.tableid;
        }

        axios.get(api_url).then(response => {
          this.columns = response.data;

        }).catch(error => {
          console.log('-----error-------');
          console.log(error);
        })
      },

      getRowData() {

        let api_url = this.api_endpoint;
        api_url += this.endpoint_row_data + this.active_database + '&tablename=' + this.tableid;
        api_url += '&column=' + this.column + '&value=' + this.rowid;

        axios.get(api_url).then(response => {
          this.row_data = response.data.data;
          for (let column_name in this.row_data) {
            this.columns_null[column_name] = (this.row_data[column_name] === null) ? true : false;
          }
        }).catch(error => {
          console.log('-----error-------');
          console.log(error);
        })
      },

      focusToApp() {
        document.getElementById('app').focus();
      },

      saveRow() {
        let api_url = this.api_endpoint;
        api_url += this.endpoint_update_row + this.active_database + '&tablename=' + this.tableid;
        api_url += '&column=' + this.column + '&value=' + this.rowid;

        let vue_instance = this;

        let params = new URLSearchParams();
        for (let column_name in this.row_data) {
          params.append(column_name, (this.columns_null[column_name] === true && this.row_data[column_name] == '') ? null : this.row_data[column_name]);
        }

        axios.post(api_url, params).then(response => {
          vue_instance.query_result = response.data;
          if(vue_instance.query_result.result == 'success') {
            // refresh data when query was success
            vue_instance.getRowData();
          }
          scroll(0,0);
        }).catch(error => {
          console.log('-----error-------');
          console.log(error);
        })
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

