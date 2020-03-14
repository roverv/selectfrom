<template>
  <div>

    <table-nav :tableid="tableid"></table-nav>

    <div class="w-full flex items-start">

      <div class="relative w-full">

        <table cellspacing="0" class="table-data" v-if="tabledata.length > 1" ref="datatable"
               @keydown.right.prevent="focusCellNext($event, 1)" @keydown.left.prevent="focusCellPrevious($event, 1)"
               @keydown.up.prevent="focusRowUp($event, 1)" @keydown.down.prevent="focusRowDown($event, 1)"
               @keydown.shift.right.prevent="focusCellNext($event,5)"
               @keydown.shift.left.prevent="focusCellPrevious($event,5)"
               @keydown.shift.up.prevent="focusRowUp($event,5)" @keydown.shift.down.prevent="focusRowDown($event,5)"
               @keydown.esc="unfocusDatatable()">
          <thead>
          <tr>
            <th class="toggle-row">
              <label for="all-page">
                <input type="checkbox" id='all-page' class="hidden" />
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 fill-current">
                  <circle cx="12" cy="12" r="10"></circle>
                  <path
                    d="M10 14.59l6.3-6.3a1 1 0 0 1 1.4 1.42l-7 7a1 1 0 0 1-1.4 0l-3-3a1 1 0 0 1 1.4-1.42l2.3 2.3z"></path>
                </svg>
              </label>
            </th>
            <th :name="column_header.Field" :id="column_header.Field | lowercase" v-for="column_header in columns"
                :class="{ ' highlight-column' : (column_header.Field.toLowerCase() == column)}">
              <a @click="orderByColumn(column_header.Field)" class="column-order-link">
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
            <td class="toggle-row">
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
                @click="$event.target.focus()" tabindex="1"
                :class="{ ' sticky-first-row-cell' : (index == 0)}">
              <span v-if="shouldTruncateField(column_name.Type)" :title="row[column_name.Field]">{{ row[column_name.Field] | truncate(20) }}</span>
              <span v-else-if="row[column_name.Field] === null" class="null-value"><i>NULL</i></span>
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

    <div class="flex w-full" v-if="tabledata.length == 1">
      <div class="w-1/2 px-2" v-for="columns_half in columns_halved">

        <div class="row-data-field w-full" v-for="column in columns_half">

          <div class=" header bg-dark-400 flex items-center w-2/5 pl-3 flex-shrink-0"
               style="padding-top: 2px; padding-bottom: 2px;">
            <div class="text-gray-300 mr-6">{{ column.Field }}</div>

          </div>

          <div class="data bg-light-100 border-b border-light-300 flex-grow flex items-center pr-3 py-1 justify-end"
               style="padding-top: 2px; padding-bottom: 2px;">

            <div class=" ml-6">
              <span v-if="tabledata[0][column.Field] === null" class="null-value"><i>NULL</i></span>
              <span v-else>{{ tabledata[0][column.Field] }}</span>
            </div>
          </div>

        </div>

      </div>
    </div>
    <br>

    <div class="flex" v-if="showLoadMoreDataButtons()">
      <button class="btn mr-3" @click="loadMoreRows()">Load 90 more rows</button>
      <button class="btn mr-3" @click="loadAllRows()">
        Load all rows ({{ total_amount_rows }})
      </button>
    </div>

    <row-sidebar :sidebarisopen="sidebarisopen" v-on:closeRowSidebar="closeRowSidebar"
                 :rowdata="sidebar_row_data" :columndata="sidebar_column_data"></row-sidebar>

  </div>

</template>

<script>

  import axios from 'axios'
  import TableNav from '@/components/TableNav.vue'
  import RowSidebar from "./RowSidebar";

  export default {
    name: 'TableData',
    props: ['tableid', 'column', 'value', 'querytype', 'active_database'],
    data() {
      return {
        tabledata: [],
        columns: [],
        total_amount_rows: 0,
        offset_rows: 0,
        endpoint_table_data: 'http://localhost/rove/api/tabledata.php?db=',
        endpoint_count_rows: 'http://localhost/rove/api/countrows.php?db=',
        order_by: '',
        order_direction: '',
        sidebarisopen: false,
        sidebar_row_data: [],
        sidebar_column_data: [],
        is_fetching_data: false, // true when fetching data through ajax
      }
    },

    components: {
      RowSidebar,
      TableNav
    },

    mounted() {
      this.getTableData();
      this.getAmountRows();
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

      focusCellNext($event, step) {
        let cell_index = Array.from($event.target.parentNode.children).indexOf($event.target);
        let element = $event.target.parentElement.getElementsByTagName('td')[cell_index + step];
        if (element) {
          element.focus();
          element.scrollIntoView({behavior: "auto", block: "center", inline: "center"});
        }
      },

      focusCellPrevious($event, step) {
        let cell_index = Array.from($event.target.parentNode.children).indexOf($event.target);
        let cell_offset = (cell_index - step <= 0) ? 0 : cell_index - step;
        let element = $event.target.parentElement.getElementsByTagName('td')[cell_offset];
        if (element) {
          element.focus();
          element.scrollIntoView({behavior: "auto", block: "center", inline: "center"});
        }
      },

      focusRowUp($event, step) {
        let cell_index = Array.from($event.target.parentNode.children).indexOf($event.target);
        let row_index = Array.from($event.target.parentNode.parentNode.children).indexOf($event.target.parentNode);
        let row_offset = (row_index - step <= 0) ? 0 : row_index - step;
        console.log(row_offset);
        let element = $event.target.parentElement.parentElement.getElementsByTagName('tr')[row_offset].getElementsByTagName('td')[cell_index];
        if (element) {
          element.focus();
          element.scrollIntoView({behavior: "auto", block: "center", inline: "center"});
        }
      },

      focusRowDown($event, step) {
        let cell_index = Array.from($event.target.parentNode.children).indexOf($event.target);
        let row_index = Array.from($event.target.parentNode.parentNode.children).indexOf($event.target.parentNode);
        let row_offset = row_index + step;
        if(row_offset >= $event.target.parentNode.parentNode.children.length -1) {
          row_offset = $event.target.parentNode.parentNode.children.length -1;
          // retrieve the next batch of rows, but only if not already fetching (prevents doing multiple ajax requests at the same time)
          if(this.showLoadMoreDataButtons() && this.is_fetching_data === false) {
            this.loadMoreRows();
          }
        }
        let element = $event.target.parentElement.parentElement.getElementsByTagName('tr')[row_offset].getElementsByTagName('td')[cell_index];
        if (element) {
          element.focus();
          element.scrollIntoView({behavior: "auto", block: "center", inline: "center"});
        }
      },


      shouldTruncateField(column_type) {
        if (column_type.includes('text') || column_type.includes('varchar') || column_type.includes('blob')) {
          return true;
        }
        return false;
      },

      getTableData() {

        let api_url = '';
        if (this.tableid) {
          api_url = this.endpoint_table_data + this.active_database + '&tablename=' + this.tableid;
        }
        if (this.column && this.value) {
          api_url += '&column=' + this.column + '&value=' + this.value;
        } else if (this.column && this.querytype) {
          api_url += '&column=' + this.column + '&type=' + this.querytype;
        }
        if (this.order_by && this.order_direction) {
          api_url += '&orderby=' + this.order_by + '&orderdirection=' + this.order_direction;
        }

        let vue_instance = this;

        this.is_fetching_data = true;

        axios.get(api_url).then(response => {
          this.tabledata = response.data.data;
          this.columns   = response.data.columns;
          this.$nextTick().then(function () {
            // DOM updated
            if (vue_instance.column) {
              console.log(vue_instance.column);
              vue_instance.gotocolumn(vue_instance.column);
            }

            // set focus on first cell, for cell navigation with keyboard
            vue_instance.$refs['datatable'].getElementsByTagName('tbody')[0].rows[0].cells[1].focus();

            vue_instance.is_fetching_data = false;
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

      orderByColumn(column) {
        this.order_by        = column;
        this.order_direction = (this.order_direction == '' || this.order_direction == 'desc') ? 'asc' : 'desc';
        this.getTableData();
      },

      closeRowSidebar() {
        this.sidebarisopen = false;
      },

      toggleRowSidebar(row_index) {
        let row_num_keys    = [];
        let column_num_keys = [];
        for (var key in this.tabledata[row_index]) {
          row_num_keys.push(this.tabledata[row_index][key]);
          column_num_keys.push(key);
        }
        this.sidebar_row_data    = row_num_keys;
        this.sidebar_column_data = column_num_keys;
        this.sidebarisopen       = true;
      },

      getAmountRows() {

        let api_url = '';
        if (this.tableid) {
          api_url = this.endpoint_count_rows + this.active_database + '&tablename=' + this.tableid;
        }

        let vue_instance = this;

        this.is_fetching_data = true;
        axios.get(api_url).then(response => {
          vue_instance.total_amount_rows = response.data.amount_rows;
          vue_instance.is_fetching_data = false;
        }).catch(error => {
          console.log('-----error-------');
          console.log(error);
        })
      },

      showLoadMoreDataButtons() {
        return (this.tabledata.length > 1 && this.total_amount_rows > this.tabledata.length);
      },

      loadMoreRows() {

        this.offset_rows += 1;

        let api_url = '';
        if (this.tableid) {
          api_url = this.endpoint_table_data + this.active_database + '&tablename=' + this.tableid;
        }
        if (this.column && this.value) {
          api_url += '&column=' + this.column + '&value=' + this.value;
        } else if (this.column && this.querytype) {
          api_url += '&column=' + this.column + '&type=' + this.querytype;
        }
        if (this.order_by && this.order_direction) {
          api_url += '&orderby=' + this.order_by + '&orderdirection=' + this.order_direction;
        }
        if (this.offset_rows > 0) {
          api_url += '&offset=' + this.offset_rows;
        }

        let vue_instance = this;
        vue_instance.is_fetching_data = true;
        axios.get(api_url).then(response => {
          let extended_tabledata = this.tabledata.concat(response.data.data)
          vue_instance.tabledata         = extended_tabledata;
          vue_instance.is_fetching_data = false;
        }).catch(error => {
          console.log('-----error-------');
          console.log(error);
        })
      },

      loadAllRows() {
        let ask_confirm = true;
        if (this.total_amount_rows > 1000) {
          ask_confirm = confirm('Are you sure you want to load all rows?');
        }
        if (ask_confirm) {
          let api_url = '';
          if (this.tableid) {
            api_url = this.endpoint_table_data + this.active_database + '&tablename=' + this.tableid;
          }
          if (this.column && this.value) {
            api_url += '&column=' + this.column + '&value=' + this.value;
          } else if (this.column && this.querytype) {
            api_url += '&column=' + this.column + '&type=' + this.querytype;
          }
          if (this.order_by && this.order_direction) {
            api_url += '&orderby=' + this.order_by + '&orderdirection=' + this.order_direction;
          }
          api_url += '&limit=none';

          let vue_instance = this;

          vue_instance.is_fetching_data = true;
          axios.get(api_url).then(response => {
            vue_instance.tabledata = response.data.data;
            vue_instance.is_fetching_data = false;
          }).catch(error => {
            console.log('-----error-------');
            console.log(error);
          })
        }
      },

      // remove focus from table cell, so user can use dialog shortcuts again
      unfocusDatatable() {
        document.activeElement.blur();
        document.getElementById('app').focus();
      },

    },

  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

  .row-data-field {
    @apply flex w-full;
  }

  .row-data-field:hover .data {
    @apply bg-light-200;
  }

  .row-data-field:hover .header {
    @apply bg-dark-600;
  }

  .rows-action {
    @apply mx-2 inline-flex rounded-lg py-1 px-2 items-center;
  }

  .rows-action:hover {
    @apply bg-light-100;
  }

  .table-data tbody td:focus {
    @apply bg-highlight-700 outline-none;
  }

</style>
