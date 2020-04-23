<template>
  <div class="flex justify-between items-center mb-4 py-1 max-w-screen">
    <a class="inline-flex items-center" @click="$emit('toggleMetaBox')">
      <h2 class="text-xl">
        {{ tableid }}
      </h2>
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 ml-1 fill-current">
        <path class="text-gray-400"
              d="M15.3 10.3a1 1 0 0 1 1.4 1.4l-4 4a1 1 0 0 1-1.4 0l-4-4a1 1 0 0 1 1.4-1.4l3.3 3.29 3.3-3.3z"></path>
      </svg>
    </a>
    <div>
      <router-link :to="{ name: 'table', params: { tableid: tableid } }" class="subnav-item"  :class="{ 'active' : ($route.name == 'table') }">
        Data
      </router-link>

      <router-link :to="{ name: 'structure', params: { tableid: tableid } }" class="subnav-item" :class="{ 'active' : ($route.name == 'structure') }">
        Structure
      </router-link>
      <a class="subnav-item">
        Indexes
      </a>
      <a class="subnav-item">
        Foreign keys
      </a>

      <router-link :to="{ name: 'addrow', params: { tableid: tableid } }" class="subnav-item" :class="{ 'active' : ($route.name == 'addrow') }">
        Add row
      </router-link>
    </div>
    <div></div>

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
    @apply  inline-flex px-6 leading-tight items-center text-light-300 border-r border-light-100;
  }
  .subnav-item:last-child {
    @apply border-0;
  }
  .subnav-item:hover {
    @apply underline;
  }
  .subnav-item.active {
    @apply text-highlight-700;
  }

</style>
