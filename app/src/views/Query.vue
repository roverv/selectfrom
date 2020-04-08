<template>
  <div class="flex justify-center">
  <div style="min-width: 900px;">
    <h1 class="text-xl mb-4">Execute query</h1>

    <form method="post" @submit.prevent="runQuery()" ref="queryform">
        <textarea ref="query" id="query"></textarea>

      <button class="btn mt-4">Run</button>

      <a @click="formatQuery()" class="btn mt-4 ml-6">Format query</a>

      <hr class="border-light-200 my-4">

      <div v-if="query_result.result == 'error'" class="bg-red-700 border border-red-800 px-3 py-2 text-white">
        {{ query_result.message }}
      </div>

      <div v-if="query_result.result == 'success'">
        {{ query_result.query }}
      </div>

      <div v-if="query_result.result == 'success' && query_result.type == 'change'">
        Affected {{ query_result.affected_rows }} rows
      </div>


      <div v-if="query_result.result == 'success' && query_result.type == 'data'">

        {{ query_result.row_count }} rows

        <table cellspacing="0" class="table-data" v-if="tabledata.length > 0" ref="datatable"
               @keydown.right.prevent="focusCellNext($event, 1)" @keydown.left.prevent="focusCellPrevious($event, 1)"
               @keydown.up.prevent="focusRowUp($event, 1)" @keydown.down.prevent="focusRowDown($event, 1)"
               @keydown.shift.right.prevent="focusCellNext($event,5)"
               @keydown.shift.left.prevent="focusCellPrevious($event,5)"
               @keydown.shift.up.prevent="focusRowUp($event,5)" @keydown.shift.down.prevent="focusRowDown($event,5)"
               @keyup.q="$refs['query'].focus()"
               @keydown.esc="unfocusDatatable()">
          <thead>
          <tr>
            <th v-for="column_meta in columns_meta">
              <span>{{ column_meta.name }}</span>
            </th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(row, row_index) in tabledata">
            <td class="table-data-row" v-for="(cell, index) in row" @dblclick="toggleRowSidebar(row_index)"
                :class="{ ' sticky-first-row-cell' : (index == 0)}" @click="$event.target.focus()" tabindex="1">
              <span v-if="cell === null" class="null-value"><i>NULL</i></span>
              <span v-else>{{ cell }}</span>
            </td>
          </tr>
          </tbody>
        </table>

        <row-sidebar :sidebarisopen="sidebarisopen" v-on:closeRowSidebar="closeRowSidebar" :rowdata="sidebar_row_data"
                     :columndata="sidebar_column_data" :columntabledata="sidebar_column_table_data"></row-sidebar>
      </div>

    </form>

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

  export default {
    name: 'query',
    data() {
      return {
        endpoint: 'http://localhost/rove/api/query.php?db=',
        query_result: {},
        tabledata: [],
        columns_meta: [],
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
          "Esc": function() {
            document.getElementById('app').focus();
          }
        },
        hintOptions: {
          // todo: fill hints
          tables: {
            users: ["name", "score", "birthDate"],
            countries: ["name", "population", "size"]
          }
        }
      });
    },

    computed: {
      columns() {
        if (this.tabledata.length > 0) {
          return Object.keys(this.tabledata[0]);
        }
        return [];
      },

      active_database() {
        return this.$store.state.activeDatabase;
      }
    },

    methods: {

      runQuery() {
        let api_url = this.endpoint + this.active_database;

        let vue_instance = this;

        let query = window.editor.getValue();
        const querystring = require('querystring');
        axios.post(api_url, querystring.stringify({query: query})).then(response => {
          this.query_result = response.data;
          this.tabledata    = this.query_result.rows;
          this.columns_meta = this.query_result.columns_meta;
          this.$nextTick().then(function () {
            vue_instance.$refs['datatable'].getElementsByTagName('tbody')[0].rows[0].cells[0].focus();
          });
        }).catch(error => {
          console.log('-----error-------');
          console.log(error);
        })
      },

      closeRowSidebar() {
        this.sidebarisopen = false;
      },

      toggleRowSidebar(row_index) {
        // this.sidebar_row_data = this.tabledata[row_index];
        let column_num_keys = [];
        let table_num_keys  = [];
        for (var key in this.columns_meta) {
          column_num_keys.push(this.columns_meta[key].name);
          table_num_keys.push(this.columns_meta[key].table);
        }
        this.sidebar_row_data          = this.tabledata[row_index];
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
    @apply bg-light-300 outline-none border border-light-300 text-gray-300 text-xl h-auto;
    /*@apply bg-light-300 outline-none border border-light-300 text-dark-600 text-xl;*/
  }

  .cm-s-default .CodeMirror-gutters {
    @apply bg-transparent border-light-300 border-r;
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

</style>

