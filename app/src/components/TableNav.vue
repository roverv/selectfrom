<template>
  <div class="mx-5">
    <router-link :to="{ name: 'table', params: { database: active_database, tableid: tableid } }" class="subnav-item"
                 :class="{ 'active' : (['table', 'tablewithcolumn', 'tablewithcolumnvalue', 'editrow'].includes($route.name)) }">
      Data
    </router-link>

    <router-link :to="{ name: 'structure', params: { database: active_database, tableid: tableid } }"
                 class="subnav-item"
                 :class="{ 'active' : (['structure', 'edittable'].includes($route.name)) }">
      Structure
    </router-link>

    <template v-if="is_view">
      <router-link :to="{ name: 'editview', params: { database: active_database, tableid: tableid } }"
                   class="subnav-item"
                   :class="{ 'active' : (['editview'].includes($route.name)) }">
        Edit view
      </router-link>
    </template>
    <template v-else>
      <router-link :to="{ name: 'indexes', params: { database: active_database, tableid: tableid } }"
                   class="subnav-item"
                   :class="{ 'active' : (['indexes'].includes($route.name)) }">
        Indexes
      </router-link>
      <router-link :to="{ name: 'foreignkeys', params: { database: active_database, tableid: tableid } }"
                   class="subnav-item"
                   :class="{ 'active' : (['foreignkeys'].includes($route.name)) }">
        Foreign keys
      </router-link>
      <router-link :to="{ name: 'addrow', params: { database: active_database, tableid: tableid } }" class="subnav-item"
                   :class="{ 'active' : ($route.name == 'addrow') }">
        Add row
      </router-link>
    </template>
  </div>
</template>


<script>

export default {
  name: 'TableNav',
  props: ['tableid'],
  data() {
    return {}
  },

  created() {
    document.addEventListener('keydown', this.triggerKeyDown);
  },

  destroyed() {
    document.removeEventListener("keydown", this.triggerKeyDown);
  },

  computed: {
    active_database() {
      return this.$store.state.activeDatabase;
    },
    tables() {
      return this.$store.getters["tables/tables"];
    },
    nodes_skip_on_key() {
      return this.$store.state.nodes_skip_on_key;
    },
    is_view() {
      let current_table_data = this.tables.find(table_data => table_data.name == this.tableid);
      if (current_table_data.type == 'VIEW') return true;
      return false;
    }
  },

  methods: {
    triggerKeyDown: function (evt) {
      const {nodeName} = document.activeElement;
      if (this.nodes_skip_on_key.includes(nodeName)) return;
      // context menu has overlapping key shortcuts with table nav
      if (document.activeElement.id == 'context-menu') return;

      if (evt.key === '1') {
        this.$router.push({name: 'table', params: {database: this.active_database, tableid: this.tableid}});
      } else if (evt.key === '2') {
        this.$router.push({name: 'structure', params: {database: this.active_database, tableid: this.tableid}});
      }

      if(this.is_view) {
        if (evt.key === '3') {
          this.$router.push({name: 'editview', params: {database: this.active_database, tableid: this.tableid}});
        }
      }
      else {
        if (evt.key === '3') {
          this.$router.push({name: 'indexes', params: {database: this.active_database, tableid: this.tableid}});
        } else if (evt.key === '4') {
          this.$router.push({name: 'foreignkeys', params: {database: this.active_database, tableid: this.tableid}});
        } else if (evt.key === '5') {
          this.$router.push({name: 'addrow', params: {database: this.active_database, tableid: this.tableid}});
        }
      }
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
