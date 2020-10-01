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
          <table cellspacing="0" class="table-data" v-if="columns.length > 0">
            <thead>
            <tr>
              <th v-for="(column_header) in Object.keys(columns[0])">{{ column_header }}</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="column_fields in columns">
              <td class="py-1" v-for="column_field in column_fields">{{ column_field }}</td>
            </tr>
            </tbody>
          </table>

          <router-link :to="{ name: 'edittable', params: { database: active_database, tableid: tableid } }"
                       class="btn mt-4">Edit table
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

      let api_url_params = {'db': this.active_database, 'tablename': this.tableid};
      let api_url        = this.buildApiUrl('table/structure', api_url_params);

      this.$http.get(api_url).then(response => {
        this.columns = response.data.data;

      }).catch(error => {
        this.handleApiError(error);
      })
    },
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

/* highlight column name */
tbody td:first-child {
  @apply text-white font-medium;
}

</style>
