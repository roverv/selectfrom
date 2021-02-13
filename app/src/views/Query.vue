<template>
  <div class="page-content-container">
    <div class="grid-container-content">
      <div class="content-header content-min-width">
        <h1 class="text-xl mb-4">Execute query</h1>

        <form method="post" @submit.prevent="runQuery()" ref="queryform">
          <textarea ref="query" id="query"></textarea>

          <button class="btn mt-4">Run</button>

          <a @click="formatQuery()" class="btn mt-4 ml-6">Format query</a>

          <hr class="border-light-200 my-4">

        </form>
      </div>

      <div class="content-body">

        <spinner v-if="fetching_query_results"></spinner>

        <div v-if="query_results.length > 1 && query_result_summary_success > 0" class="success-box mb-3">
          {{ query_result_summary_success }} successful executed
          <span v-if="query_result_summary_success == 1">query</span>
          <span v-else>queries</span>
        </div>

        <div v-if="query_results.length > 1 && query_result_summary_error > 0" class="error-box mb-3">
          {{ query_result_summary_error }} executed
          <span v-if="query_result_summary_error == 1">query</span>
          <span v-else>queries</span>
          returned an error
        </div>

        <div v-if="query_results.length > 1">
          <h2 class="text-xl font-semibold mt-4">Results</h2>
        </div>

        <div v-if="query_results.length > 0">
          <div v-for="(query_result, query_result_index) in query_results" :key="query_result_index" class="mb-8 mt-1">

            <div v-if="query_result.result == 'error'" class="error-box">
              {{ query_result.message }}
              <hr class="border-light-200 my-2">
              {{ query_result.query }}
            </div>

            <div v-if="query_result.result == 'success'" class="success-box mb-3">
              {{ query_result.query }}
              <hr class="border-light-200 my-2">
              <div class="flex justify-between items-center">
                <div v-if="query_result.type == 'change'">
                  Affected {{ query_result.affected_rows }} rows
                </div>
                <div v-else-if="query_result.type == 'data'">
                  {{ query_result.row_count }} rows
                </div>
                <div class="text-gray-400 text-sm">
                  {{ query_result.execution_time | formatSeconds }}s
                </div>
              </div>
            </div>


            <div v-if="query_result.result == 'success' && query_result.type == 'data'">

              <table cellspacing="0" class="table-data" v-if="query_result.rows.length > 0" ref="datatable"
                     @keydown.right.exact.prevent="focusCellNext($event, 1)"
                     @keydown.left.exact.prevent="focusCellPrevious($event, 1)"
                     @keydown.up.exact.prevent="focusRowUp($event, 1)" @keydown.down.exact.prevent="focusRowDown($event, 1)"
                     @keydown.shift.right.prevent="focusCellNext($event,5)"
                     @keydown.shift.left.prevent="focusCellPrevious($event,5)"
                     @keydown.shift.up.prevent="focusRowUp($event,5)"
                     @keydown.shift.down.prevent="focusRowDown($event,5)"
                     @keyup.q.exact="$refs['query'].focus()"
                     @keydown.esc.exact="unfocusDatatable()">
                <thead>
                <tr>
                  <th v-for="(column_meta, column_meta_index) in query_result.columns_meta" :key="column_meta_index">
                    <span>{{ column_meta.name }}</span>
                  </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(row, row_index) in query_result.rows" :key="row_index" @click.ctrl="toggleRowSidebar(query_result_index, row_index)">
                  <td class="table-data-row" v-for="(cell, index) in row" :key="index"
                      :class="{ ' sticky-first-row-cell' : (index == 0)}" @click.exact="$event.target.focus()" tabindex="1">
                    <span v-if="cell === null" class="null-value"><i>NULL</i></span>
                    <router-link v-else-if="query_results_primary_key_columns[query_result_index][index] !== false" class="link"
                                 :to="{ name: 'tablewithcolumnvalue', params: {database: active_database, tableid: query_results_primary_key_columns[query_result_index][index], column: 'primarykey', comparetype: 'is', value: cell}}">{{ cell }}</router-link>
                    <span v-else-if="shouldTruncateField(query_result.columns_meta[index].native_type)" :title="cell" @click="swapTruncatedText($event)">{{ cell | truncate(cell_text_display_limit) }}</span>
                    <span v-else>{{ cell }}</span>
                  </td>
                </tr>
                </tbody>
              </table>

              <row-sidebar :sidebarisopen="sidebarisopen" v-on:closeRowSidebar="closeRowSidebar"
                           :rowdata="sidebar_row_data" :columndata="sidebar_column_data" from="query"
                           :columntabledata="sidebar_column_table_data"></row-sidebar>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>

  import RowSidebar from "@/components/RowSidebar";
  import CodeMirror from 'codemirror/lib/codemirror.js';
  import 'codemirror/lib/codemirror.css';
  import "codemirror/mode/sql/sql.js";
  import "codemirror/addon/hint/show-hint.css";
  import "codemirror/addon/hint/show-hint";
  import "codemirror/addon/hint/sql-hint";
  import sqlFormatter from "sql-formatter";
  import {number_format} from '../util';
  import Spinner from "@/components/Spinner";
  import TableDataRow from "@/components/TableDataRow";
  import ApiMixin from "@/mixins/Api";


  export default {
    name: 'query',
    props: ['historyindex'],
    data() {
      return {
        endpoint: '/query',
        query_results: {},
        sidebarisopen: false,
        sidebar_row_data: [],
        sidebar_column_data: [],
        sidebar_column_table_data: [],
        fetching_query_results: false,
      }
    },

    components: {
      RowSidebar,
      Spinner,
      TableDataRow,
    },

    mixins: [
      ApiMixin
    ],

    filters: {
      formatSeconds: function (seconds) {
        return number_format(seconds, 3, '.', '');
      },
      truncate: function (value, limit) {
        if (!value) return value;
        if (value.length > limit) {
          value = value.replace(/(\r\n|\n|\r)/gm, ""); // remove line breaks
          value = value.substring(0, limit) + '...';
        }

        return value
      }
    },

    mounted() {
      this.$refs.query.focus();

      let vue_instance = this;

      window.editor = CodeMirror.fromTextArea(document.getElementById('query'), {
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
            vue_instance.runQuery();
          },
          'Shift-Enter': function () {
            vue_instance.formatQuery();
          },
          "Esc": function () {
            document.getElementById('app').focus();
          }
        },
        hintOptions: {
          tables: this.tables_with_column_names
        }
      });

      if (typeof this.historyindex !== 'undefined' && this.historyindex >= 0) {
        window.editor.setValue(this.query_history[this.historyindex])
      }

      if(this.$store.getters["queryedit/isset"]) {
        window.editor.setValue(this.$store.getters["queryedit/query"]);
        if(this.$store.getters["queryedit/execute"] === true) {
          this.runQuery();
        }
        this.$store.commit('queryedit/empty');
      }
    },

    computed: {
      active_database() {
        return this.$store.state.activeDatabase;
      },

      tables_with_column_names() {
        return this.$store.getters["tables/tablesWithColumnNames"];
      },

      query_history() {
        return this.$store.getters["queryhistory/queries"];
      },

      last_key() {
        return this.$store.getters["queryhistory/last_key"];
      },

      cell_text_display_limit() {
        return this.$store.getters["settings/getSetting"]('cell_text_display_limit');
      },

      query_result_summary_success() {
        if(this.query_results.length < 1) return;

        return this.query_results.reduce(function(accumulator, query_result) {
          if(query_result.result === 'success') {
            accumulator += 1;
          }
          return accumulator;
        }, 0);
      },

      query_result_summary_error() {
        if(this.query_results.length < 1) return;

        return this.query_results.reduce(function(accumulator, query_result) {
          if(query_result.result === 'error') {
            accumulator += 1;
          }
          return accumulator;
        }, 0);
      },

      query_results_primary_key_columns() {
        // loop through all the query results
        return this.query_results.map(function (query_result, result_index) {
          // loop through all the columns and return the table name if the column is a primary key
          return query_result.columns_meta.map(function (column_data, column_index) {
            if(column_data.flags.find(flag => flag === 'primary_key')) {
              return column_data.table;
            }
            return false;
          });
        });
      },

    },

    methods: {

      runQuery() {
        this.fetching_query_results = true;

        let api_url_params = {'db': this.active_database};
        let api_url        = this.buildApiUrl(this.endpoint, api_url_params);

        let query         = window.editor.getValue();
        const querystring = require('querystring');
        this.$http.post(api_url, querystring.stringify({query: query})).then(response => {
          this.query_results = Object.freeze(response.data.data.query_results);

          // only push single query executions to history
          if(this.query_results.length == 1) {
            if (this.query_history.includes(query) === false) {
              this.$store.commit("queryhistory/ADD_QUERY", query);
            }

            // generate a path the the new query history entry
            let props = this.$router.resolve({
              name: 'queryhistory',
              params: {'database': this.active_database, 'historyindex': this.last_key},
            });

            // push query history url on the history, we can't do this with Vue router because it would reload the page
            // this make it possible to navigate back to the previous query
            history.pushState(
                {},
                null,
                props.href
            );
          }

          if(response.data.data.refresh_cache === true) {
            // a query has changed the cached data, refresh the cache
            this.$store.dispatch('refreshTables');
          }
          this.fetching_query_results = false;
        }).catch(error => {
          this.handleApiError(error);
          this.fetching_query_results = false;
        })
      },

      closeRowSidebar() {
        this.sidebarisopen = false;
      },

      toggleRowSidebar(query_result_index, row_index) {
        if(this.sidebarisopen === true) {
          this.sidebar_row_data.push(this.query_results[query_result_index].rows[row_index]);
          return;
        }

        let column_num_keys = [];
        let table_num_keys  = [];
        for (var key in this.query_results[query_result_index].columns_meta) {
          column_num_keys.push(this.query_results[query_result_index].columns_meta[key].name);
          table_num_keys.push(this.query_results[query_result_index].columns_meta[key].table);
        }
        this.sidebar_row_data          = [this.query_results[query_result_index].rows[row_index]];
        this.sidebar_column_data       = column_num_keys;
        this.sidebar_column_table_data = table_num_keys;
        this.sidebarisopen             = true;
      },

      formatQuery() {
        window.editor.setValue(sqlFormatter.format(window.editor.getValue()))
      },

      shouldTruncateField(column_type) {
        if(this.cell_text_display_limit === 0) return false;
        if (column_type === 'BLOB' || column_type === 'VAR_STRING' || column_type === 'STRING') {
          return true;
        }
        return false;
      },

      swapTruncatedText($event) {
        $event.target.innerHTML = $event.target.getAttribute('title');
      },

    }
  }
</script>

