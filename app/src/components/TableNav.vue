<template>
  <div class="flex justify-between items-center mb-4 py-1 max-w-screen">
    <h2 class=" text-xl">{{ tableid }}</h2>
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
