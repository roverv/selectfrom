<template>
  <div >

    <table-nav :tableid="tableid"></table-nav>

    <a @click="swapTableDisplay">Toggle</a>

    <div class="w-full flex items-start">


      <div class="relative w-full">

        <table cellspacing="0" class="flex-grow w-full bg-light-100 relative swap-table"
               style="box-shadow: 0 2px 3px 2px rgba(0,0,0,.03);"
               v-if="tabledata.length > 1 && table_display_rotated">
          <tr v-for="column_header in columns">
            <th :name="column_header.Field" :id="column_header.Field | lowercase"
                class="sticky left-0 z-20 bg-dark-400 text-gray-200 px-2"
                :class="{ ' highlight' : (column_header.Field.toLowerCase() == column)}">
              {{ column_header.Field }}
            </th>
            <td class="whitespace-pre px-1 py-1" v-for="row in tabledata"
            >{{ row[column_header.Field] }}
            </td>
          </tr>
        </table>
        <br>

        <table cellspacing="0" class="flex-grow w-full bg-light-100 relative"
               style="box-shadow: 0 2px 3px 2px rgba(0,0,0,.03);"
               v-if="tabledata.length > 1" v-show="!table_display_rotated">
          <thead class="bg-dark-400 text-gray-200 text-left">
          <tr class="font-normal">
            <th class="sticky top-0 bg-dark-400 z-20 text-gray-200 py-3">
              <label for="all-page">
                <input type="checkbox" id='all-page' class="hidden" />
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 fill-current">
                  <circle cx="12" cy="12" r="10"></circle>
                  <path
                    d="M10 14.59l6.3-6.3a1 1 0 0 1 1.4 1.42l-7 7a1 1 0 0 1-1.4 0l-3-3a1 1 0 0 1 1.4-1.42l2.3 2.3z"></path>
                </svg>
              </label>
            </th>
            <th :name="column_header.Field" :id="column_header.Field | lowercase"
                class="sticky top-0 bg-dark-400 z-20 text-gray-200 px-2" v-for="column_header in columns"
                :class="{ ' highlight' : (column_header.Field.toLowerCase() == column)}">
              <a @click="orderByColumn(column_header.Field)" class="flex items-center">
                <span>{{ column_header.Field }}</span>
                <svg viewBox="0 0 24 24" class="w-5 ml-2 fill-current"
                     v-if="order_by == column_header.Field && order_direction == 'asc'">
                  <path class="text-highlight-400"
                        d="M6 11V4a1 1 0 1 1 2 0v7h3a1 1 0 0 1 .7 1.7l-4 4a1 1 0 0 1-1.4 0l-4-4A1 1 0 0 1 3 11h3z"></path>
                  <path class="text-highlight-700"
                        d="M21 21H8a1 1 0 0 1 0-2h13a1 1 0 0 1 0 2zm0-4h-9a1 1 0 0 1 0-2h9a1 1 0 0 1 0 2zm0-4h-5a1 1 0 0 1 0-2h5a1 1 0 0 1 0 2z"></path>
                </svg>
                <svg viewBox="0 0 24 24" class="w-5 ml-2 fill-current"
                     v-if="order_by == column_header.Field && order_direction == 'desc'">
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
          <tr v-for="(row, row_index) in tabledata">
            <td class="sticky left-0 z-10 w-12 text-center ">
              <label>
                <input type="checkbox" class="hidden" />
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 fill-current">
                  <circle cx="12" cy="12" r="10"></circle>
                  <path
                    d="M10 14.59l6.3-6.3a1 1 0 0 1 1.4 1.42l-7 7a1 1 0 0 1-1.4 0l-3-3a1 1 0 0 1 1.4-1.42l2.3 2.3z"></path>
                </svg>
              </label>
            </td>
            <td class="table-data-row" v-for="(column_name, index) in columns" @dblclick="toggleRowSidebar(row_index)"
                :class="{ ' sticky id-field-offset z-10' : (index == 0)}">
              <span v-if="shouldTruncateField(column_name.Type)" :title="row[column_name.Field]">{{ row[column_name.Field] | truncate(20) }}</span>
              <span v-else>{{ row[column_name.Field] }}</span>
            </td>
          </tr>
          </tbody>
        </table>

        <div class="row-actions sticky bottom-0 left-0 z-30 w-full hidden" v-if="tabledata.length > 1">

          <div class="py-3 px-2  flex items-center bg-dark-600 text-white">

            <div class="font-bold mr-6">
              5 rows
            </div>

            <a class="rows-action" href="">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 mr-2 fill-current text-gray-600">
                <path class="text-gray-600"
                      d="M4 14a1 1 0 0 1 .3-.7l11-11a1 1 0 0 1 1.4 0l3 3a1 1 0 0 1 0 1.4l-11 11a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-3z" />
                <rect width="20" height="2" x="2" y="20" class="text-gray-500" rx="1" />
              </svg>
              <span>Edit</span>
            </a>

            <a class="rows-action" href="">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 mr-2 fill-current text-gray-600">
                <rect width="14" height="14" x="3" y="3" class="text-gray-500" rx="2" />
                <rect width="14" height="14" x="7" y="7" class="text-gray-600" rx="2" />
              </svg>
              <span>Duplicate</span>
            </a>

            <a class="rows-action" href="">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 mr-2 fill-current text-gray-600">
                <path class="text-gray-600"
                      d="M5 5h14l-.89 15.12a2 2 0 0 1-2 1.88H7.9a2 2 0 0 1-2-1.88L5 5zm5 5a1 1 0 0 0-1 1v6a1 1 0 0 0 2 0v-6a1 1 0 0 0-1-1zm4 0a1 1 0 0 0-1 1v6a1 1 0 0 0 2 0v-6a1 1 0 0 0-1-1z" />
                <path class="text-gray-500"
                      d="M8.59 4l1.7-1.7A1 1 0 0 1 11 2h2a1 1 0 0 1 .7.3L15.42 4H19a1 1 0 0 1 0 2H5a1 1 0 1 1 0-2h3.59z" />
              </svg>
              <span>Delete</span>
            </a>

            <a class="rows-action" href="">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 mr-2 fill-current text-gray-600">
                <path class="text-gray-600"
                      d="M6 2h6v6c0 1.1.9 2 2 2h6v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2zm2 11a1 1 0 0 0 0 2h8a1 1 0 0 0 0-2H8zm0 4a1 1 0 0 0 0 2h4a1 1 0 0 0 0-2H8z" />
                <polygon class="text-gray-500" points="14 2 20 8 14 8" />
              </svg>
              <span>Export</span>
            </a>

          </div>
        </div>

      </div>

    </div>

    <div class="flex w-full bg-white p-3" v-if="tabledata.length == 1">
      <div class="w-1/2" v-for="columns_half in columns_halved">
        <div class="row-data-field" v-for="column in columns_half">
          <div class="w-48 text-right"><strong>{{ column.Field }}</strong></div>
          <div class="ml-4">{{ tabledata[0][column.Field] }}</div>
        </div>
      </div>
    </div>
    <br>

    <row-sidebar :sidebarisopen="sidebarisopen" v-on:closeRowSidebar="closeRowSidebar" :rowdata="sidebar_row_data"></row-sidebar>

  </div>

</template>

<script>

  import axios from 'axios'
  import TableNav from '@/components/TableNav.vue'
  import RowSidebar from "./RowSidebar";

  export default {
    name: 'TableData',
    props: ['tableid', 'column', 'value', 'active_database'],
    data() {
      return {
        tabledata: [],
        columns: [],
        endpoint: 'http://localhost/rove/api/tabledata.php?db=',
        table_display_rotated: false,
        order_by: '',
        order_direction: '',
        sidebarisopen: false,
        sidebar_row_data: {},
      }
    },

    components: {
      RowSidebar,
      TableNav
    },

    mounted() {
      console.log(this.tableid);
      this.getAllPosts();
    },

    computed: {
      columns_halved: function () {
        let halfwayThrough  = Math.floor(this.columns.length / 2)
        let arrayFirstHalf  = this.columns.slice(0, halfwayThrough);
        let arraySecondHalf = this.columns.slice(halfwayThrough, this.columns.length);
        return [arrayFirstHalf, arraySecondHalf];
      }
    },

    watch: {
      // force update on route change
      '$route.params': function () {
        this.$nextTick();
      }
    },

    filters: {
      lowercase: function (string) {
        return string.toLowerCase();
      },

      truncate: function (value, limit) {
        if (!value) return value;
        if (value.length > limit) {
          value = value.replace(/(\r\n|\n|\r)/gm, ""); // remove line breaks
          value = value.substring(0, limit) + '...';
        }

        return value
      }
    },

    methods: {

      shouldTruncateField(column_type) {
        if (column_type.includes('text') || column_type.includes('varchar') || column_type.includes('blob')) {
          return true;
        }
        return false;
      },

      getAllPosts() {

        let api_url = '';
        if (this.tableid) {
          api_url = this.endpoint + this.active_database + '&tablename=' + this.tableid;
        }
        if (this.column && this.value) {
          api_url += '&column=' + this.column + '&value=' + this.value;
        }
        if (this.order_by && this.order_direction) {
          api_url += '&orderby=' + this.order_by + '&orderdirection=' + this.order_direction;
        }

        let vue_instance = this;

        axios.get(api_url).then(response => {
          this.tabledata = response.data.data;
          this.columns   = response.data.columns;
          this.$nextTick().then(function () {
            // DOM updated
            if (vue_instance.column) {
              console.log(vue_instance.column);
              vue_instance.gotocolumn(vue_instance.column);
            }
          });

        }).catch(error => {
          console.log('-----error-------');
          console.log(error);
        })
      },

      gotocolumn(field_id) {
        var element = document.getElementById(field_id);
        if (element) {
          element.scrollIntoView({behavior: "auto", block: "center", inline: "center"});
        }
      },

      swapTableDisplay() {
        this.table_display_rotated = !this.table_display_rotated;
      },

      orderByColumn(column) {
        this.order_by        = column;
        this.order_direction = (this.order_direction == '' || this.order_direction == 'desc') ? 'asc' : 'desc';
        this.getAllPosts();
      },

      closeRowSidebar() {
        this.sidebarisopen = false;
      },

      toggleRowSidebar(row_index) {
        this.sidebar_row_data = this.tabledata[row_index];
        this.sidebarisopen = true;
      }

    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

  table tbody td {
    @apply border-b border-light-300;
  }

  tbody tr:hover td {
    @apply bg-light-200;
  }

  tbody td:hover {
    @apply bg-highlight-400 !important;
  }

  tbody td:first-child {
    width:      2rem;
    text-align: center;
  }

  .id-field-offset {
    left: 24px;
  }

  .row-data-field {
    @apply flex w-full;
    border-bottom: 1px solid #edf2f7;
  }

  .row-data-field:hover {
    @apply bg-light-200;
  }

  .highlight {
    @apply bg-highlight-700 text-white;
  }

  .table-data-row {
    @apply whitespace-pre px-1;
    padding-top:    1px;
    padding-bottom: 1px;
  }

  input[type="checkbox"] + svg circle {
    @apply hidden;
  }

  input[type="checkbox"] + svg path {
    @apply text-gray-400;
  }

  input[type="checkbox"]:checked + svg circle {
    @apply inline-block text-highlight-700;
  }

  input[type="checkbox"]:checked + svg path {
    @apply text-gray-100;
  }

  tbody td:first-child {
    width:      2rem;
    text-align: center;
  }

  .rows-action {
    @apply mx-2 inline-flex rounded-lg py-1 px-2 items-center;
  }

  .rows-action:hover {
    @apply bg-light-100;
  }


</style>
