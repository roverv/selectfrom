<template>
  <div>
    <router-link :to="{ name: 'table', params: { database: active_database, tableid: tableid } }" class="subnav-item"
                 :class="{ 'active' : (['table', 'tablewithcolumn', 'tablewithcolumnvalue'].includes($route.name)) }">
      Data
    </router-link>

    <router-link :to="{ name: 'structure', params: { database: active_database, tableid: tableid } }" class="subnav-item"
                 :class="{ 'active' : ($route.name == 'structure') }">
      Structure
    </router-link>
    <a class="subnav-item">
      Indexes
    </a>
    <a class="subnav-item">
      Foreign keys
    </a>

    <router-link :to="{ name: 'addrow', params: { database: active_database, tableid: tableid } }" class="subnav-item"
                 :class="{ 'active' : ($route.name == 'addrow') }">
      Add row
    </router-link>
  </div>
</template>


<script>

  export default {
    name: 'TableNav',
    props: ['tableid'],
    data() {
      return {}
    },

    computed: {
      active_database() {
        return this.$store.state.activeDatabase;
      },
    }

  }
</script>


<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

  .subnav-item {
    @apply inline-flex px-6 leading-tight items-center text-light-300 border-r border-light-100;
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
