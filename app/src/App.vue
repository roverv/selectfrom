<template>
  <div id="app" class="grid-container-app" v-on:keydown.self.stop.exact.prevent.open-search="openSearchModal"
       v-on:keydown.self.exact.prevent.open-recent-tables="openRecentTables"
       v-on:keydown.self.stop.exact.prevent.to-query="goToQuery" v-on:keydown.self.stop.prevent.open-database-list="openDatabasesModal"
       v-on:keydown.self.stop.exact.prevent.open-query-history="openQueryHistory"
       v-on:keydown.self.stop.exact.prevent.level-up="moveLevelUp"
       v-on:keydown.self.stop.exact.prevent.refresh-page="refreshPage"
       v-on:keydown.self.exact.prevent.open-context-menu="openContextMenu"
       v-on:keydown.self.exact.prevent.open-inventory="openInventory"
       tabindex="0">

    <SearchModal v-if="active_database && searchmodalopen" :modalisopen="searchmodalopen"
                 v-on:closesearchmodal="closeSearchModal()" />

    <DatabasesModal v-if="databasemodalopen" :modalisopen="databasemodalopen"
                    v-on:closedatabasesmodal="closeDatabasesModal()" />

    <RecentTables v-if="active_database && recenttablesopen" :modalisopen="recenttablesopen"
                  v-on:closerecenttables="closeRecentTables()" tabindex="0"
                  @keydown.esc="recenttablesopen = false" />

    <QueryHistory v-if="active_database && queryhistoryopen" :modalisopen="queryhistoryopen"
                  v-on:closequeryhistory="closeQueryHistory()" tabindex="0" @keydown.esc="queryhistoryopen = false" />

    <Welcome :modalisopen="welcomeopen" v-on:closewelcome="closeWelcome()" v-if="welcomeopen"></Welcome>

    <ContextMenu :modalisopen="contextmenuopen" v-on:closecontextmenu="closeContext()" :contextoptions="contextoptions"
                 v-on:runContextAction="runContextAction" v-if="contextmenuopen"></ContextMenu>

    <InventoryModal :modalisopen="inventoryopen" v-on:closeinventory="closeInventory()" v-if="inventoryopen"></InventoryModal>

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
      <router-view :key="$route.fullPath + $store.state.reloadMainComponentKey" v-on:setcontextoptions="setContextOptions" ref="pageComponent" />
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
  import Welcome from "@/components/Welcome";
  import ContextMenu from "@/components/ContextMenu";
  import InventoryModal from "@/components/InventoryModal";

  export default {

    data() {
      return {
        searchmodalopen: false,
        databasemodalopen: false,
        recenttablesopen: false,
        queryhistoryopen: false,
        welcomeopen: false,
        contextmenuopen: false,
        inventoryopen: false,
        contextoptions: [],
        tables: [],
      }
    },

    created() {
      this.$store.dispatch("databases/get");
      this.setCsrfTokenOnRequests();
    },

    watch: {
      $route(to, from) {

        if(this.$route.params.hasOwnProperty('database')) {
          if(this.$store.state.activeDatabase == '' || this.$store.state.activeDatabase != this.$route.params.database) {
            this.$store.commit("setActiveDatabase", this.$route.params.database);
          }
        }
        else if(['adddatabase', 'server', 'login', 'settings'].includes(to.name)) {
          this.$store.commit("setActiveDatabase", '');
        }

        if(from.name == 'login' && this.do_not_show_welcome_message === false) {
          this.welcomeopen   = true;
        }

        this.searchmodalopen   = false;
        this.recenttablesopen  = false;
        this.databasemodalopen = false;
        this.queryhistoryopen  = false;
        this.contextmenuopen   = false;
        this.inventoryopen     = false;
        this.contextoptions    = [];
        this.$nextTick();
        document.getElementById('app').focus();
      }
    },

    components: {
      InventoryModal,
      Welcome,
      ApiError,
      MainNavigation,
      TableListSidebar,
      RecentTables,
      SearchModal,
      DatabasesModal,
      QueryHistory,
      ContextMenu,
    },

    computed: {
      active_database() {
        return this.$store.state.activeDatabase;
      },

      show_table_list_sidebar() {
        return !(this.active_database == '' || this.$route.name == 'server');
      },

      do_not_show_welcome_message() {
        return this.$store.getters["settings/getSetting"]('do_not_show_welcome_message');
      },

      a_modal_is_open() {
        return (this.searchmodalopen || this.recenttablesopen || this.databasemodalopen || this.queryhistoryopen || this.contextmenuopen || this.inventoryopen);
      }
    },

    methods: {

      openSearchModal() {
        if(!this.active_database) return;
        if (this.a_modal_is_open) return;
        this.searchmodalopen = true;
      },

      openDatabasesModal() {
        if (this.a_modal_is_open) return;
        this.databasemodalopen = true;
      },

      openRecentTables() {
        if(!this.active_database) return;
        if (this.a_modal_is_open) return;
        this.recenttablesopen = true;
      },

      openQueryHistory() {
        if(!this.active_database) return;
        if (this.a_modal_is_open) return;
        this.queryhistoryopen = true;
      },

      openContextMenu() {
        if (this.a_modal_is_open) return;
        this.contextmenuopen = true;
      },

      openInventory() {
        if(!this.active_database) return;
        if (this.a_modal_is_open) return;
        this.inventoryopen = true;
      },

      setContextOptions(contextoptions) {
        this.contextoptions = contextoptions;
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

      closeWelcome() {
        this.welcomeopen = false;
        // when the modal is closed, we need to set the focus back on the app
        document.getElementById('app').focus();
      },

      closeContext() {
        this.contextmenuopen = false;
        // when the modal is closed, we need to set the focus back on the app
        document.getElementById('app').focus();
      },

      closeInventory() {
        this.inventoryopen = false;
        // when the modal is closed, we need to set the focus back on the app
        document.getElementById('app').focus();
      },

      runContextAction(context_action) {
        this.$refs.pageComponent[context_action]();
      },

      moveLevelUp() {
        switch (this.$route.name) {
          case 'database':
          case 'adddatabase':
          case 'editdatabase':
            this.$router.push({name: 'server'});
            break;

          case 'query':
          case 'queryhistory':
          case 'exportdatabase':
          case 'importdatabase':
            this.$router.push({name: 'database'});
            break;

          case 'addrow':
          case 'editrow':
          case 'edittable':
          case 'editview':
            this.$router.push({name: 'table', params: {database: this.$route.params.database, tableid: this.$route.params.tableid}});
            break;

          case 'addindex':
          case 'editindex':
            this.$router.push({name: 'indexes', params: {database: this.$route.params.database, tableid: this.$route.params.tableid}});
            break;

          case 'table':
          case 'tablewithcolumn':
          case 'tablewithcolumnvalue':
          case 'structure':
          case 'addtable':
          case 'addview':
          case 'indexes':
            this.$router.push({name: 'database'});
            break;
        }
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

      setCsrfTokenOnRequests() {
        if(typeof this.$store.state.csrf_token !== 'undefined' || Object.keys(this.$store.state.csrf_token).length !== 0) {
          // if the csrf token is set, send it along with all axios requests
          this.$http.defaults.headers.post['X-CSRF-NAME'] = this.$store.state.csrf_token.csrf_name;
          this.$http.defaults.headers.post['X-CSRF-VALUE'] = this.$store.state.csrf_token.csrf_value;
        }
      }

    }
  }

</script>
