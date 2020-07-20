<template>
  <div class="page-content-container">

    <div class="edit-table-container">

      <div class="table-page-header" v-if="$route.name != 'addtable'">
        <h2>
          {{ tableid }}
        </h2>
        <table-nav :tableid="tableid"></table-nav>
        <div></div>
      </div>

      <h1 class="text-xl mb-4">
        <span v-if="$route.name == 'addtable'">Create new table</span>
        <span v-else>Edit table</span>
      </h1>

      <div v-if="query_result.result == 'error'" class="error-box mb-4">
        {{ query_result.message }}
      </div>

      <form method="post" @submit.prevent="saveRow()" autocomplete="off">

        <div class="mb-8">
          <div class="flex w-full mb-1">

            <div class="bg-dark-400 flex justify-between items-center w-64 pl-3 flex-shrink-0 relative flex-wrap mr-2">
              <div>Table name</div>
            </div>

            <input type="text" class="default-text-input w-64" v-on:keyup.esc="focusToApp">
          </div>

          <div class="flex w-full mb-1">

            <div class="bg-dark-400 flex justify-between items-center w-64 pl-3 flex-shrink-0 relative flex-wrap mr-2">
              <div>Engine</div>
            </div>

            <select class="default-select w-64">
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

            <select name="Collation" class="default-select w-64">
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

            <input type="text" class="default-text-input w-64" v-on:keyup.esc="focusToApp">
          </div>

        </div>

        <h2 class="mb-3 text-lg">Columns</h2>


        <div class="columns-table mb-8">
          <span class="head">Name</span>
          <span class="head">Type</span>
          <span class="head">Length</span>
          <span class="head">Attributes</span>
          <span class="head">NULL</span>
          <span class="head">Auto increment</span>
          <span class="head">Default value</span>
          <span class="head">Comment</span>
          <span class=""></span>


          <template v-for="(column_row, index) in column_rows">
            <div class="columns-table-cell">
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
              <select class="default-select w-32" v-model="column_row.attribute" v-if="columnIsNumeric(column_row.type)">
                <option></option>
                <option selected="">unsigned</option>
                <option>zerofill</option>
                <option>unsigned zerofill</option>
              </select>

              <select class="default-select w-48" v-model="column_row.attribute" v-if="columnHasCollation(column_row.type)">
                <option value=""></option>

                <optgroup v-for="(collation_group, charset) in collations" :label="charset">
                  <option v-for="collation in collation_group">
                    {{ collation }}
                  </option>
                </optgroup>
              </select>
            </div>

            <div class="columns-table-cell justify-center">
              <label class="custom-checkbox">
                <input type="checkbox" value="1" class="opacity-0" autocomplete="off" v-model="column_row.null">
                <span class="input-box"></span>
              </label>
            </div>

            <div class="columns-table-cell justify-center">
              <label class="custom-checkbox">
                <input type="checkbox" value="1" class="opacity-0" autocomplete="off" v-model="column_row.auto_increment">
                <span class="input-box"></span>
              </label>
            </div>

            <div class="columns-table-cell">
              <label class="custom-checkbox">
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

            <div class="flex items-start pt-1">
              <a @click="addColumn(index)" class="btn icon ml-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 fill-current">
                  <path class="text-light-300" fill-rule="evenodd"
                        d="M17 11a1 1 0 0 1 0 2h-4v4a1 1 0 0 1-2 0v-4H7a1 1 0 0 1 0-2h4V7a1 1 0 0 1 2 0v4h4z"></path>
                </svg>
              </a>
              <a @click="removeColumn(index)" class="btn icon ml-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 fill-current"
                     style="transform: rotate(45deg);">
                  <path class="text-light-300" fill-rule="evenodd"
                        d="M17 11a1 1 0 0 1 0 2h-4v4a1 1 0 0 1-2 0v-4H7a1 1 0 0 1 0-2h4V7a1 1 0 0 1 2 0v4h4z"></path>
                </svg>
              </a>
            </div>

          </template>

        </div>


      </form>

      <div class="flex justify-center">
        <button class="btn" @click="saveRow()">Save</button>
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

  var default_column_row = {
    name: '',
    type: '',
    length: '',
    attribute: '',
    null: false,
    auto_increment: false,
    has_default_value: false,
    default_value: '',
    comment: '',
  };

  export default {
    name: 'edittable',
    props: ['tableid'],
    data() {
      return {
        endpoint_create_table: 'createtable.php?db=',
        endpoint_table_creation_data: 'table_creation_data.php?db=',
        collations: [],
        engines: [],
        data_types: [],
        data_type_attributes: [],
        query_result: {},
        column_rows: [clone(default_column_row)],
      }
    },

    components: {
      TableNav,
    },

    mixins: [
      HandleApiError
    ],

    mounted() {
      this.getTableCreationData();
      // if(this.$route.name == 'editrow') {
      //   this.getRowData();
      // }

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
      format: function (query_string) {
        return sqlFormatter.format(query_string);
      }
    },

    methods: {

      getTableCreationData() {
        let api_url = this.api_endpoint + this.endpoint_table_creation_data;

        axios.get(api_url).then(response => {
          this.collations = response.data.collations;
          this.engines    = response.data.engines;
          this.data_types    = response.data.data_types;
          this.data_type_attributes    = response.data.data_type_attributes;
        }).catch(error => {
          this.handleApiError(error);
        })
      },

      focusToApp() {
        document.getElementById('app').focus();
      },

      addColumn(from_index) {
        let new_column_row = clone(default_column_row);
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
      }

    }
  }
</script>

<style>

  .columns-table {
    display:               grid;
    grid-template-columns: repeat(9, auto);
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
