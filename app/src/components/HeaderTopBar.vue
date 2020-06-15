<template>
  <header class="py-3 px-10 mb-3 border-b border-light-100 w-full z-20">
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
        <a @click="$emit('SwitchTheme', 'light')" class="mx-2">Theme light</a>
        <a @click="$emit('SwitchTheme','color')" class="mx-2">Theme color</a>
      </div>
    </div>
  </header>
</template>


<script>

  export default {
    name: 'HeaderTopBar',

    data() {
      return {}
    },

    computed: {
      active_database() {
        return this.$store.state.activeDatabase;
      },
    },


  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>


</style>
