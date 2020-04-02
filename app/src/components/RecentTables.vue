<template>

  <div class="modal-container" :class="{ 'open' : modalisopen }" style="background: radial-gradient(circle, rgba(0,0,0,0.2) 20%, rgba(0,0,0,0.7) 80%); transition: opacity 0.2s ease-in;">

    <div class="relative rounded-md  w-full m-auto flex-col flex w-full max-w-md border-8 border-dark-600 bg-white" id="recent-tables">
      <div class="text-lg">
        <h3 class="bg-dark-600 text-gray-200 pt-1 pb-2 px-3 text-xl mb-4">
          Recent tables
        </h3>
        <ul class="mx-3 my-4" id="recent-tables-list" autocomplete="off">
          <li v-for="(table_name, index) in recent_tables" class="list-item" v-bind:class="{'active': isActive(index)}" :ref="table_name">
            <router-link :to="{ name: 'table', params: { tableid: table_name } }" class="block py-1 bg-orange-100 px-2">
              {{ table_name }}
            </router-link>
          </li>
        </ul>
      </div>
    </div>

  </div>
</template>

<script>

  export default {
    name: 'RecentTables',
    props: ['modalisopen', 'recent_tables'],
    data() {
      return {
        tables: [],
        current: 0
      }
    },

    created() {
      if (localStorage.getItem('recent_tables')) {
        this.tables = JSON.parse(localStorage.getItem('recent_tables'));
      }
      document.addEventListener('keydown', this.triggerKeyDown);
    },

    beforeDestroy() {
      document.removeEventListener('keydown', this.triggerKeyDown);
    },

    methods: {

      // When up pressed while autocomplete is open
      up() {
        if (this.current > 0) {
          this.current--;
        }
      },

      triggerKeyDown: function(evt) {
        if (evt.key === 'Escape') {
          this.close();
          evt.preventDefault();
        }
        if (evt.key === 'ArrowUp') {
          this.up();
          evt.preventDefault();
        }
        if (evt.key === 'ArrowDown' || evt.key === 'e') {
          this.down();
          evt.preventDefault();
        }
        if (evt.key === 'Enter') {
          let element = this.recent_tables[this.current];
          this.$refs[element][0].childNodes[0].click(); // click the link => go to table
          evt.preventDefault();
        }
      },

      // When up pressed while autocomplete is open
      down() {
        if (this.current < this.recent_tables.length - 1) {
          this.current++;
        }
      },

      fillautocomplete(index) {
        // if(this.matches[index] === 'undefined' || this.matches[index] == '') return;
        if (this.can_autocomplete_column) {
          let search_values = this.search_value.split(".");
          this.search_value = search_values[0] + '.' + this.matches[index];
        } else {
          this.search_value = this.matches[index];
        }
      },

      // For highlighting element
      isActive(index) {
        return index === this.current
      },

      close() {
        this.$emit('closerecenttables');
      }

    },

  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .modal-container.open {
    opacity:    1;
    visibility: visible;
    transition: opacity .2s ease-in;
  }

  .modal-container {
    position:   fixed;
    top:        0;
    right:      0;
    bottom:     0;
    left:       0;
    z-index:    50;
    overflow:   auto;
    display:    flex;
    visibility: hidden;
    opacity:    0;
    transition: opacity .2s ease-in;
  }

  .list-item {
    @apply px-1 text-on-bg-light;
  }

  .list-item a {
    @apply block py-1 px-1 border-b border-gray-300;
  }

  .list-item.active a {
    @apply bg-highlight-100 border-t border-highlight-700;
  }

</style>

