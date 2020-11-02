<template>

  <div class="modal-container" :class="{ 'open' : modalisopen }">

    <div class="modal-content modal-list max-w-md" id="recent-tables">
      <div class="text-lg">
        <h3 class="modal-title">
          Recent tables
        </h3>
        <ul class="mx-3 my-4" id="recent-tables-list" autocomplete="off">
          <li class="list-item" v-if="recent_tables.length == 0">
            No tables visited
          </li>
          <li v-for="(table_name, index) in recent_tables" class="list-item" v-bind:class="{'active': isActive(index)}"
              :ref="table_name">
            <router-link :to="{ name: 'table', params: { database: active_database,  tableid: table_name } }">
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
      // when you press e on app.vue, the recent tables will open but also trigger the keydown below, adding +1 to current
      // the small timeout below prevents this
      let self = this
      setTimeout(function () {
        document.addEventListener('keydown', self.triggerKeyDown);
      }, 50);
    },

    beforeDestroy() {
      document.removeEventListener('keydown', this.triggerKeyDown);
    },

    computed: {
      active_database() {
        return this.$store.state.activeDatabase;
      },

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
        else {
          this.current = this.recent_tables.length - 1;
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
        else {
          this.current = 0;
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
