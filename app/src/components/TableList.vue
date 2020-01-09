<template>
  <div class="sidebar flex-shrink-0 pr-1 relative" :class="[tableListIsOpen ? 'w-56 mr-4 ml-8 my-6' : 'w-8']">

    <div class="absolute top-0" :class="[tableListIsOpen ? 'right-0 mr-2' : 'left-0 ml-1']">
      <TableListSettingDropdown :isOpen="false" v-on:toggleTablesWithoutRows="toggleTablesWithoutRows()"
                                v-on:toggleTableList="toggleTableList()"></TableListSettingDropdown>
    </div>

    <div v-if="tableListIsOpen">

      <div class="hidden">
        <h2 class="mb-2 text-gray-700 text-xl">Favorites</h2>
        <ul class="text-gray-800 mb-6">
          <li>
            <a>organisation</a>
          </li>
          <li>
            <a>site</a>
          </li>
          <li>
            <a>site_host</a>generatepdf
          </li>
          <li>
            <a>user</a>
          </li>
        </ul>

      </div>

      <h2 class="mb-2 text-gray-700 text-xl">
        <span class="text-gray-500 text-lg">{{ tables_filtered.length }}</span>
        Tables
      </h2>

      <input type="search" name="filter_table" class="border border-gray-300 mb-4 py-1 px-2"
             placeholder="Filter tables">
      <div class="">
        <ul class="text-gray-800">
          <li v-for="(table_data) in tables_filtered" v-if="!only_show_tables_with_rows || table_data.Rows > 0"
              :class="{active: table_data.Name == $route.params.tableid}">
            <router-link :to="{ name: 'table', params: { tableid: table_data.Name } }">
              {{ table_data.Name }}
              <span class="text-gray-500 hidden">
              ({{ table_data.Rows }})
            </span>
            </router-link>
          </li>
        </ul>
      </div>

    </div>

  </div>
</template>

<script>

  import axios from 'axios'
  import TableListSettingDropdown from '@/components/TableListSettingDropdown.vue'

  export default {
    name: 'TableList',
    props: ['active_database'],

    data() {
      return {
        tables_all: null,
        tableListIsOpen: true,
        tables_filtered: [],
        only_show_tables_with_rows: false,
        endpoint: 'http://localhost/rove/api/tables.php?db=',
      }
    },

    components: {
      TableListSettingDropdown
    },

    watch: {
      active_database: function (value) {
        if (value) {
          this.getAllTables();
        }
      }
    },

    methods: {
      getAllTables() {
        axios.get(this.endpoint + this.active_database).then(response => {
          this.tables_all      = response.data;
          this.tables_filtered = response.data;

          let tables_names = [];
          this.tables_all.forEach(function (table_data) {
            tables_names.push(table_data.Name);
          });
          localStorage.setItem('tables', JSON.stringify(tables_names));
        }).catch(error => {
          console.log('-----error-------');
          console.log(error);
        })
      },

      toggleTablesWithoutRows: function () {
        this.only_show_tables_with_rows = !this.only_show_tables_with_rows;
      },

      toggleTableList: function () {
        this.tableListIsOpen = !this.tableListIsOpen;
      }
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

  li.active {
    @apply border-l-2 border-orange-400 bg-orange-100 pl-2;
  }

</style>
