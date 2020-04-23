s<template>
  <div id="app" v-on:keyup.self.open-search="openSearchModal" v-on:keyup.self.open-recent-tables="openRecentTables"
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

    <header class="bg-gray-500 py-3 px-10 mb-3 bg-light-100 absolute w-full top-0 z-20">
      <div class="flex justify-between items-center">

        <div class="flex items-center">
          <a class="text-gray-300">MySQL</a>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 mt-1 mx-1 fill-current text-gray-400"
               style="transform: rotate(90deg);">
            <path
              d="M8.7 13.7a1 1 0 1 1-1.4-1.4l4-4a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1-1.4 1.4L12 10.42l-3.3 3.3z"></path>
          </svg>
          <router-link :to="{ name: 'server'}" class="text-gray-300">
            Server
          </router-link>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 mt-1 mx-1 fill-current text-gray-400"
               style="transform: rotate(90deg);">
            <path
              d="M8.7 13.7a1 1 0 1 1-1.4-1.4l4-4a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1-1.4 1.4L12 10.42l-3.3 3.3z"></path>
          </svg>


          <router-link :to="{ name: 'database', params: {database: active_database }}" class="mx-2" v-if="active_database">
            {{ active_database }}
          </router-link>

          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 mt-1 mx-1 fill-current text-gray-400"
               style="transform: rotate(90deg);" v-if="page_is_table">
            <path
              d="M8.7 13.7a1 1 0 1 1-1.4-1.4l4-4a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1-1.4 1.4L12 10.42l-3.3 3.3z"></path>
          </svg>
          <router-link :to="{ name: 'table', params: {tableid: this.$route.params.tableid }}" class="mx-2" v-if="page_is_table">
            {{ $route.params.tableid }}
          </router-link>
        </div>

        <div>
          <router-link :to="{ name: 'query' }" class="btn">
            Query
          </router-link>

        </div>

        <div>
          <a @click="SwitchTheme('light')" class="mx-2">Theme light</a>
          <a @click="SwitchTheme('color')" class="mx-2">Theme color</a>
        </div>
      </div>
    </header>

    <div class="flex pr-5 w-full justify-between mb-4 text-default">

      <TableListSidebar v-if="show_table_list_sidebar" />

      <div class="flex-grow py-6 relative pt-20 mb-4 pr-4">
        <router-view :key="$route.fullPath + $store.state.reloadMainComponentKey" />
      </div>

    </div>
  </div>
</template>

<style>

  body {
    background:              var(--background-body);
    background-attachment:   fixed;
    @apply text-primary;
    min-height:              100vh;
    @apply font-sans;
    -webkit-font-smoothing:  antialiased;
    -moz-osx-font-smoothing: grayscale;
  }

  html {
    --scrollbarBG: white;
    --thumbBG:     #90A4AE;
  }

  html::-webkit-scrollbar {
    width:  14px;
    height: 14px;
  }

  html {
    scrollbar-width: 14px;
    scrollbar-color: var(--thumbBG) var(--scrollbarBG);
  }

  html::-webkit-scrollbar-track {
    background: var(--scrollbarBG);
  }

  html::-webkit-scrollbar-thumb {
    background-color: var(--thumbBG);
    border-radius:    5px;
    border:           2px solid var(--scrollbarBG);
  }

  a {
    @apply no-underline cursor-pointer;
  }

  a:hover {
    @apply underline;
  }

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
        this.searchmodalopen   = false;
        this.recenttablesopen  = false;
        this.databasemodalopen = false;
        this.queryhistoryopen  = false;
        this.$nextTick();
        document.getElementById('app').focus();
      }
    },

    components: {
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

      page_is_table() {
        return (typeof this.$route.params.tableid !== 'undefined' && this.$route.params.tableid);
      }
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
