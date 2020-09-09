<template>
  <div class="page-content-container">
    <div>

      <div class="table-page-header">
        <h2>
          {{ tableid }}
        </h2>
        <table-nav :tableid="tableid"></table-nav>
        <div></div>
      </div>

      <div class="w-full flex items-start">

        <div class="relative w-full">
          <table cellspacing="0" class="flex-grow  bg-light-100 relative"
                 style="box-shadow: 0 2px 3px 2px rgba(0,0,0,.03);"
                 v-if="columns.length > 1">
            <thead class="bg-dark-400 text-gray-200">
            <tr class="font-normal">
              <th class="sticky top-0 z-20 bg-dark-400 text-gray-200 px-2 py-3"
                  v-for="(column_header) in Object.keys(columns[0])">{{ column_header }}
              </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="column_fields in columns">
              <td class="whitespace-pre px-2 py-1" v-for="column_field in column_fields">{{ column_field }}</td>
            </tr>
            </tbody>
          </table>

          <router-link :to="{ name: 'edittable', params: { database: active_database, tableid: tableid } }" class="btn mt-4">Edit table
          </router-link>

        </div>

      </div>

    </div>
  </div>
</template>

<script>

import TableNav from '@/components/TableNav.vue'
import HandleApiError from '@/mixins/HandleApiError.js'
import ApiUrl from "@/mixins/ApiUrl";

export default {
  name: 'TableStructure',
  props: ['tableid'],
  data() {
    return {
      columns: [],
      endpoint: 'table_structure.php',
    }
  },

  components: {
    TableNav
  },

  mixins: [
    HandleApiError,
    ApiUrl
  ],

  mounted() {
    this.getTableStructure();
  },

  computed: {
    active_database() {
      return this.$store.state.activeDatabase;
    },
  },

  watch: {
    // force update on route change
    '$route.params': function () {
      this.$nextTick();
    }
  },

  filters: {
    lowercase: function (string) {
      return string.toLowerCase();
    }
  },

  methods: {
    getTableStructure() {

      let api_url_params = {'db': this.active_database, 'tablename' : this.tableid};
      let api_url        = this.buildApiUrl(this.endpoint, api_url_params);

      axios.get(api_url).then(response => {
        this.columns = response.data;

      }).catch(error => {
        this.handleApiError(error);
      })
    },
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

table tbody td {
  @apply text-gray-300 border-b border-light-300;
}

tbody tr:hover td {
  @apply bg-light-200;
}

tbody td:hover {
  @apply bg-highlight-400 !important;
}

tbody td:first-child {
  @apply text-white;
}

</style>
