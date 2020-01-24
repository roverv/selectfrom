<template>
  <div id="app" v-on:keyup.f="openSearchModal" v-on:keyup.e="openRecentTables" tabindex="0">

    <SearchModal v-if="active_database && searchmodalopen" :modalisopen="searchmodalopen" v-on:closesearchmodal="closeSearchModal()"
                 :active_database="active_database" />

    <RecentTables v-if="active_database && recenttablesopen" :modalisopen="recenttablesopen" :recent_tables="recent_tables" v-on:closerecenttables="closeRecentTables()" tabindex="0" @keydown.esc="recenttablesopen = false" />

    <header class="bg-gray-500 text-gray-200 py-3 px-10 mb-4 flex">
      <h3 class="mb-0">{{ active_database }}</h3>
      <router-link to="/test">Test</router-link>

      <router-link to="/">Home</router-link> &gt;
      <router-link to="/database">Rove</router-link> &gt;

      <SwitchDatabase v-on:setActiveDatabase="setActiveDatabase" :active_database="active_database" :databases="databases"></SwitchDatabase>

      <a @click="SwitchTheme('light')">Theme light</a>
      <a @click="SwitchTheme('color')">Theme color</a>
    </header>

    <div class="flex pr-8 w-full justify-between mb-4 text-default">

      <TableList :active_database="active_database" />

      <router-view :key="$route.fullPath" :active_database="active_database" v-on:addrecenttable="addRecentTable" />

    </div>
  </div>
</template>

<style>

  body {
    background: var(--background-body);
    background-attachment: fixed;
    @apply text-primary;
    min-height: 100vh;
    @apply font-sans;
    -webkit-font-smoothing:  antialiased;
    -moz-osx-font-smoothing: grayscale;
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
  import TableList from '@/components/TableList.vue'
  import RecentTables from "./components/RecentTables";
  import SwitchDatabase from "./components/SwitchDatabase";
  import axios from 'axios';

  export default {

    data() {
      return {
        endpoint: 'http://localhost/rove/api/',
        searchmodalopen: false,
        recenttablesopen: false,
        databases: [],
        active_database: '',
        recent_tables: [],
        tables: [],
      }
    },

    created() {
      if (!localStorage.getItem('databases')) {
        this.getDatabases();
      } else {
        this.databases = JSON.parse(localStorage.getItem('databases'));
        this.tables    = Object.keys(this.databases);
        if (localStorage.getItem('active_database')) {
          this.active_database = localStorage.getItem('active_database');
        }
      }

      if(sessionStorage.getItem('recent_tables')) {
        this.recent_tables = JSON.parse(sessionStorage.getItem('recent_tables'));
      }
    },

    watch: {
      $route(to, from) {
        this.searchmodalopen  = false;
        this.recenttablesopen = false;
        this.$nextTick();
        document.getElementById('app').focus();
      }
    },

    components: {
      RecentTables,
      SearchModal,
      TableList,
      SwitchDatabase
    },

    methods: {

      getDatabases() {
        axios.get(this.endpoint + 'databases.php').then(response => {
          this.databases = response.data;
          localStorage.setItem('databases', JSON.stringify(response.data));
          if (localStorage.getItem('active_database')) {
            this.active_database = localStorage.getItem('active_database');
          }
        }).catch(error => {
          console.log('-----error-------');
          console.log(error);
        })
      },

      setActiveDatabase(active_database) {
        localStorage.clear();
        sessionStorage.clear();
        localStorage.setItem('active_database', active_database);
        this.active_database = active_database;
      },

      openSearchModal() {
        if (this.searchmodalopen || this.recenttablesopen) return;
        this.searchmodalopen = true;
      },

      openRecentTables() {
        if (this.searchmodalopen || this.recenttablesopen) return;
        this.recenttablesopen = true;
      },

      closeSearchModal() {
        this.searchmodalopen = false;
        // when the modal is closed, we need to set the focus back on the app
        document.getElementById('app').focus();
      },

      closeRecentTables() {
        this.recenttablesopen = false;
        // when the modal is closed, we need to set the focus back on the app
        document.getElementById('app').focus();
      },

      forceupdate() {
        this.$forceUpdate();
      },

      addRecentTable: function(tableid) {
        // remove duplicates
        if (this.recent_tables.indexOf(tableid) >= 0) {
          this.recent_tables.splice(this.recent_tables.indexOf(tableid), 1);
        }
        // limit to 8
        while(this.recent_tables.length > 7) {
          this.recent_tables.splice(7, 1);
        }
        // add new table to the beginning of the array
        this.recent_tables.unshift(tableid);
        sessionStorage.setItem('recent_tables', JSON.stringify(this.recent_tables));
      },

      SwitchTheme(theme_name) {
        document.documentElement.removeAttribute("class");
        document.documentElement.classList.add("theme-" + theme_name);
      }

    }
  }

</script>
