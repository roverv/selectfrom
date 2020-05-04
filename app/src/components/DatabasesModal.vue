<template>

  <div class="modal-container" :class="{ 'open' : modalisopen }">
    <div class="modal-content modal-search">
      <div class="px-8 py-4">
        <form method="post" @submit.prevent="submitSearch" ref="searchform">

          <input v-model="search_value"
                 type="text" name="searchany" id="searchany" ref="searchany"
                 class="input-search" placeholder="Go to database" autocomplete="off">
          <div class="autocomplete-container" v-if="openAutocomplete">
            <ul class="autocomplete-list">
              <li v-for="(database_name, index) in matches" :id="'match-' + index"
                  v-bind:class="{'active': isActive(index)}"
                  class="autocomplete-row">
                <a @click="clickedOnAutocomplete(index)">{{ database_name }}</a>
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
    name: 'DatabaseModal',
    props: ['modalisopen'],
    data() {
      return {
        search_value: '',
        list_databases: [],
        current: -1
      }
    },

    created() {
      document.addEventListener('keydown', this.triggerKeyDown);
    },

    mounted() {
      this.list_databases = this.databases;
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
      // Filtering the suggestion based on the input
      matches() {
        return this.list_databases.filter((database_name) => {
          return this.fussySearchMatch(this.search_value, database_name);
        });
      },

      databases() {
        return this.$store.getters["databases/databaseNames"];
      },

      openAutocomplete() {
        return this.search_value.length > 0 && this.matches.length !== 0;
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
        } else if (evt.key === 'ArrowDown') {
          this.down();
          evt.preventDefault();
        } else if (evt.key === 'ArrowRight') {
          if (this.current == -1) return; // do nothing when we are not on a autocomplete item
          this.fillautocomplete(this.current);
          evt.preventDefault();
        } else if (evt.key === 'Enter') {
          if (this.current == -1) return; // do nothing when we are not on a autocomplete item
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
        this.$emit('closedatabasesmodal');
      },

      submitSearch() {
        if (this.search_value == '') return false;

        // cannot go to a database that does not exists, skip
        if (this.databases.includes(this.search_value) === false) {

          // if the table is not found, but there are autocomplete items, just replace the value with the first matching autocomplete item
          // very handy when doing fuzzy search: orgadd > ENTER > organisation_address
          if(this.matches.length > 0) {
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

        this.$router.push({name: 'database', params: {'database': this.search_value}});
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
        this.current      = -1; // when we fill the autocomplete, set the pointer back to the input (-1)
        this.search_value = this.matches[index];
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

