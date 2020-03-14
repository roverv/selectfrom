<template>
  <div>
    <h1 class="text-xl mb-4">Execute query</h1>

    <form method="post" @submit.prevent="runQuery()" ref="queryform">
      <textarea v-model="query" class="w-full h-64 bg-light-200 p-3 outline-none border border-light-300" ref="query"
                v-on:keydown.esc="unfocusElement($event)" v-on:keydown.ctrl.enter="runQuery()"></textarea>

      <button class="btn mt-2">Run</button>

      <hr class="border-light-200 my-4">

      <div v-if="query_result.result == 'error'" class="bg-red-700 border border-red-800 px-3 py-2 text-white">
        {{ query_result.message }}
      </div>


      <div v-if="query_result.result == 'success'">
        {{ query_result.affected_rows }} rows
      </div>


      <div v-if="query_result.result == 'success'">

        <table cellspacing="0" class="table-data" v-if="tabledata.length > 0">
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
                :class="{ ' sticky-first-row-cell' : (index == 0)}">
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
</template>

<script>

  import axios from 'axios';
  import RowSidebar from "@/components/RowSidebar";

  export default {
    name: 'query',
    props: ['active_database'],
    data() {
      return {
        endpoint: 'http://localhost/rove/api/query.php?db=',
        query: '',
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

    mounted() {
      this.$refs.query.focus();
    },

    computed: {
      columns() {
        if (this.tabledata.length > 0) {
          return Object.keys(this.tabledata[0]);
        }
        return [];
      }
    },

    methods: {

      runQuery() {
        let api_url = this.endpoint + this.active_database;

        let vue_instance = this;

        const querystring = require('querystring');
        axios.post(api_url, querystring.stringify({query: this.query})).then(response => {
          this.query_result = response.data;
          this.tabledata    = this.query_result.rows;
          this.columns_meta = this.query_result.columns_meta;
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

      unfocusElement($event) {
        $event.target.blur();
        document.getElementById('app').focus();
      },

    }
  }
</script>


