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

        <div v-if="query_results.length > 0" v-for="(query_result, query_result_index) in query_results" :key="query_result_index" class="mb-8 mt-1">

          <div v-if="query_result.result == 'error'" class="error-box">
            {{ query_result.message }}
          </div>

          <div v-if="query_result.result == 'success'" class="success-box mb-3">
            {{ query_result.query }}
            <hr class="border-light-200 my-2">
            <div class="flex justify-between items-center">
              <div v-if="query_result.type == 'change'">
                Affected {{ query_result.affected_rows }} rows
              </div>
              <div v-else="query_result.type == 'data'">
                {{ query_result.row_count }} rows
              </div>
              <div class="text-gray-400 text-sm">
                {{ query_result.execution_time | formatSeconds }}s
              </div>
            </div>
          </div>


          <div v-if="query_result.result == 'success' && query_result.type == 'data'">

            <table cellspacing="0" class="table-data" v-if="query_result.rows.length > 0" ref="datatable"
                   @keydown.right.prevent="focusCellNext($event, 1)"
                   @keydown.left.prevent="focusCellPrevious($event, 1)"
                   @keydown.up.prevent="focusRowUp($event, 1)" @keydown.down.prevent="focusRowDown($event, 1)"
                   @keydown.shift.right.prevent="focusCellNext($event,5)"
                   @keydown.shift.left.prevent="focusCellPrevious($event,5)"
                   @keydown.shift.up.prevent="focusRowUp($event,5)" @keydown.shift.down.prevent="focusRowDown($event,5)"
                   @keyup.q="$refs['query'].focus()"
                   @keydown.esc="unfocusDatatable()">
              <thead>
              <tr>
                <th v-for="(column_meta, column_meta_index) in query_result.columns_meta" :key="column_meta_index">
                  <span>{{ column_meta.name }}</span>
                </th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(row, row_index) in query_result.rows">
                <td class="table-data-row" v-for="(cell, index) in row" @click.ctrl="toggleRowSidebar(query_result_index, row_index)"
                    :class="{ ' sticky-first-row-cell' : (index == 0)}" @click="$event.target.focus()" tabindex="1">
                  <span v-if="cell === null" class="null-value"><i>NULL</i></span>
                  <span v-else>{{ cell }}</span>
                </td>
              </tr>
              </tbody>
            </table>

            <row-sidebar :sidebarisopen="sidebarisopen" v-on:closeRowSidebar="closeRowSidebar"
                         :rowdata="sidebar_row_data" :columndata="sidebar_column_data" :from="query"
                         :columntabledata="sidebar_column_table_data"></row-sidebar>
          </div>

        </div>

      </div>
    </div>
  </div>
</template>

<script>

  import axios from 'axios';
  import RowSidebar from "@/components/RowSidebar";
  import TableKeyNavigation from '@/mixins/TableKeyNavigation.js'
  import CodeMirror from 'codemirror/lib/codemirror.js';
  import 'codemirror/lib/codemirror.css';
  import "codemirror/mode/sql/sql.js";
  import "codemirror/addon/hint/show-hint.css";
  import "codemirror/addon/hint/show-hint";
  import "codemirror/addon/hint/sql-hint";
  import sqlFormatter from "sql-formatter";
  import {number_format} from '../util'


  export default {
    name: 'query',
    props: ['historyindex'],
    data() {
      return {
        endpoint: 'query.php?db=',
        query_results: {},
        sidebarisopen: false,
        sidebar_row_data: [],
        sidebar_column_data: [],
        sidebar_column_table_data: [],
      }
    },

    components: {
      RowSidebar
    },

    mixins: [
      TableKeyNavigation
    ],

    filters: {
      formatSeconds: function(seconds) {
        return number_format(seconds, 3, '.', '');
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
          "Esc": function () {
            document.getElementById('app').focus();
          }
        },
        hintOptions: {
          tables: this.tables_with_columns
        }
      });

      if(typeof this.historyindex !== 'undefined' && this.historyindex >= 0) {
        window.editor.setValue(this.query_history[this.historyindex])
      }
    },

    computed: {
      active_database() {
        return this.$store.state.activeDatabase;
      },

      tables_with_columns() {
        return this.$store.getters["tables/tablesWithColumns"];
      },

      api_endpoint() {
        return this.$store.state.apiEndPoint;
      },

      query_history() {
        return this.$store.getters["queryhistory/queries"];
      },
    },

    methods: {

      runQuery() {
        let api_url = this.api_endpoint + this.endpoint + this.active_database;

        let vue_instance = this;

        let query         = window.editor.getValue();
        const querystring = require('querystring');
        axios.post(api_url, querystring.stringify({query: query})).then(response => {
          this.query_results = response.data;
          if(this.query_history.includes(query) === false) {
            this.$store.commit("queryhistory/ADD_QUERY", query);
          }
          this.$nextTick().then(function () {
            // todo: navigation on query results???
            // vue_instance.$refs['datatable'][0].getElementsByTagName('tbody')[0].rows[0].cells[0].focus();
          });
        }).catch(error => {
          console.log('-----error-------');
          console.log(error);
        })
      },

      closeRowSidebar() {
        this.sidebarisopen = false;
      },

      toggleRowSidebar(query_result_index, row_index) {
        // this.sidebar_row_data = this.tabledata[row_index];
        let column_num_keys = [];
        let table_num_keys  = [];
        for (var key in this.query_results[query_result_index].columns_meta) {
          column_num_keys.push(this.query_results[query_result_index].columns_meta[key].name);
          table_num_keys.push(this.query_results[query_result_index].columns_meta[key].table);
        }
          this.sidebar_row_data          = this.query_results[query_result_index].rows[row_index];
        this.sidebar_column_data       = column_num_keys;
        this.sidebar_column_table_data = table_num_keys;
        this.sidebarisopen             = true;
      },

      formatQuery() {
        window.editor.setValue(sqlFormatter.format(window.editor.getValue()))
      },

    }
  }
</script>


<style>
  .cm-s-default.CodeMirror {
    @apply bg-light-300 outline-none border border-light-300 text-gray-300 text-xl h-auto text-gray-900 ;
    /*@apply bg-light-300 outline-none border border-light-300 text-dark-600 text-xl;*/
  }

  .cm-s-default .CodeMirror-gutters {
    @apply border-light-300 border-r;
    background: hsl(213, 27%, 41%);
  }

  .cm-s-default .CodeMirror-linenumber {
    @apply text-white;
  }

  .cm-s-default div.CodeMirror-selected {
    @apply bg-light-100;
  }

  .cm-s-default .cm-bracket {
    @apply text-dark-600;
  }


  html {
    --scrollbarBG: hsla(0, 0%, 100%, 0.5);
    --thumbBG:     #90A4AE;
  }

  .CodeMirror-hscrollbar::-webkit-scrollbar {
    width: 8px;
    height: 8px;
  }

  .CodeMirror-hscrollbar {
    scrollbar-width: 8px;
    scrollbar-color: var(--thumbBG) var(--scrollbarBG);
    @apply outline-none;
  }

  .CodeMirror-hscrollbar::-webkit-scrollbar-track {
    background: var(--scrollbarBG);
  }

  .CodeMirror-hscrollbar::-webkit-scrollbar-thumb {
    background-color: var(--thumbBG);
    border-radius:    5px;
    border:           1px solid var(--scrollbarBG);
  }

</style>
