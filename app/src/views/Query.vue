<template>
  <div>
    <h1 class="text-xl mb-4">Execute query</h1>

    <form method="post" @submit.prevent="runQuery()" ref="queryform">
      <textarea v-model="query" class="w-full h-64 bg-light-200 p-3 outline-none border border-light-300"></textarea>

      <button>Run</button>

      <hr class="border-light-200 my-4">

      <div v-if="query_result.result == 'error'" class="bg-red-700 border border-red-800 px-3 py-2 text-white">
        {{ query_result.message }}
      </div>


      <div v-if="query_result.result == 'success'">
        {{ query_result.affected_rows }} rows
      </div>


      <div v-if="query_result.result == 'success'">
        <pre>
          {{ query_result.data }}
        </pre>
      </div>

    </form>

  </div>
</template>

<script>

  import axios from 'axios'

  export default {
    name: 'query',
    props: ['active_database'],
    data() {
      return {
        endpoint: 'http://localhost/rove/api/query.php?db=',
        query: '',
        query_result: {}
      }
    },

    methods: {

      runQuery() {
        let api_url = this.endpoint + this.active_database;

        let vue_instance = this;

        const querystring = require('querystring');
        axios.post(api_url, querystring.stringify({ query: this.query }) ).then(response => {
          this.query_result = response.data;
        }).catch(error => {
          console.log('-----error-------');
          console.log(error);
        })
      },

    }
  }
</script>

<style>

</style>

