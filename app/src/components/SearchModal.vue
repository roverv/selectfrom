<template>

  <div class="modal-container" :class="{ 'open' : modalisopen }"
       style="background: radial-gradient(circle, rgba(0,0,0,0.2) 20%, rgba(0,0,0,0.7) 80%); transition: opacity 0.2s ease-in;">
    <div class="relative rounded-md  w-full m-auto flex-col flex w-full max-w-2xl text-on-bg-light">
      <div class="px-8 py-4">
        <form method="post" @submit.prevent="submitSearch" ref="searchform">

          <input v-model="search_value"
                 type="text" name="searchany" id="searchany" ref="searchany"
                 class="block w-full mt-3 px-4 py-3 text-xl border-8 border-dark-600 focus:outline-none"
                 style="box-shadow: 0 15px 35px hsla(0,0%,0%,.2);" placeholder="Go to table, column or row"
                 autocomplete="off"
          >
          <div class="relative" v-if="openAutocomplete">
            <ul class="bg-gray-200 absolute w-full border-8 border-dark-600 border-t-0 h-64 overflow-y-scroll">
              <li v-for="(table_name, index) in matches" :id="'match-' + index"
                  v-bind:class="{'active': isActive(index)}"
                  class="autocomplete-row">
                <a @click="clickedOnAutocomplete(index)">{{ table_name }}</a>
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
        current: -1
      }
    },

    created() {
      if(this.active_database) this.getTablesWithColumns();
      document.addEventListener('keydown', this.triggerKeyDown);
    },

    mounted() {
      this.$refs.searchany.focus();
    },

    beforeDestroy() {
      document.removeEventListener('keydown', this.triggerKeyDown);
    },

    watch: {

      active_database: function (value) {
        if (value) {
          if (!localStorage.getItem('tables_with_columns')) {
            this.getTablesWithColumns();
          } else {
            this.tables_with_columns = JSON.parse(localStorage.getItem('tables_with_columns'));
            this.tables              = Object.keys(this.tables_with_columns);
          }
        }
      },

      matches: function () {
        // when there are no matches, set the current back on the input (-1), this way fillautocomplete wont return undefined
        if (this.matches.length <= 0) {
          this.current = -1;
        }
      }
    },

    computed: {
      // Filtering the suggestion based on the input
      matches() {
        if (this.can_autocomplete_column) {
          let search_values = this.search_value.split(this.column_split_value);
          return this.tables_with_columns[search_values[0]].filter((column_name) => {
            return this.fussySearchMatch(search_values[1], column_name);
          });
        } else {
          return this.tables.filter((table_name) => {
            return this.fussySearchMatch(this.search_value, table_name);
          });
        }
      },

      openAutocomplete() {
        return this.search_value.length > 0 && this.matches.length !== 0;
      },

      entering_column() {
        return this.search_value.includes(".") || this.search_value.includes("^");
      },

      column_split_value() {
        if(this.search_value.includes(".")) {
          return '.';
        }
        else if(this.search_value.includes("^")) {
          return '^';
        }
        return '';
      },

      can_autocomplete_column() {
        if (!this.entering_column) return false;

        let search_values = this.search_value.split(this.column_split_value);
        if (this.tables_with_columns[search_values[0]] !== 'undefined') return true;
        return false;
      }
    },

    methods: {

      fussySearchMatch: function (search, text) {
        search = search.toUpperCase();
        text   = text.toUpperCase();

        let j = -1; // remembers position of last found character

        // consider each search character one at a time
        for (let i = 0; i < search.length; i++) {
          let l = search[i];
          if (l == ' ') continue;     // ignore spaces

          j = text.indexOf(l, j + 1);     // search for character & update position
          if (j == -1) return false;  // if it's not found, exclude this item
        }
        return true;
      },

      triggerKeyDown: function(evt) {
        if (evt.key === 'Escape') {
          this.close();
          evt.preventDefault();
        }
        else if (evt.key === 'ArrowUp') {
          this.up();
          evt.preventDefault();
        }
        else if (evt.key === 'ArrowDown') {
          this.down();
          evt.preventDefault();
        }
        else if (evt.key === 'ArrowRight') {
          if(this.current == -1) return; // do nothing when we are not on a autocomplete item
          this.fillautocomplete(this.current);
          evt.preventDefault();
        }
        else if (evt.key === 'Enter') {
          if(this.current == -1) return; // do nothing when we are not on a autocomplete item
          this.fillautocomplete(this.current);
          let vue = this;
          // set a tiny timeout, this way the user will see the value change from fillautocomplete
          setTimeout(function () {
            vue.submitSearch();
          }, 100);
          evt.preventDefault();
        }
      },

      close() {
        this.$emit('closesearchmodal');
      },

      getTablesWithColumns() {
        axios.get(this.endpoint + this.active_database).then(response => {
          this.tables_with_columns = response.data;
          this.tables              = Object.keys(this.tables_with_columns);
          localStorage.setItem('tables_with_columns', JSON.stringify(response.data));
        }).catch(error => {
          console.log('-----error-------');
          console.log(error);
        })
      },

      submitSearch() {
        if (this.search_value == '') return false;

        let has_id     = this.search_value.includes("#");
        let has_column = this.search_value.includes(".") || this.search_value.includes("^");

        if (has_id) {
          let search_values = this.search_value.split("#");
          let table_id      = this.normalizeValue(search_values[0]);
          this.$router.push({
            name: 'tablewithcolumnvalue',
            params: {tableid: table_id, column: 'id', value: search_values[1]}
          });
        } else if (has_column) {
          let has_column_value = this.search_value.includes("%");
          if (has_column_value) {
            let search_values = this.search_value.split(".");
            let data_values   = search_values[1].split("%");
            let table_id      = this.normalizeValue(search_values[0]);
            let column        = this.normalizeValue(data_values[0]);
            this.$router.push({
              name: 'tablewithcolumnvalue',
              params: {tableid: table_id, column: column, value: data_values[1]}
            });
          } else if(this.column_split_value == '.') {
            let search_values = this.search_value.split(".");
            let table_id      = this.normalizeValue(search_values[0]);
            let column        = this.normalizeValue(search_values[1]);
            this.$router.push({name: 'tablewithcolumn', params: {tableid: table_id, column: column}});
          }
          else if(this.column_split_value == '^') {
            let search_values = this.search_value.split("^");
            let table_id      = this.normalizeValue(search_values[0]);
            let column        = this.normalizeValue(search_values[1]);
            this.$router.push({name: 'tablegroupbycolumn', params: {tableid: table_id, querytype: 'groupby', column: column}});
          }
        } else {
          this.$router.push({name: 'table', params: {tableid: this.search_value}});
        }
        // when on the same route, router wont change component, so close the modal
        this.$emit('closemodal');
      },

      normalizeValue(value) {
        return value.toLowerCase();
      },

      // When up pressed while autocomplete is open
      up() {
        if (this.current >= 0) {
          this.current--;
        }
        else if (this.current == -1) {
          this.current = this.matches.length - 1;
        }
        if(this.current >= 0) {
          let element = document.getElementById('match-' + this.current);
          element.scrollIntoView({behavior: "auto", block: "center", inline: "center"});
        }
      },

      // When up pressed while autocomplete is open
      down() {
        if (this.current == this.matches.length -1) {
          this.current = -1;
        }
        else if (this.current <= this.matches.length - 1) {
          this.current++;
        }
        if(this.current >= 0) {
          let element = document.getElementById('match-' + this.current);
          element.scrollIntoView({behavior: "auto", block: "center", inline: "center"});
        }
      },

      fillautocomplete(index) {
        // if(this.matches[index] === 'undefined' || this.matches[index] == '') return;
        this.current = -1; // when we fill the autocomplete, set the pointer back to the input (-1)
        if (this.can_autocomplete_column) {
          let search_values = this.search_value.split(this.column_split_value);
          this.search_value = search_values[0] + this.column_split_value + this.matches[index];
        } else {
          this.search_value = this.matches[index];
        }
      },

      clickedOnAutocomplete(index) {
        this.fillautocomplete(index);
        let vue = this;
        // set a tiny timeout, this way the user will see the value change from fillautocomplete
        setTimeout(function () {
          vue.submitSearch();
        }, 100);
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
    @apply bg-highlight-100 border-t border-highlight-700;
  }

</style>

