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
        <span v-if="page_is_create">Create new index</span>
        <span v-else>Edit index</span>
      </h1>

      <result-message :message="query_result"></result-message>

      <spinner v-if="is_fetching_data"></spinner>

      <form method="post" autocomplete="off">

        <div class="vertical-form">

          <div class="input-row">
            <div class="label-box">
              <div>Index name</div>
            </div>
            <input type="text" class="default-text-input w-64" v-on:keyup.esc="focusToApp" v-model="name"
                   placeholder="Leave empty for auto generate" v-focus>
          </div>

          <div class="input-row">
            <div class="label-box">
              <div>Type</div>
            </div>
            <select class="default-select w-64" v-model="type" v-on:keyup.esc="focusToApp">
              <option value=""></option>
              <option v-for="index_type in index_types">
                {{ index_type }}
              </option>
            </select>
          </div>

        </div>


        <h2 class="mb-3 text-lg">Columns</h2>

        <div class="columns-table mb-8 w-auto" :class="{ 'edit' : page_is_edit }">
          <span class="head">Name</span>
          <span class="head">Length</span>
          <span class=""></span>

          <template v-for="(index_column, index) in columns">

            <div class="columns-table-cell">
              <select class="default-select w-64" v-model="index_column.name" v-on:keyup.esc="focusToApp">
                <option value=""></option>
                <option v-for="table_column in table_columns" :value="table_column.column_name">
                  {{ table_column.column_name }} | {{ table_column.column_type }}
                </option>
              </select>
            </div>

            <div class="columns-table-cell ">
              <input type="text" v-model="index_column.length" class="default-text-input w-16"
                     v-on:keyup.esc="focusToApp">
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
        <button class="btn show-focus" @click="saveIndex()">Save</button>
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
  name: '',
  length: null,
};

export default {
  name: 'editindex',
  props: ['tableid', 'indexname'],
  data() {
    return {
      endpoint_create_index: 'index/create',
      endpoint_alter_index: 'index/alter',
      endpoint_get_index: 'index/get',
      query_result: {},
      is_fetching_data: false,
      index_types: [],
      name: '',
      columns: [clone(default_column_row)],
      type: null,
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
    this.getIndex();
  },

  computed: {
    active_database() {
      return this.$store.state.activeDatabase;
    },
    page_is_create() {
      return (this.$route.name == 'addindex');
    },
    page_is_edit() {
      return (this.$route.name == 'editindex');
    },
    table_columns() {
      return this.$store.getters["tables/columnsOfTable"](this.tableid);
    },
  },

  methods: {

    getIndex() {
      this.is_fetching_data = true;

      let api_url_params = {'db': this.active_database, 'tablename': this.tableid};
      if (this.page_is_edit) {
        api_url_params.indexname = this.indexname;
      }
      let api_url = this.buildApiUrl(this.endpoint_get_index, api_url_params);

      this.$http.get(api_url).then(response => {
        let reponse_data   = response.data.data;
        this.index_types   = reponse_data.index_types;
        if (this.page_is_edit) {
          this.name    = reponse_data.index.name;
          this.type    = reponse_data.index.type;
          this.columns = reponse_data.index.columns;
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

    saveIndex() {

      let api_url_params = {'db': this.active_database, 'tablename': this.tableid};
      if (this.page_is_edit) {
        api_url_params.indexname = this.indexname;
      }

      let api_url = '';
      if (this.page_is_create) {
        api_url = this.buildApiUrl(this.endpoint_create_index, api_url_params);
      } else {
        api_url = this.buildApiUrl(this.endpoint_alter_index, api_url_params);
      }

      let vue_instance = this;

      let params = new URLSearchParams();
      params.append('name', this.name);
      params.append('type', this.type);
      for (let column_index in this.columns) {
        params.append('columns[' + column_index + '][name]', this.columns[column_index].name);
        params.append('columns[' + column_index + '][length]', this.columns[column_index].length);
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
            message: 'Index created',
            query: api_result.query
          });
          vue_instance.$router.push({
            name: 'indexes',
            params: {database: this.active_database, tableid: vue_instance.tableid}
          });
        } else if (api_result.result == 'success') {
          this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", {
            type: 'success',
            message: 'Index updated',
            query: api_result.query
          });
          vue_instance.$router.push({
            name: 'indexes',
            params: {database: this.active_database, tableid: vue_instance.tableid}
          });
        }
      }).catch(error => {
        this.handleApiError(error);
      })
    },
  }
}
</script>

<style scoped>

.columns-table {
  grid-template-columns: repeat(3, auto);
}

.edit-table-container {
  min-width: 800px;
}

</style>
