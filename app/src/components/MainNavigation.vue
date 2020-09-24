<template>
  <div class="main-navigation">

    <div class="px-5 py-2 text-center border-light-200 mb-4 ">
      <h2 class="font-bold m-0 p-0">SAKOJA</h2>
    </div>

    <div class="flex flex-col flex-wrap py-3 px-5">

      <div class="w-full">

        <div class="flex items-center mb-1">
          <router-link :to="{ name: 'server'}" class="btn-link ">
            Databases
          </router-link>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 fill-current text-gray-400"
               style="transform: rotate(90deg);">
            <path
                d="M8.7 13.7a1 1 0 1 1-1.4-1.4l4-4a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1-1.4 1.4L12 10.42l-3.3 3.3z"></path>
          </svg>
        </div>

        <div class="flex items-center mb-1" v-if="active_database">
          <router-link :to="{ name: 'database', params: {database: active_database }}" class="btn-link">
            {{ active_database }}
          </router-link>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 fill-current text-gray-400"
               style="transform: rotate(90deg);">
            <path
                d="M8.7 13.7a1 1 0 1 1-1.4-1.4l4-4a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1-1.4 1.4L12 10.42l-3.3 3.3z"></path>
          </svg>
        </div>

        <div class="flex items-center mb-1 " v-if="active_database && $route.params.hasOwnProperty('tableid')">
          <router-link :to="{ name: 'table', params: {database: active_database, tableid: $route.params.tableid }}" class="btn-link">
            {{ $route.params.tableid }}
          </router-link>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 fill-current text-gray-400"
               style="transform: rotate(90deg);">
            <path
                d="M8.7 13.7a1 1 0 1 1-1.4-1.4l4-4a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1-1.4 1.4L12 10.42l-3.3 3.3z"></path>
          </svg>
        </div>

      </div>

      <hr class="border-light-200 my-3">

      <div class="w-full">
        <router-link :to="{ name: 'query' }" class="btn-link">
          Query
        </router-link>
        <router-link :to="{ name: 'query' }" class="btn-link">
          Search
        </router-link>
        <router-link :to="{ name: 'query' }" class="btn-link">
          Import
        </router-link>
        <router-link :to="{ name: 'query' }" class="btn-link">
          Export
        </router-link>
      </div>

      <hr class="border-light-200 my-3">

      <div class="w-full mb-8">
        <a @click="refreshCache()" class="btn-link">
          Refresh cache
        </a>
        <a @click="refreshPage()" class="btn-link">
          Refresh page
        </a>
        <router-link :to="{ name: 'settings' }" class="btn-link">
          Settings
        </router-link>
        <a href="" class="btn-link">
          Logout
        </a>
      </div>
    </div>

  </div>
</template>


<script>

export default {
  name: 'MainNavigation',

  data() {
    return {}
  },

  computed: {
    active_database() {
      return this.$store.state.activeDatabase;
    },
  },

  methods: {

    refreshCache() {
      this.$store.dispatch('refreshTables')
      this.$store.dispatch('refreshDatabases')
    },

    refreshPage() {
      this.$store.state.reloadMainComponentKey += 1;
    }

  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

.main-navigation {
  @apply border-r border-light-100 z-20 w-56 h-full absolute;
  margin-left: -215px;
}

.main-navigation:hover {
  @apply ml-0 bg-dark-400 border-0;
  transition: all 0.3s ease;
}

.btn-link {
  @apply block w-full py-1;
}

</style>
