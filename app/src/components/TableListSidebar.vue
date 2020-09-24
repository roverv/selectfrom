<template>
  <div class="sidebar pr-1" :class="[tables_list_is_open ? 'open ' : 'closed']">

    <div class="sidebar-fixed relative">

      <div class="flex justify-between items-center">
        <div  class="flex items-center mb-2">

          <h2 class=" text-xl mr-3" v-if="tables_list_is_open">
            <span class="text-light-300 text-lg">{{ tables_filtered.length }}</span>
            Tables
          </h2>

          <TableListSettingDropdown class="mt-1" :isOpen="false" :class="[tables_list_is_open ? '' : 'ml-2']"
                                    :only_show_tables_with_rows="only_show_tables_with_rows"
                                    :tables_list_is_open="tables_list_is_open"
                                    v-on:toggleTablesWithoutRows="toggleTablesWithoutRows()"
                                    v-on:toggleTableList="toggleTableList()"></TableListSettingDropdown>

        </div>
      </div>
    </div>

    <div v-if="tables_list_is_open" class="sidebar-scrollable table-list scroll-bar">
        <ul class="pr-2">
          <li v-for="(table_data) in tables_filtered"
              :class="{active: table_data.Name == $route.params.tableid}">
            <router-link :to="{ name: 'table', params: { database: active_database, tableid: table_data.Name } }">
              {{ table_data.Name }}
              <span class="text-gray-500 hidden">
              ({{ table_data.Rows }})
            </span>
            </router-link>
          </li>
        </ul>

    </div>

  </div>
</template>

<script>

  import TableListSettingDropdown from '@/components/TableListSettingDropdown.vue'

  export default {
    name: 'TableListSidebar',

    data() {
      return {
        tables_list_is_open: true,
        only_show_tables_with_rows: false,
      }
    },

    components: {
      TableListSettingDropdown
    },

    mounted() {
      if (localStorage.getItem('only_show_tables_with_rows')) {
        this.only_show_tables_with_rows = (localStorage.getItem('only_show_tables_with_rows') === 'true');
      }
      if (localStorage.getItem('tables_list_is_open')) {
        this.tables_list_is_open = (localStorage.getItem('tables_list_is_open') === 'true');
      }
    },

    computed: {
      tables_filtered() {
        if (!this.tables_all) return [];
        var vue = this;
        return this.tables_all.filter(function (table_data) {
          return !vue.only_show_tables_with_rows || table_data.Rows > 0;
        })
      },

      active_database() {
        return this.$store.state.activeDatabase;
      },

      tables_all() {
        return this.$store.getters["tables/tables"];
      },
    },

    methods: {

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

  li {
    @apply px-2;
  }

  li.active {
    @apply border-l-2 border-highlight-700 bg-light-100;
  }

  html {
    --scrollbarBG: transparent;
    --thumbBG:     #90A4AE;
  }

  .sidebar {
    display:             grid;
    height:              100%;
    grid-template-areas: 'fixed-part' 'dynamic-part';
    grid-gap:            0px;
    grid-row-gap:        0;
    grid-template-rows:  auto minmax(0, 1fr);
  }
  .sidebar.open {
    @apply mr-4 ml-5;
    min-width: 200px;
  }
  .sidebar.closed {
    @apply w-12;
  }

  .sidebar-fixed {
    grid-area: fixed-part;
  }

  .sidebar-scrollable {
    grid-area:  dynamic-part;
    overflow-y: auto;
  }

</style>
