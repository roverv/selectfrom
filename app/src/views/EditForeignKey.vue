<template>
  <div class="page-content-container">

    <div class="edit-table-container">

      <div class="table-page-header">
        <h2>
          {{ tableid }}
        </h2>
        <table-nav :tableid="tableid"></table-nav>
        <div></div>
      </div>

      <h1 class="text-xl mb-4">
        <span v-if="page_is_create">Create new foreign key</span>
        <span v-else>Edit foreign key</span>
      </h1>

      <result-message :message="query_result"></result-message>

      <spinner v-if="is_fetching_data"></spinner>

      <form method="post" autocomplete="off">

        <div class="vertical-form">
          <div class="input-row">

            <div class="label-box">
              <div>Foreign key name</div>
            </div>

            <input type="text" class="default-text-input w-64" v-on:keyup.esc="focusToApp" v-model="name"
                   placeholder="Leave empty for auto generate" v-focus>
          </div>

          <div class="input-row">

            <div class="label-box">
              <div>On delete</div>
            </div>

            <div>
              <select class="default-select w-64" v-model="on_delete" v-on:keyup.esc="focusToApp">
                <option value=""></option>
                <option v-for="foreign_key_rule in foreign_key_rules">
                  {{ foreign_key_rule }}
                </option>
              </select>
              <p class="text-gray-500" v-if="page_is_create && on_delete == 'SET NULL'">
                If you specify a SET NULL action, make sure that you have not declared the columns in the child table as
                NOT NULL.
              </p>
            </div>

          </div>

          <div class="input-row">

            <div class="label-box">
              <div>On update</div>
            </div>

            <div>
              <select class="default-select w-64" v-model="on_update" v-on:keyup.esc="focusToApp">
                <option value=""></option>
                <option v-for="foreign_key_rule in foreign_key_rules">
                  {{ foreign_key_rule }}
                </option>
              </select>
              <p class="text-gray-500" v-if="page_is_create && on_update == 'SET NULL'">
                If you specify a SET NULL action, make sure that you have not declared the columns in the child table as
                NOT NULL.
              </p>
            </div>

          </div>

        </div>


        <h2 class="mb-3 text-lg">Columns</h2>

        <div class="vertical-form mb-4">
          <div class="input-row">
            <div class="label-box">
              <div>Reference table</div>
            </div>
            <select class="default-select w-64" v-model="reference_table" v-on:keyup.esc="focusToApp" @change="referenceTableChanged()">
              <option value=""></option>
              <option v-for="referencable_table in referencable_tables">
                {{ referencable_table.name }}
              </option>
            </select>
          </div>

        </div>


        <div class="columns-table mb-8 w-auto" :class="{ 'edit' : page_is_edit }">
          <span class="head">Column</span>
          <span class="head">Reference column</span>
          <span class=""></span>

          <template v-for="(column, index) in columns">

            <template v-if="validateSameDataType(index) === false">
              <p class="full-columns-table-cell text-warning text-center mb-2">
                Corresponding columns in the foreign key and the referenced key must have <u>similar data types</u>.
              </p>
              <div style="grid-column: 3/4"></div>
            </template>

            <template v-if="validatePrecisionType(index) === false">
              <p class="full-columns-table-cell text-warning text-center mb-2">
                The <u>size and sign</u> of fixed precision types such as INTEGER and DECIMAL must be the same
                <br>
                <b>{{ getColumnTypes(column.column).column_type }}</b> vs <b>{{ getReferenceColumnTypes(column.reference_column).column_type }}</b>
              </p>
              <div style="grid-column: 3/4"></div>
            </template>

            <div class="columns-table-cell">
              <span class="text-gray-500">{{ tableid }}.</span>&nbsp;
              <select class="default-select w-64" v-model="column.column" v-on:keyup.esc="focusToApp">
                <option value=""></option>
                <option v-for="table_column in table_columns" :value="table_column.column_name">
                  {{ table_column.column_name }} ({{ table_column.data_type }})
                </option>
              </select>
            </div>

            <div class="columns-table-cell" v-if="reference_table">
              <div>
              <span class="text-gray-500">{{ reference_table }}.</span>&nbsp;
              <select class="default-select w-64" v-model="column.reference_column" v-on:keyup.esc="focusToApp">
                <option value=""></option>
                <option v-for="referencable_column in referencable_columns" :value="referencable_column.column_name">
                  {{ referencable_column.column_name }} ({{ referencable_column.data_type }})
                </option>
              </select>
              </div>
            </div>
            <div class="columns-table-cell" v-else>
              Choose a reference table
            </div>

            <div class="flex items-start pt-1">
              <button @click="addColumn(index)" class="btn btn-icon ml-2 show-focus" type="button">
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
        <button class="btn show-focus" @click="saveForeignKey()">Save</button>
      </div>

    </div>
  </div>
</template>

<script>

import TableNav from '@/components/TableNav.vue'
import Spinner from "@/components/Spinner";
import ApiMixin from "@/mixins/Api";
import ResultMessage from "@/components/ResultMessage";
import {clone} from "@/util";

var default_column_row = {
  column: '',
  reference_column: ''
};

export default {
  name: 'editforeignkey',
  props: ['tableid', 'foreignkeyname'],
  data() {
    return {
      endpoint_create_foreign_key: 'foreignkey/create',
      endpoint_alter_foreign_key: 'foreignkey/alter',
      endpoint_get_foreign_key: 'foreignkey/get',
      query_result: {},
      is_fetching_data: false,
      foreign_key_rules: [],
      name: '',
      on_delete: '',
      on_update: '',
      reference_table: '',
      columns: [clone(default_column_row)],
    }
  },

  components: {
    ResultMessage,
    TableNav,
    Spinner,
  },

  mixins: [
    ApiMixin
  ],

  mounted() {
    this.getForeignKey();
  },

  computed: {
    active_database() {
      return this.$store.state.activeDatabase;
    },
    page_is_create() {
      return (this.$route.name == 'addforeignkey');
    },
    page_is_edit() {
      return (this.$route.name == 'editforeignkey');
    },
    tables() {
      return this.$store.getters["tables/tables"];
    },
    referencable_tables() {
      return this.tables.filter(table_data => (table_data.type !== 'VIEW' && table_data.has_foreign_key_support === true));
    },
    tables_with_columns() {
      return this.$store.getters["tables/tablesWithColumns"];
    },
    referencable_columns() {
      if (!this.reference_table) return [];
      return this.tables_with_columns[this.reference_table];
    },
    table_columns() {
      return this.tables_with_columns[this.tableid];
    }
  },

  methods: {

    getForeignKey() {
      this.is_fetching_data = true;

      let api_url_params = {'db': this.active_database, 'tablename': this.tableid};
      if (this.page_is_edit) {
        api_url_params.foreign_key_name = this.foreignkeyname;
      }
      let api_url = this.buildApiUrl(this.endpoint_get_foreign_key, api_url_params);

      this.$http.get(api_url).then(response => {
        let reponse_data       = response.data.data;
        this.foreign_key_rules = reponse_data.foreign_key_rules;
        if (this.page_is_edit) {
          this.name            = reponse_data.foreign_key.name;
          this.on_update       = reponse_data.foreign_key.on_update;
          this.on_delete       = reponse_data.foreign_key.on_update;
          this.reference_table = reponse_data.foreign_key.reference_table;
          this.columns         = reponse_data.foreign_key.columns.map(function (column) {
            return {'column': column.column_name, 'reference_column': column.reference_column_name};
          });
        }
        this.is_fetching_data = false;
      }).catch(error => {
        this.handleApiError(error);
      })
    },

    focusToApp() {
      document.getElementById('app').focus();
    },

    addColumn(from_index) {
      let new_column_row = clone(default_column_row);
      this.columns.splice(from_index + 1, 0, new_column_row);
    },

    removeColumn(index) {
      this.columns.splice(index, 1);
      if (this.columns.length == 0) {
        this.addColumn(-1);
      }
    },

    saveForeignKey() {

      let api_url_params = {'db': this.active_database, 'tablename': this.tableid};
      if (this.page_is_edit) {
        api_url_params.foreign_key_name = this.foreignkeyname;
      }

      let api_url = '';
      if (this.page_is_create) {
        api_url = this.buildApiUrl(this.endpoint_create_foreign_key, api_url_params);
      } else {
        api_url = this.buildApiUrl(this.endpoint_alter_foreign_key, api_url_params);
      }

      let vue_instance = this;

      let params = new URLSearchParams();
      params.append('name', this.name);
      params.append('on_delete', this.on_delete);
      params.append('on_update', this.on_update);
      params.append('reference_table', this.reference_table);
      for (let column_index in this.columns) {
        params.append('columns[' + column_index + '][column_name]', this.columns[column_index].column);
        params.append('columns[' + column_index + '][reference_column_name]', this.columns[column_index].reference_column);
      }

      this.$http.post(api_url, params).then(response => {

        if (this.validateApiPostResponse(response) === false) return;

        let api_result = response.data.data;

        if (api_result.result == 'error') {
          vue_instance.query_result = {type: 'error', message: api_result.message};
          scroll(0, 0);
          return;
        }

        if (api_result.result == 'success' && this.page_is_create) {
          this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", {
            type: 'success',
            message: 'Foreign key created',
            query: api_result.query
          });
          vue_instance.$router.push({
            name: 'foreignkeys',
            params: {database: this.active_database, tableid: vue_instance.tableid}
          });
        } else if (api_result.result == 'success') {
          this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", {
            type: 'success',
            message: 'Foreign key updated',
            query: api_result.query
          });
          vue_instance.$router.push({
            name: 'foreignkeys',
            params: {database: this.active_database, tableid: vue_instance.tableid}
          });
        }
      }).catch(error => {
        this.handleApiError(error);
      })
    },

    validateSameDataType(column_index) {
      if(!this.reference_table) return;

      let reference_column = this.columns[column_index].reference_column;
      let column = this.columns[column_index].column;

      if(!reference_column || !column) return;

      let column_data = this.getColumnTypes(column);
      let reference_column_data = this.getReferenceColumnTypes(reference_column);

      // this happens when changing the reference table
      if(!reference_column_data || reference_column_data.hasOwnProperty('data_type') === false) return;

      return column_data.data_type === reference_column_data.data_type;
    },

    validatePrecisionType(column_index) {
      if(!this.reference_table) return;

      if(this.validateSameDataType(column_index) !== true) return;

      let reference_column = this.columns[column_index].reference_column;
      let column = this.columns[column_index].column;

      if(!reference_column || !column) return;

      let column_data = this.getColumnTypes(column);
      let reference_column_data = this.getReferenceColumnTypes(reference_column);

      /** @backendlogic */
      if(['tinyint', 'smallint', 'mediumint', 'int', 'bigint', 'decimal'].includes(column_data.data_type) === false) return;

      // this happens when changing the reference table
      if(!reference_column_data || reference_column_data.hasOwnProperty('column_type') === false) return;

      return column_data.column_type === reference_column_data.column_type;
    },

    getColumnTypes(column) {
      return this.tables_with_columns[this.tableid].find(column_data => column_data.column_name == column);
    },

    getReferenceColumnTypes(reference_column) {
      return this.tables_with_columns[this.reference_table].find(column_data => column_data.column_name == reference_column)
    },

    referenceTableChanged() {
      // reset reference table columns on change
      for (let column_index in this.columns) {
        this.columns.reference_column == '';
      }
    }

  }
}
</script>

<style scoped>

.columns-table {
  grid-template-columns: repeat(3, auto);
}

.full-columns-table-cell {
  grid-column: 1/3;
}

.edit-table-container {
  min-width: 800px;
}

</style>
