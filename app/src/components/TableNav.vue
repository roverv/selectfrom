<template>
  <div class="flex justify-between items-center mb-4 py-1 max-w-screen">
    <h2 class=" text-xl">{{ tableid }}</h2>
    <div>
      <router-link :to="{ name: 'table', params: { tableid: tableid } }" class="subnav-item"  :class="{ 'active' : ($route.name == 'table') }">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 fill-current mr-2 text-gray-400">
          <rect width="20" height="12" x="2" y="10" class="primary" rx="2"></rect>
          <path class="text-gray-400"
                d="M20 8H4c0-1.1.9-2 2-2h12a2 2 0 0 1 2 2zm-2-4H6c0-1.1.9-2 2-2h8a2 2 0 0 1 2 2z"></path>
        </svg>
        Data
      </router-link>

      <router-link :to="{ name: 'structure', params: { tableid: tableid } }" class="subnav-item" :class="{ 'active' : ($route.name == 'structure') }">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 fill-current mr-2 text-gray-400">
          <path class="primary"
                d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2zm0 2v10h2a2 2 0 0 1 2 2c0 1.1.9 2 2 2h2a2 2 0 0 0 2-2c0-1.1.9-2 2-2h2V5H5z"></path>
          <path class="text-gray-600"
                d="M8 7h8a1 1 0 0 1 0 2H8a1 1 0 1 1 0-2zm0 4h8a1 1 0 0 1 0 2H8a1 1 0 0 1 0-2z"></path>
        </svg>
        Structure
      </router-link>
      <a class="subnav-item">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 fill-current mr-2 text-gray-400">
          <polygon class="primary" points="3 6 12 2 21 6 21 18 12 22 3 18"></polygon>
          <path class="text-gray-600" d="M21 6v12l-9 4V10l9-4zM9 8.67v3l-3-1.34v-3l9-4 3 1.34-9 4z"></path>
        </svg>
        Indexes
      </a>

      <a class="subnav-item">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 fill-current mr-2 text-gray-400">
          <circle cx="12" cy="12" r="10" class="primary"></circle>
          <path class="text-gray-600"
                d="M13 11h4a1 1 0 0 1 0 2h-4v4a1 1 0 0 1-2 0v-4H7a1 1 0 0 1 0-2h4V7a1 1 0 0 1 2 0v4z"></path>
        </svg>
        Add row
      </a>
    </div>
    <div>
      <!--      <a href="">-->
      <!--        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-6 fill-current text-gray-400">-->
      <!--          <path class="secondary"-->
      <!--                d="M9.53 16.93a1 1 0 0 1-1.45-1.05l.47-2.76-2-1.95a1 1 0 0 1 .55-1.7l2.77-.4 1.23-2.51a1 1 0 0 1 1.8 0l1.23 2.5 2.77.4a1 1 0 0 1 .55 1.71l-2 1.95.47 2.76a1 1 0 0 1-1.45 1.05L12 15.63l-2.47 1.3z" />-->
      <!--        </svg>-->
      <!--      </a>-->
    </div>
  </div>
</template>


<script>

  export default {
    name: 'TableNav',
    props: ['tableid'],
    data() {
      return {
      }
    },

  }
</script>


<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

  .max-w-screen {
    max-width: 100vw;
  }

  .subnav-item {
    @apply mx-2 inline-flex rounded-lg py-1 px-2 items-center;
  }
  .subnav-item:hover {
    @apply bg-light-100;
  }
  .subnav-item.active {
    @apply px-3 border-2 border-light-200 bg-light-100;
  }

</style>
