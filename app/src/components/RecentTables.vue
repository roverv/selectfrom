<template>

  <div class="modal-container" :class="{ 'open' : modalisopen }"
       @keyup.esc="$emit('closerecenttables')"
       style="background-color: rgba(0,0,0,0.5); transition: opacity 0.2s ease-in;">

    <!--    <div class="relative rounded-md  w-full m-auto flex-col flex w-full max-w-md border-8 border-gray-800 bg-white"-->
    <!---->
    <div class="relative rounded-md  w-full m-auto flex-col flex w-full max-w-md border-8 border-gray-800 bg-white"
    >
      <div class="text-lg">
        <h3 class="bg-gray-800 text-gray-200 pt-1 pb-2 px-3 text-xl mb-4">Recent tables</h3>
        <ul class="mx-3 my-4">
          <li v-for="table in tables" class="border-b border-t border-orange-400">
            <router-link :to="{ name: 'table', params: { tableid: table } }" class="block py-1 bg-orange-100 px-2">
              {{ table }}
            </router-link>
          </li>
          <li class="border-b border-gray-300">
            <a href="" class="block py-1 px-2">organisation</a>
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
        tables: [],
      }
    },

    created() {
      if (localStorage.getItem('recent_tables')) {
        this.tables = JSON.parse(localStorage.getItem('recent_tables'));
      }
    },

    methods: {

      // When up pressed while autocomplete is open
      up() {
        if (this.current > 0) {
          this.current--;
          var element = document.getElementById('match-' + this.current);
          element.scrollIntoView({behavior: "auto", block: "center", inline: "center"});
        }
      },

      // When up pressed while autocomplete is open
      down() {
        if (this.current < this.matches.length - 1) {
          this.current++;
          var element = document.getElementById('match-' + this.current);
          element.scrollIntoView({behavior: "auto", block: "center", inline: "center"});
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

  .autocomplete-row {
    @apply px-3;
  }

  .autocomplete-row a {
    @apply block py-1 px-2 border-b border-gray-300;
  }

  .autocomplete-row.active a {
    @apply bg-orange-100 border-t border-orange-400;
  }

</style>

