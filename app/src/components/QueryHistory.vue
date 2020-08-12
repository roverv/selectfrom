<template>

  <div class="modal-container" :class="{ 'open' : modalisopen }">

    <div class="modal-content modal-list max-w-6xl">
      <div class="text-lg">
        <h3 class="modal-title">
          Query history
        </h3>
        <ul class="mx-3 my-4" autocomplete="off">
          <li v-for="(query, index) in query_history" class="list-item" v-bind:class="{'active': isActive(index)}"
              :ref="index">
            <router-link :to="{ name: 'queryhistory', params: { database: active_database, historyindex: index } }">
              {{ query }}
            </router-link>
          </li>
        </ul>
      </div>
    </div>

  </div>
</template>

<script>

  export default {
    name: 'QueryHistory',
    props: ['modalisopen'],
    data() {
      return {
        tables: [],
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
      active_database() {
        return this.$store.state.activeDatabase;
      },
      
      query_history() {
        return this.$store.getters["queryhistory/queries"];
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
        if (evt.key === 'ArrowDown' || evt.key === 'h') {
          this.down();
          evt.preventDefault();
        }
        if (evt.key === 'Enter') {
          // let element = this.query_history[this.current];
          this.$refs[this.current][0].childNodes[0].click(); // click the link => go to table
          evt.preventDefault();
        }
      },

      // When up pressed while autocomplete is open
      down() {
        if (this.current < this.query_history.length - 1) {
          this.current++;
        }
      },

      // For highlighting element
      isActive(index) {
        return index === this.current
      },

      close() {
        this.$emit('closequeryhistory');
      }

    },

  }
</script>

