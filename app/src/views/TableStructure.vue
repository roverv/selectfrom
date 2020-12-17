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

      <flash-message></flash-message>

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

          <router-link v-if="!is_view" :to="{ name: 'edittable', params: { database: active_database, tableid: tableid } }"
                       class="btn mt-4">Edit table
          </router-link>

        </div>

      </div>

    </div>
  </div>
</template>

<script>

import TableNav from '@/components/TableNav.vue'
import ApiMixin from "@/mixins/Api";
import FlashMessage from "@/components/FlashMessage";

export default {
  name: 'TableStructure',
  props: ['tableid'],
  data() {
    return {
      columns: [],
    }
  },

  components: {
    FlashMessage,
    TableNav
  },

  mixins: [
    ApiMixin
  ],

  created() {
    if(this.is_view === false) {
      this.$emit('setcontextoptions', [
        {
          'shortkey': '1',
          'label': 'Edit table',
          'action': 'editTable'
        }]);
    }
  },

  mounted() {
    this.getTableStructure();
  },

  computed: {
    active_database() {
      return this.$store.state.activeDatabase;
    },
    tables() {
      return this.$store.getters["tables/tables"];
    },
    is_view() {
      let current_table_data = this.tables.find(table_data => table_data.name == this.tableid);
      if (current_table_data.type == 'VIEW') return true;
      return false;
    }
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

    editTable() {
      this.$router.push({ name: 'edittable', params: { database: this.active_database, tableid: this.tableid } });
    },

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
