<template>

  <div class="modal-container" :class="{ 'open' : modalisopen }"
       @keyup.esc="$emit('closesearchmodal')"
       style="background-color: rgba(0,0,0,0.5); transition: opacity 0.2s ease-in;">
    <div class="relative rounded-md  w-full m-auto flex-col flex w-full max-w-2xl">
      <div class="px-8 py-4">
        <form method="post" @submit.prevent="submitSearch">
<!--          <input type="text" name="searchany" id="searchany" v-model="search_value" ref="searchany"-->
<!--                 class="block w-full my-3 px-4 py-3 text-xl border-8 border-gray-800 focus:outline-none"-->
<!--                 style="box-shadow: 0 15px 35px hsla(0,0%,0%,.2);" placeholder="Go to table, column or row">-->

          <input v-model="search_value"
                 type="text" name="searchany" id="searchany" ref="searchany"
                 class="block w-full mt-3 px-4 py-3 text-xl border-8 border-gray-800 focus:outline-none"
                 style="box-shadow: 0 15px 35px hsla(0,0%,0%,.2);" placeholder="Go to table, column or row"
                 @keydown.down = 'down'
                 @keydown.up = 'up'
                 @keydown.right = 'fillautocomplete(current)'
                 autocomplete="off"
          >
          <div class="relative" v-if="openAutocomplete">
<!--            :value="search_value" @input="updateValue($event.target.value)"-->
            <ul class="bg-gray-200 absolute w-full border-8 border-gray-800 border-t-0 h-64 overflow-y-scroll" >
              <li v-for="(table_name, index) in matches" :id="'match-' + index"
                  v-bind:class="{'active': isActive(index)}"
                  class="autocomplete-row"
                  @click="fillautocomplete(index)">
                <a href="#">{{ table_name }}</a>
              </li>
            </ul>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>

  import axios from 'axios'

  export default {
    name: 'SearchModal',
    props: ['modalisopen', 'active_database'],
    data() {
      return {
        search_value: '',
        tables_with_columns: [],
        tables: [],
        endpoint: 'http://localhost/rove/api/tables_with_columns.php?db=',
        current: 0
      }
    },


    watch: {

      active_database: function(value) {
        if(value) {
          if(!localStorage.getItem('tables_with_columns')) {
            this.getTablesWithColumns();
          }
          else {
            this.tables_with_columns = JSON.parse(localStorage.getItem('tables_with_columns'));
            this.tables = Object.keys(this.tables_with_columns);
          }
        }
      },

      modalisopen: function() {
        if(this.modalisopen) {
          this.$nextTick(() => this.$refs.searchany.focus())
        }
        else {
          this.search_value = '';
        }
      },

      matches: function() {
        // an autocomplete item should alway be active
        if(this.current > this.matches.length) {
          console.log(this.current, this.matches.length);
          this.current = 0;
        }
      }
    },

    computed: {
      // Filtering the suggestion based on the input
      matches () {
        if(this.can_autocomplete_column) {
          let search_values = this.search_value.split(".");
          return this.tables_with_columns[search_values[0]].filter((column_name) => {
            return column_name.includes(search_values[1]);
          });
        }
        else {
          return this.tables.filter((table_name) => {
            return table_name.includes(this.search_value);
          });
        }
      },

      openAutocomplete () {
        return this.search_value.length > 0 && this.matches.length !== 0;
      },

      entering_column () {
        return this.search_value.includes(".");
      },

      can_autocomplete_column() {
        if(!this.entering_column) return false;

        let search_values = this.search_value.split(".");
        if(this.tables_with_columns[search_values[0]] !== 'undefined') return true;
        return false;
      }
    },

    methods: {

      getTablesWithColumns() {
        axios.get(this.endpoint + this.active_database).then(response => {
          this.tables_with_columns = response.data;
          this.tables = Object.keys(this.tables_with_columns);
          localStorage.setItem('tables_with_columns', JSON.stringify(response.data));
        }).catch(error => {
          console.log('-----error-------');
          console.log(error);
        })
      },

      submitSearch() {
        if(this.search_value == '') return false;

        let has_id = this.search_value.includes("#");
        let has_column = this.search_value.includes(".");

        if(has_id) {
          let search_values = this.search_value.split("#");
          let table_id = this.normalizeValue(search_values[0]);
          this.$router.push({ name: 'tablewithcolumnvalue', params: { tableid: table_id, column: 'id', value: search_values[1] } });
        }
        else if(has_column) {
          let has_column_value = this.search_value.includes("%");
          if(has_column_value) {
            let search_values = this.search_value.split(".");
            let data_values = search_values[1].split("%");
            let table_id = this.normalizeValue(search_values[0]);
            let column = this.normalizeValue(data_values[0]);
            this.$router.push({ name: 'tablewithcolumnvalue', params: { tableid: table_id, column: column, value: data_values[1] } });
          }
          else {
            let search_values = this.search_value.split(".");
            let table_id = this.normalizeValue(search_values[0]);
            let column = this.normalizeValue(search_values[1]);
            this.$router.push({ name: 'tablewithcolumn', params: { tableid: table_id, column: column } });
          }
        }
        else {
          this.$router.push({ name: 'table', params: { tableid: this.search_value } });
        }
        // when on the same route, router wont change component, so close the modal
        this.$emit('closemodal');
      },

      normalizeValue(value) {
        return value.toLowerCase();
      },

    // When up pressed while autocomplete is open
    up () {
      if (this.current > 0) {
        this.current--;
        var element = document.getElementById('match-' + this.current);
        element.scrollIntoView({behavior: "auto", block: "center", inline: "center"});
      }
    },

    // When up pressed while autocomplete is open
    down () {
      if (this.current < this.matches.length - 1) {
        this.current++;
        var element = document.getElementById('match-' + this.current);
        element.scrollIntoView({behavior: "auto", block: "center", inline: "center"});
      }
    },

    fillautocomplete (index) {
      // if(this.matches[index] === 'undefined' || this.matches[index] == '') return;
      if(this.can_autocomplete_column) {
        let search_values = this.search_value.split(".");
        this.search_value = search_values[0] + '.' + this.matches[index];
      }
      else {
        this.search_value = this.matches[index];
      }
    },

    // For highlighting element
    isActive (index) {
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
    position:         fixed;
    top:              0;
    right:            0;
    bottom:           0;
    left:             0;
    z-index:          50;
    overflow:         auto;
    display:          flex;
    visibility:       hidden;
    opacity:          0;
    transition:       opacity .2s ease-in;
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

