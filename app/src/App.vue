<template>
  <div id="app" class="grid-container-app" v-on:keyup.self.open-search="openSearchModal" v-on:keyup.self.open-recent-tables="openRecentTables"
       v-on:keyup.self.to-query="goToQuery" v-on:keyup.self.open-database-list="openDatabasesModal"
       v-on:keyup.self.open-query-history="openQueryHistory"
       v-on:keyup.self.refresh-page="refreshPage" tabindex="0">

    <SearchModal v-if="active_database && searchmodalopen" :modalisopen="searchmodalopen"
                 v-on:closesearchmodal="closeSearchModal()" />

    <DatabasesModal v-if="databasemodalopen" :modalisopen="databasemodalopen"
                    v-on:closedatabasesmodal="closeDatabasesModal()" />

    <RecentTables v-if="active_database && recenttablesopen" :modalisopen="recenttablesopen"
                  v-on:closerecenttables="closeRecentTables()" tabindex="0"
                  @keydown.esc="recenttablesopen = false" />

    <QueryHistory v-if="active_database && queryhistoryopen" :modalisopen="queryhistoryopen"
                  v-on:closequeryhistory="closeQueryHistory()" tabindex="0" @keydown.esc="queryhistoryopen = false" />

    <div class="app-header">
      <ApiError :key="$route.fullPath + $store.state.reloadMainComponentKey"></ApiError>
    </div>

    <div class="app-sidebar-navigation h-screen">
      <MainNavigation v-on:SwitchTheme="SwitchTheme"></MainNavigation>
    </div>

    <div class="app-sidebar-tables py-5 h-screen">
      <TableListSidebar v-if="show_table_list_sidebar" />
    </div>

    <div class="app-body">
      <div class="py-5 pb-5 mr-5">
        <router-view :key="$route.fullPath + $store.state.reloadMainComponentKey" />
      </div>
    </div>

  </div>
</template>

<style>
  #app {
    @apply outline-none;
  }
</style>

<script>
  import SearchModal from "./components/SearchModal";
  import DatabasesModal from "./components/DatabasesModal";
  import TableListSidebar from "./components/TableListSidebar";
  import RecentTables from "./components/RecentTables";
  import QueryHistory from "./components/QueryHistory";
  import MainNavigation from "./components/MainNavigation";
  import ApiError from "./components/ApiError";

  // use this to reload the main component
  // this.$store.state.reloadMainComponentKey += 1;

  export default {

    data() {
      return {
        searchmodalopen: false,
        databasemodalopen: false,
        recenttablesopen: false,
        queryhistoryopen: false,
        tables: [],
      }
    },

    created() {
      this.$store.dispatch("databases/get");
    },

    watch: {
      $route(to, from) {

        if(this.$route.params.hasOwnProperty('database')) {
          if(this.$store.state.activeDatabase == '' || this.$store.state.activeDatabase != this.$route.params.database) {
            this.$store.commit("setActiveDatabase", this.$route.params.database);
          }
        }

        this.searchmodalopen   = false;
        this.recenttablesopen  = false;
        this.databasemodalopen = false;
        this.queryhistoryopen  = false;
        this.$nextTick();
        document.getElementById('app').focus();
      }
    },

    components: {
      ApiError,
      MainNavigation,
      TableListSidebar,
      RecentTables,
      SearchModal,
      DatabasesModal,
      QueryHistory,
    },

    computed: {
      active_database() {
        return this.$store.state.activeDatabase;
      },

      show_table_list_sidebar() {
        return !(this.active_database == '' || this.$route.name == 'server');
      },
    },

    methods: {

      openSearchModal() {
        if (this.searchmodalopen || this.recenttablesopen || this.databasemodalopen || this.queryhistoryopen) return;
        this.searchmodalopen = true;
      },

      openDatabasesModal() {
        if (this.searchmodalopen || this.recenttablesopen || this.databasemodalopen || this.queryhistoryopen) return;
        this.databasemodalopen = true;
      },

      openRecentTables() {
        if (this.searchmodalopen || this.recenttablesopen || this.databasemodalopen || this.queryhistoryopen) return;
        this.recenttablesopen = true;
      },

      openQueryHistory() {
        if (this.searchmodalopen || this.recenttablesopen || this.databasemodalopen || this.queryhistoryopen) return;
        this.queryhistoryopen = true;
      },

      closeSearchModal() {
        this.searchmodalopen = false;
        // when the modal is closed, we need to set the focus back on the app
        document.getElementById('app').focus();
      },

      closeDatabasesModal() {
        this.databasemodalopen = false;
        // when the modal is closed, we need to set the focus back on the app
        document.getElementById('app').focus();
      },

      closeRecentTables() {
        this.recenttablesopen = false;
        // when the modal is closed, we need to set the focus back on the app
        document.getElementById('app').focus();
      },

      closeQueryHistory() {
        this.queryhistoryopen = false;
        // when the modal is closed, we need to set the focus back on the app
        document.getElementById('app').focus();
      },

      forceupdate() {
        this.$forceUpdate();
      },

      SwitchTheme(theme_name) {
        document.documentElement.removeAttribute("class");
        document.documentElement.classList.add("theme-" + theme_name);
      },

      goToQuery() {
        this.$router.push({name: 'query'});
      },

      refreshPage() {
        this.$store.state.reloadMainComponentKey += 1;
      },

    }
  }

</script>
