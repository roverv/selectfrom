<template>

  <div class="modal-container" :class="{ 'open' : modalisopen }">

    <div class="modal-content modal-list max-w-md" id="recent-tables">
      <div class="text-lg">
        <h3 class="modal-title">
          Recent tables
        </h3>
        <ul class="mx-3 my-4" id="recent-tables-list" autocomplete="off">
          <li v-for="(table_name, index) in recent_tables" class="list-item" v-bind:class="{'active': isActive(index)}"
              :ref="table_name">
            <router-link :to="{ name: 'table', params: { tableid: table_name } }">
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
    props: ['modalisopen'],
    data() {
      return {
        current: 0
      }
    },

    created() {
      document.addEventListener('keydown', this.triggerKeyDown);
    },

    beforeDestroy() {
      document.removeEventListener('keydown', this.triggerKeyDown);
    },

    computed: {
      recent_tables() {
        return this.$store.getters["recenttables/tables"];
      },
    },


    methods: {

      // When up pressed while autocomplete is open
      up() {
        if (this.current > 0) {
          this.current--;
        }
      },

      triggerKeyDown: function (evt) {
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
