<template>
  <div class="page-content-container">

    <spinner v-if="fetching_databases" delay="200"></spinner>

    <div v-if="!fetching_databases">

      <div class="flex justify-between items-center mb-3">
        <h2 class="text-xl">
          <span class="text-gray-500 text-lg">{{ databases.length }}</span>
          Databases
        </h2>

        <router-link :to="{ name: 'adddatabase'}" class="btn">
          Create database
        </router-link>
      </div>

      <table cellspacing="0" class="table-data" v-if="databases.length > 1">
        <thead>
        <tr>
          <th class="toggle-row">
            <label for="check-all-rows">
              <input type="checkbox" id='check-all-rows' class="hidden" @change="toggleAllRows($event)" />
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 fill-current">
                <circle cx="12" cy="12" r="10"></circle>
                <path
                    d="M10 14.59l6.3-6.3a1 1 0 0 1 1.4 1.42l-7 7a1 1 0 0 1-1.4 0l-3-3a1 1 0 0 1 1.4-1.42l2.3 2.3z"></path>
              </svg>
            </label>
          </th>
          <th v-for="(database_list_header, index) in database_list_headers" :key="index">
            <a @click="orderByColumn(database_list_header)" class="column-order-link">
              <span class="capitalize">{{ database_list_header }}</span>
              <svg viewBox="0 0 24 24" class="w-5 ml-2 fill-current"
                   v-if="order_by == database_list_header && order_direction == 'asc'">
                <path class="text-highlight-400"
                      d="M6 11V4a1 1 0 1 1 2 0v7h3a1 1 0 0 1 .7 1.7l-4 4a1 1 0 0 1-1.4 0l-4-4A1 1 0 0 1 3 11h3z"></path>
                <path class="text-highlight-700"
                      d="M21 21H8a1 1 0 0 1 0-2h13a1 1 0 0 1 0 2zm0-4h-9a1 1 0 0 1 0-2h9a1 1 0 0 1 0 2zm0-4h-5a1 1 0 0 1 0-2h5a1 1 0 0 1 0 2z"></path>
              </svg>
              <svg viewBox="0 0 24 24" class="w-5 ml-2 fill-current"
                   v-if="order_by == database_list_header && order_direction == 'desc'">
                <path class="text-highlight-400"
                      d="M18 13v7a1 1 0 0 1-2 0v-7h-3a1 1 0 0 1-.7-1.7l4-4a1 1 0 0 1 1.4 0l4 4A1 1 0 0 1 21 13h-3z"></path>
                <path class="text-highlight-700"
                      d="M3 3h13a1 1 0 0 1 0 2H3a1 1 0 1 1 0-2zm0 4h9a1 1 0 0 1 0 2H3a1 1 0 1 1 0-2zm0 4h5a1 1 0 0 1 0 2H3a1 1 0 0 1 0-2z"></path>
              </svg>
            </a>
          </th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(database, database_index) in ordered_databases" :key="database.name">
          <td class="toggle-row">
            <label>
              <input type="checkbox" class="hidden" :value="database_index" v-model="selected_rows" />
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 fill-current">
                <circle cx="12" cy="12" r="10"></circle>
                <path
                    d="M10 14.59l6.3-6.3a1 1 0 0 1 1.4 1.42l-7 7a1 1 0 0 1-1.4 0l-3-3a1 1 0 0 1 1.4-1.42l2.3 2.3z"></path>
              </svg>
            </label>
          </td>
          <td class="table-data-row" v-for="(database_list_header, index) in database_list_headers" :key="index"
              @click="$event.target.focus()" tabindex="1"
              :class="{ ' sticky-first-row-cell' : (index == 0)}">
            <router-link v-if="database_list_header == 'name'"
                         :to="{ name: 'database', params: { database: database[database_list_header] } }"
                         class="inline-block whitespace-normal">
              {{ database[database_list_header] }}
            </router-link>
            <span v-else>{{ database[database_list_header] }}</span>
          </td>
        </tr>
        </tbody>
      </table>

      <div class="row-actions sticky bottom-0 left-0 z-30 w-full"
           v-if="databases.length > 1 && selected_rows.length > 0">

        <div class="py-3 px-3  flex items-center bg-dark-600 text-white">

          <div class="font-bold mr-6">
            {{ selected_rows.length }} databases
          </div>

          <a class="rows-action" v-if="selected_rows.length == 1" @click="editDatabase()">
            <span>Edit</span>
          </a>

          <a class="rows-action">
            <span>Drop</span>
          </a>

        </div>
      </div>

    </div>
  </div>
</template>

<script>

import Spinner from "@/components/Spinner";

export default {
  name: 'DatabaseList',

  data() {
    return {
      selected_rows: [],
      order_by: 'name',
      order_direction: 'asc',
    }
  },

  components: {
    Spinner,
  },

  created() {
    if(this.$store.getters["databases/hasDatabases"] === false) {
      this.$store.dispatch('databases/get');
    }
  },

  computed: {
    database_list_headers: function () {
      return ["name", "collation"];
    },

    databases() {
      return this.$store.getters["databases/databases"];
    },

    fetching_databases() {
      return this.$store.getters["databases/isLoading"];
    },

    ordered_databases() {
      if (this.databases.length == 0) return [];

      // create a clone of databases, we dont want to sort the vuex state
      let ordered_databases = JSON.parse(JSON.stringify(this.databases));

      let reverse = this.order_direction == 'asc' ? 1 : -1;

      let vue_instance = this;

      ordered_databases.sort(function (a, b) {
        if (a[vue_instance.order_by] == b[vue_instance.order_by]) return 0;
        return reverse * ((a[vue_instance.order_by] > b[vue_instance.order_by]) - (b[vue_instance.order_by] > a[vue_instance.order_by]));
      });

      return ordered_databases;
    }

  },

  methods: {

    toggleAllRows($event) {
      if ($event.target.checked) {
        this.selected_rows = [];
        for (let row_index in Object.keys(this.databases)) {
          this.selected_rows.push(row_index);
        }
      } else {
        this.selected_rows = [];
      }
    },

    orderByColumn(column) {
      this.order_by        = column;
      this.order_direction = (this.order_direction == '' || this.order_direction == 'desc') ? 'asc' : 'desc';
      // undo selection because of indexes
      this.selected_rows = [];
    },

    editDatabase() {
      let selected_database_name = this.ordered_databases[this.selected_rows[0]].name;

      this.$router.push({
        name: 'editdatabase',
        params: { database: selected_database_name }
      });
    }

  },
}
</script>
