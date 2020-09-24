<template>

  <div class="modal-container" :class="{ 'open' : modalisopen }">
    <div class="modal-content modal-search">
      <div class="px-8 py-4">
        <form method="post" @submit.prevent="submitSearch" ref="searchform">

          <div class="bg-dark-600 text-white px-4 py-3 border-b border-light-100" v-if="show_help_text">
            <h2 class="text-xl mb-2">Search options</h2>
            <div class="flex">
              <div class="w-48">table</div>
              <div class="text-light-300">Go to table</div>
            </div>
            <div class="flex">
              <div class="w-48">table.column</div>
              <div class="text-light-300">Go to table and focus on column</div>
            </div>
            <div class="flex">
              <div class="w-48">table.column%value</div>
              <div class="text-light-300">Find rows where column is LIKE value</div>
            </div>
            <div class="flex">
              <div class="w-48">table.column=value</div>
              <div class="text-light-300">Find rows where column is value</div>
            </div>
            <div class="flex">
              <div class="w-48">table#id</div>
              <div class="text-light-300">Find row where the primary key is id</div>
            </div>
            <div class="flex">
              <div class="w-48">table|column</div>
              <div class="text-light-300">Find all values of a column (group by column)</div>
            </div>

            <p class="mt-2">Use the arrow keys to navigate between autocomplete items. Use right arrow or Enter to fill autocomplete. With fuzzy search you can easily filter down results, eg: ppr &gt; product_price.</p>
          </div>

          <div class="input-search">
            <input v-model="search_value" type="text" name="searchany" id="searchany" ref="searchany"
                   placeholder="Go to table, column or row" autocomplete="off">

            <a @click="show_help_text = !show_help_text" class="text-gray-500 hover:text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-8 absolute right-0 top-0 mt-3 fill-current">
                <path
                      d="M12 19.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm1-5.5a1 1 0 0 1-2 0v-1.41a1 1 0 0 1 .55-.9L14 10.5C14.64 10.08 15 9.53 15 9c0-1.03-1.3-2-3-2-1.35 0-2.49.62-2.87 1.43a1 1 0 0 1-1.8-.86C8.05 6.01 9.92 5 12 5c2.7 0 5 1.72 5 4 0 1.3-.76 2.46-2.05 3.24L13 13.2V14z"></path>
              </svg>
            </a>

          </div>

          <div class="autocomplete-container" v-if="openAutocomplete">
            <ul class="autocomplete-list scroll-bar">
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

  export default {
    name: 'SearchModal',
    props: ['modalisopen'],
    data() {
      return {
        search_value: '',
        current: -1,
        show_help_text: false
      }
    },

    created() {
      document.addEventListener('keydown', this.triggerKeyDown);
    },

    mounted() {
      this.$refs.searchany.focus();
    },

    beforeDestroy() {
      document.removeEventListener('keydown', this.triggerKeyDown);
    },

    watch: {
      matches: function () {
        // when there are no matches, set the current back on the input (-1), this way fillautocomplete wont return undefined
        if (this.matches.length <= 0) {
          this.current = -1;
        }
      }
    },

    computed: {
      active_database() {
        return this.$store.state.activeDatabase;
      },

      // Filtering the suggestion based on the input
      matches() {

        if (this.search_value == '') {
          return this.searches;
        }

        let search_results = [];
        if (this.can_autocomplete_column) {
          let search_values = this.search_value.split(this.column_split_value);
          search_results    = this.tables_with_columns[search_values[0]].filter((column_name) => {
            return this.fussySearchMatch(search_values[1], column_name);
          });
        } else {
          search_results = this.tables.filter((table_name) => {
            return this.fussySearchMatch(this.search_value, table_name);
          });
        }

        // sort on length, shortest first, because most of the time the shortest table name is the one you want
        return search_results.sort((value_1, value_2) => {
          return value_1.length - value_2.length;
        });
      },

      openAutocomplete() {
        // if the input is empty and there are searches from the history
        if (this.search_value == '' && this.searches.length > 0) {
          return true;
        }
        if (this.search_value.length > 0 && this.matches.length !== 0) {
          return true;
        }
        return false;
      },

      entering_column() {
        return this.search_value.includes(".") || this.search_value.includes("|");
      },

      column_split_value() {
        if (this.search_value.includes(".")) {
          return '.';
        } else if (this.search_value.includes("|")) {
          return '|';
        }
        return '';
      },

      can_autocomplete_column() {
        if (!this.entering_column) return false;

        let search_values = this.search_value.split(this.column_split_value);
        if (this.tables_with_columns[search_values[0]] !== 'undefined') return true;
        return false;
      },

      tables_with_columns() {
        return this.$store.getters["tables/tablesWithColumns"];
      },

      tables() {
        return Object.keys(this.tables_with_columns);
      },

      searches() {
        return this.$store.getters["searches/searches"];
      },

      rows_per_page() {
        return this.$store.getters["settings/getSetting"]('default_rows_per_page');
      },
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

      triggerKeyDown: function (evt) {
        if (evt.key === 'Escape') {
          this.close();
          evt.preventDefault();
        } else if (evt.key === 'ArrowUp') {
          this.up();
          evt.preventDefault();
        } else if (evt.key === 'ArrowDown' || evt.key === 'Tab') {
          this.down();
          evt.preventDefault();
        } else if (evt.key === 'ArrowRight') {
          if (this.current == -1) return; // do nothing when we are not on a autocomplete item
          this.fillautocomplete(this.current);
          evt.preventDefault();
        } else if (evt.key === 'Enter') {
          if (this.current == -1) return; // do nothing when we are not on a autocomplete item
          this.fillautocomplete(this.current);
          evt.preventDefault();
        }
      },

      close() {
        this.$emit('closesearchmodal');
      },

      submitSearch() {
        if (this.search_value == '') return false;

        let has_id             = this.search_value.includes("#");
        let has_column         = this.search_value.includes(".") || this.search_value.includes("|");
        let column_value       = '';
        let value_compare_type = '';

        let table_id = '';
        let column   = '';
        let row_id   = '';

        if (has_id) {
          let search_values = this.search_value.split("#");
          table_id          = search_values[0];
          column            = 'id';
          row_id            = search_values[1];
        } else if (has_column) {
          if (this.search_value.includes("%")) {
            let search_values  = this.search_value.split(".");
            let data_values    = search_values[1].split("%");
            table_id           = search_values[0];
            column             = data_values[0];
            column_value       = data_values[1];
            value_compare_type = 'like';

          } else if (this.search_value.includes("=")) {
            let search_values  = this.search_value.split(".");
            let data_values    = search_values[1].split("=");
            table_id           = search_values[0];
            column             = data_values[0];
            column_value       = data_values[1];
            value_compare_type = 'is';

          } else if (this.column_split_value == '.') {
            let search_values = this.search_value.split(".");
            table_id          = search_values[0];
            column            = search_values[1];

          } else if (this.column_split_value == '|') {
            let search_values = this.search_value.split("|");
            table_id          = search_values[0];
            column            = search_values[1];
          }
        } else {
          table_id = this.search_value;
        }

        // cannot go to a table that does not exists, skip
        if (this.tables.includes(table_id) === false) {
          // if the table is not found, but there are autocomplete items, just replace the value with the first matching autocomplete table item
          // very handy when doing fuzzy search: orgadd > ENTER > organisation_address
          if (this.matches.length > 0) {
            this.search_value = this.matches[0];
            return false;
          }
          // briefly highlight the text red, to show the user the input is false
          this.$refs.searchany.classList.add('text-red-700');
          let vue = this;
          setTimeout(function () {
            vue.$refs.searchany.classList.remove('text-red-700');
          }, 300);
          return false;
        }

        // cannot go to a column that does not exists, skip
        if (has_id == '' && column != '' && this.tables_with_columns[table_id].includes(column) === false) {
          // if the table is not found, but there are autocomplete items, just replace the value with the first matching autocomplete table item
          // very handy when doing fuzzy search: orgadd > ENTER > organisation_address
          if (this.matches.length > 0) {
            this.search_value = table_id + this.column_split_value + this.matches[0];
            return false;
          }
          // briefly highlight the text red, to show the user the input is false
          this.$refs.searchany.classList.add('text-red-700');
          let vue = this;
          setTimeout(function () {
            vue.$refs.searchany.classList.remove('text-red-700');
          }, 300);
          return false;
        }

        this.$store.commit("searches/ADD_SEARCH", this.search_value);

        // perform the normalize (tolowercase) here, else the .include in the code above won't work, because it is case sensitive
        table_id = this.normalizeValue(table_id);
        // go to table where primary key is value: "user#2"
        if (has_id) {
          this.$router.push({
            name: 'tablewithcolumnvalue',
            params: {database: this.active_database, tableid: table_id, column: 'primarykey', comparetype: 'is', value: row_id}
          });

        } else if (has_column) {
          column = this.normalizeValue(column);
          // go to table where column equals value: "user.organisation_id=2" or "user.email%google"
          if (value_compare_type === 'like' || value_compare_type === 'is') {
            this.$router.push({
              name: 'tablewithcolumnvalue',
              params: {database: this.active_database, tableid: table_id, column: column, comparetype: value_compare_type, value: column_value}
            });

            // go to table and center on column: "user.password"
          } else if (this.column_split_value == '.') {
            this.$router.push({name: 'tablewithcolumn', params: {database: this.active_database, tableid: table_id, column: column}});

            // query for the group by of a column: "user|sex"
          } else if (this.column_split_value == '|') {
            let flash_query = "SELECT COUNT(*) as amount, " + column + " FROM " + table_id + " GROUP BY " + column + " LIMIT " + this.rows_per_page;
            this.$store.commit("queryedit/ADD_QUERY_EDIT", flash_query);
            this.$store.commit("queryedit/ACTIVATE_DIRECT_EXECUTION");
            if(this.$route.name == 'query') {
              // if we are already on the query page, just refresh the page
              this.$store.state.reloadMainComponentKey += 1;
            }
            else {
              this.$router.push({name: 'query'});
            }
          }
          // go to table: "user"
        } else {
          this.$router.push({name: 'table', params: {database: this.active_database, tableid: this.search_value}});
        }
        // when on the same route, router wont change component, so close the modal
        this.close();
      },

      normalizeValue(value) {
        return value.toLowerCase();
      },

      // When up pressed while autocomplete is open
      up() {
        if (this.current >= 0) {
          this.current--;
        } else if (this.current == -1) {
          this.current = this.matches.length - 1;
        }
        if (this.current >= 0) {
          let element = document.getElementById('match-' + this.current);
          element.scrollIntoView({behavior: "auto", block: "center", inline: "center"});
        }
      },

      // When up pressed while autocomplete is open
      down() {
        if (this.current == this.matches.length - 1) {
          this.current = -1;
        } else if (this.current <= this.matches.length - 1) {
          this.current++;
        }
        if (this.current >= 0) {
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
