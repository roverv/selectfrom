<template>
  <div>
    <h1 class="text-xl mb-4">Execute query</h1>

    <form method="post" @submit.prevent="runQuery()" ref="queryform">
      <textarea v-model="query" class="w-full h-64 bg-light-200 p-3 outline-none border border-light-300" ref="query" v-on:keydown.ctrl.enter="runQuery()"></textarea>

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
            <th v-for="column_header in columns">
                <span>{{ column_header }}</span>
            </th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(row, row_index) in tabledata">
            <td class="table-data-row" v-for="(column_name, index) in columns" @dblclick="toggleRowSidebar(row_index)"
                :class="{ ' sticky-first-row-cell' : (index == 0)}">
              <span v-if="row[column_name] === null" class="null-value"><i>NULL</i></span>
              <span v-else>{{ row[column_name] }}</span>
            </td>
          </tr>
          </tbody>
        </table>

        <row-sidebar :sidebarisopen="sidebarisopen" v-on:closeRowSidebar="closeRowSidebar" :rowdata="sidebar_row_data"></row-sidebar>
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
        sidebarisopen: false,
        sidebar_row_data: {},
      }
    },

    components: {
      RowSidebar
    },

    created() {
      document.addEventListener('keydown', this.triggerKeyDown);
    },

    mounted() {
      this.$refs.query.focus();
    },

    computed: {
      columns() {
        if(this.tabledata.length > 0) {
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
        axios.post(api_url, querystring.stringify({ query: this.query }) ).then(response => {
          this.query_result = response.data;
          this.tabledata = this.query_result.data;
        }).catch(error => {
          console.log('-----error-------');
          console.log(error);
        })
      },

      closeRowSidebar() {
        this.sidebarisopen = false;
      },

      toggleRowSidebar(row_index) {
        this.sidebar_row_data = this.tabledata[row_index];
        this.sidebarisopen = true;
      }

    }
  }
</script>


