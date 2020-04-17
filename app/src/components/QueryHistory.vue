<template>

  <div class="modal-container" :class="{ 'open' : modalisopen }" style="background: radial-gradient(circle, rgba(0,0,0,0.2) 20%, rgba(0,0,0,0.7) 80%); transition: opacity 0.2s ease-in;">

    <div class="relative rounded-md  w-full m-auto flex-col flex w-full max-w-6xl border-8 border-dark-600 bg-white">
      <div class="text-lg">
        <h3 class="bg-dark-600 text-gray-200 pt-1 pb-2 px-3 text-xl mb-4">
          Query history
        </h3>
        <ul class="mx-3 my-4" autocomplete="off">
          <li v-for="(query, index) in query_history" class="list-item" v-bind:class="{'active': isActive(index)}" :ref="index">
            <router-link :to="{ name: 'queryhistory', params: { historyindex: index } }" class="block py-1 bg-orange-100 px-2">
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

    computed:  {
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

      triggerKeyDown: function(evt) {
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

  .list-item:not(.active) {
    @apply truncate;
  }

  .list-item.active a {
    @apply bg-highlight-100 border-t border-highlight-700;
  }

</style>

