<template>
  <div id="app" v-on:keyup.f="openSearchModal" v-on:keyup.e="openRecentTables" tabindex="0">

    <SearchModal v-if="active_database" :modalisopen="searchmodalopen" v-on:closesearchmodal="closeSearchModal()" :active_database="active_database" />

<!--    <RecentTables :modalisopen="recenttablesopen" v-on:closerecenttables="closeRecentTables()" />-->

    <header class="bg-gray-500 text-gray-200 py-5 px-10 mb-4">
      <h3>Rove</h3>
      <router-link to="/test">Test</router-link>

      <router-link to="/">Home</router-link> &gt;
      <router-link to="/database">Rove</router-link> &gt;

      <SwitchDatabase v-on:setActiveDatabase="setActiveDatabase" :databases="databases"></SwitchDatabase>
    </header>

    <div class="flex pr-8 w-full justify-between mb-4">

      <TableList :active_database="active_database" />

      <router-view :key="$route.fullPath" :active_database="active_database"  />

    </div>
  </div>
</template>

<style>

  body {
    @apply bg-gray-200 font-sans;
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
        tables: [],
      }
    },

    created() {
      if (!localStorage.getItem('databases')) {
        this.getDatabases();
      } else {
        this.databases = JSON.parse(localStorage.getItem('databases'));
        this.tables    = Object.keys(this.databases);
      }
      if (localStorage.getItem('active_database')) {
        this.active_database = localStorage.getItem('active_database');
      }
    },

    watch: {
      $route (to, from){
        this.searchmodalopen = false;
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
        if(this.searchmodalopen || this.recenttablesopen) return;
        this.searchmodalopen = true;
      },

      openRecentTables() {
        if(this.searchmodalopen || this.recenttablesopen) return;
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
      }

    }
  }

</script>
