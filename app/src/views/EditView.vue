<template>
  <div class="page-content-container">

    <div class="grid-container-content">
      <div class="content-header content-min-width">

        <div class="table-page-header" v-if="page_is_edit">
          <h2>
            {{ tableid }}
          </h2>
          <table-nav :tableid="tableid"></table-nav>
          <div></div>
        </div>

        <h1 class="text-xl mb-4">
          <span v-if="page_is_create">Create new view</span>
          <span v-else>Edit view</span>
        </h1>

        <result-message :message="query_result"></result-message>

        <spinner v-if="is_fetching_data"></spinner>

        <form method="post" autocomplete="off">

          <div class="mb-8">
            <div class="flex w-full mb-4">

              <div class="bg-dark-400 flex justify-between items-center w-64 pl-3 flex-shrink-0 relative flex-wrap mr-2">
                <div>View name</div>
              </div>

              <input type="text" class="default-text-input w-64" v-on:keyup.esc="focusToApp" v-model="view_name">
            </div>

            <h3 class="mb-2">Definition</h3>

            <textarea ref="view_definition" id="view_definition"></textarea>

          </div>


        </form>

        <div class="flex justify-center">
          <button class="btn show-focus" @click="saveView()">Save</button>
        </div>

      </div>
    </div>


  </div>
</template>

<script>

import TableNav from '@/components/TableNav.vue'
import CodeMirror from 'codemirror/lib/codemirror.js';
import 'codemirror/lib/codemirror.css';
import "codemirror/mode/sql/sql.js";
import "codemirror/addon/hint/show-hint.css";
import "codemirror/addon/hint/show-hint";
import "codemirror/addon/hint/sql-hint";
import sqlFormatter from "sql-formatter";
import {clone} from '../util'
import Spinner from "@/components/Spinner";
import ApiMixin from "@/mixins/Api";
import ResultMessage from "@/components/ResultMessage";

export default {
  name: 'editview',
  props: ['tableid'],
  data() {
    return {
      endpoint_create_view: 'view/create',
      endpoint_alter_view: 'view/alter',
      endpoint_view_creation_data: 'view/creationdata',
      view_name: '',
      view_definition: '',
      query_result: {},
      is_fetching_data: false,
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

    this.$refs.view_definition.focus();

    let vue_instance = this;

    window.editor = CodeMirror.fromTextArea(document.getElementById('view_definition'), {
      mode: "text/x-mysql",
      indentWithTabs: true,
      smartIndent: true,
      lineNumbers: true,
      matchBrackets: true,
      autofocus: true,
      viewportMargin: Infinity,
      extraKeys: {
        "Ctrl-Space": "autocomplete",
        'Ctrl-Enter': function () {
          vue_instance.saveView();
        },
        'Shift-Enter': function () {
          vue_instance.formatQuery();
        },
        "Esc": function () {
          document.getElementById('app').focus();
        }
      },
      hintOptions: {
        tables: this.tables_with_columns
      }
    });

    if (this.page_is_edit) {
      this.getViewCreationData();
    }
  },

  computed: {
    active_database() {
      return this.$store.state.activeDatabase;
    },
    page_is_create() {
      return (this.$route.name == 'addview');
    },
    page_is_edit() {
      return (this.$route.name == 'editview');
    },
    tables_with_columns() {
      return this.$store.getters["tables/tablesWithColumns"];
    },
  },

  filters: {
    format: function (query_string) {
      return sqlFormatter.format(query_string);
    }
  },

  methods: {

    getViewCreationData() {
      this.is_fetching_data = true;

      let api_url_params = {'db': this.active_database};
      api_url_params.tablename = this.tableid;

      let api_url = this.buildApiUrl(this.endpoint_view_creation_data, api_url_params);

      this.$http.get(api_url).then(response => {
        let reponse_data = response.data.data;
        if (this.page_is_edit) {
          this.view_name = reponse_data.view_name;
          this.view_definition = reponse_data.view_definition;
          window.editor.setValue(sqlFormatter.format(reponse_data.view_definition));
        }
        this.is_fetching_data = false;
      }).catch(error => {
        this.handleApiError(error);
      })
    },

    focusToApp() {
      document.getElementById('app').focus();
    },


    saveView() {

      let api_url_params = {'db': this.active_database};
      if (this.page_is_edit) {
        api_url_params.tablename = this.tableid;
      }

      let api_url = '';
      if (this.page_is_create) {
        api_url = this.buildApiUrl(this.endpoint_create_view, api_url_params);
      } else {
        api_url = this.buildApiUrl(this.endpoint_alter_view, api_url_params);
      }

      let vue_instance = this;

      let params = new URLSearchParams();
      params.append('view_name', this.view_name);
      let query = window.editor.getValue();
      params.append('view_definition', query);

      this.$http.post(api_url, params).then(response => {

        if (this.validateApiResponse(response) === false) return;

        let api_result = response.data.data;

        if (api_result.result == 'error') {
          vue_instance.query_result = {type: 'error', message: api_result.message};
          scroll(0, 0);
          return;
        }

        if (api_result.result == 'success' && vue_instance.page_is_create) {
          this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", {
            type: 'success',
            message: 'View created',
            query: api_result.query
          });
          this.$store.dispatch('refreshTables');
          vue_instance.$router.push({
            name: 'table',
            params: {database: this.active_database, tableid: vue_instance.view_name}
          });
        } else if (api_result.result == 'success') {
          this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", {
            type: 'success',
            message: 'View updated',
            query: api_result.query
          });
          this.$store.dispatch('refreshTables');
          vue_instance.$router.push({
            name: 'structure',
            params: {database: this.active_database, tableid: vue_instance.view_name}
          });
        }
        scroll(0, 0);
      }).catch(error => {
        this.handleApiError(error);
      })
    },

    formatQuery() {
      window.editor.setValue(sqlFormatter.format(window.editor.getValue()))
    },

  }
}
</script>

