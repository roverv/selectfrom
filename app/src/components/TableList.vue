<template>
  <div class="sidebar flex-shrink-0 pr-1 relative" :class="[tables_list_is_open ? 'w-56 mr-4 ml-8 my-6' : 'w-8']">

    <div class="absolute top-0" :class="[tables_list_is_open ? 'right-0 mr-2' : 'left-0 ml-1']">
      <TableListSettingDropdown :isOpen="false" :only_show_tables_with_rows="only_show_tables_with_rows"
                                :tables_list_is_open="tables_list_is_open"
                                v-on:toggleTablesWithoutRows="toggleTablesWithoutRows()"
                                v-on:toggleTableList="toggleTableList()"></TableListSettingDropdown>
    </div>

    <div v-if="tables_list_is_open">

      <h2 class="mb-2 text-xl">
        <span class="text-gray-500 text-lg">{{ tables_filtered.length }}</span>
        Tables
      </h2>

      <input type="search" name="filter_table" class="bg-light-100 border border-light-200 mb-4 py-1 px-2"
             placeholder="Filter tables">
      <div class="">
        <ul class="">
          <li v-for="(table_data) in tables_filtered"
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
        tables_list_is_open: true,
        only_show_tables_with_rows: false,
        endpoint: 'http://localhost/rove/api/tables.php?db=',
      }
    },

    components: {
      TableListSettingDropdown
    },

    created() {
      if(this.active_database) this.getAllTables();
    },

    mounted() {
      if(localStorage.getItem('only_show_tables_with_rows')) {
        this.only_show_tables_with_rows = (localStorage.getItem('only_show_tables_with_rows') === 'true');
      }
      if(localStorage.getItem('tables_list_is_open')) {
        this.tables_list_is_open = (localStorage.getItem('tables_list_is_open') === 'true');
      }
    },

    watch: {
      active_database: function (value) {
        if (value) {
          this.getAllTables();
        }
      }
    },

    computed : {
      tables_filtered() {
        if(!this.tables_all) return [];
        var vue = this;
        return this.tables_all.filter(function(table_data) {
          return !vue.only_show_tables_with_rows || table_data.Rows > 0;
        })
      }
    },

    methods: {
      getAllTables() {
        axios.get(this.endpoint + this.active_database).then(response => {
          this.tables_all      = response.data;

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
        localStorage.setItem('only_show_tables_with_rows', this.only_show_tables_with_rows)
      },

      toggleTableList: function () {
        this.tables_list_is_open = !this.tables_list_is_open;
        localStorage.setItem('tables_list_is_open', this.tables_list_is_open)
      }
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

  li.active {
    @apply border-l-2 border-highlight-700 bg-light-100 pl-2;
  }

</style>
