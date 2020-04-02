<template>
  <div v-show="initial_loading === false">

    <table-nav :tableid="tableid" v-on:toggleMetaBox="toggleMetaBox"></table-nav>

    <table-data-meta v-if="meta_box_open"></table-data-meta>

    <div class="w-full flex items-start">

      <div class="relative w-full">

        <div v-cloak v-if="is_fetching_data === false && tabledata.length == 0">
          <p class="bg-light-100 text-gray-400 px-2 py-2 inline-block">No rows found</p>
        </div>

        <table cellspacing="0" class="table-data" v-if="tabledata.length > 1" ref="datatable"
               @keydown.right.prevent="focusCellNext($event, 1)" @keydown.left.prevent="focusCellPrevious($event, 1)"
               @keydown.up.prevent="focusRowUp($event, 1)" @keydown.down.prevent="focusRowDown($event, 1)"
               @keydown.shift.right.prevent="focusCellNext($event,5)"
               @keydown.shift.left.prevent="focusCellPrevious($event,5)"
               @keydown.shift.up.prevent="focusRowUp($event,5)" @keydown.shift.down.prevent="focusRowDown($event,5)"
               @keydown.esc="unfocusDatatable()"
               @keydown.open-search="unfocusDatatable()" @keydown.open-recent-tables="unfocusDatatable()"
               @keydown.refresh-page="unfocusDatatable()" @keydown.to-query="unfocusDatatable()"
               @keydown.open-database-list="unfocusDatatable()">
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
                <input type="checkbox" class="hidden" :value="row_index" v-model="selected_rows" />
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

        <div class="row-actions sticky bottom-0 left-0 z-30 w-full"
             v-if="tabledata.length > 1 && selected_rows.length > 0">

          <div class="py-3 px-3  flex items-center bg-dark-600 text-white">

            <div class="font-bold mr-6">
              {{ selected_rows.length }} rows
            </div>

            <a class="rows-action" href="" v-if="selected_rows.length == 1">
              <span>Edit</span>
            </a>

            <a class="rows-action" href="">
              <span>Duplicate</span>
            </a>

            <a class="rows-action" @click="deleteRows()">
              <span>Delete</span>
            </a>

            <a class="rows-action" href="">
              <span>Export</span>
            </a>

          </div>
        </div>

      </div>

    </div>

    <div class="w-full px-2" v-if="tabledata.length == 1">
        <div class="row-data-field w-full" v-for="column in columns">

          <div class=" header bg-dark-400 flex items-center w-2/5 pl-3 flex-shrink-0"
               style="padding-top: 2px; padding-bottom: 2px;">
            <div class="text-gray-300 mr-6">{{ column.Field }}</div>

          </div>

          <div class="data bg-light-100 border-b border-light-300 flex-grow flex items-center pr-3 py-1 justify-start"
               style="padding-top: 2px; padding-bottom: 2px;">

            <div class=" ml-6">
              <span v-if="tabledata[0][column.Field] === null" class="null-value"><i>NULL</i></span>
              <span v-else>{{ tabledata[0][column.Field] }}</span>
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
  import TableDataMeta from '@/components/TableDataMeta.vue'
  import TableKeyNavigation from '@/mixins/TableKeyNavigation.js'
  import RowSidebar from "./RowSidebar";

  export default {
    name: 'TableData',
    props: ['tableid', 'column', 'value'],
    data() {
      return {
        tabledata: [],
        columns: [],
        total_amount_rows: 0,
        offset_rows: 0,
        endpoint_table_data: 'http://localhost/rove/api/tabledata.php?db=',
        endpoint_count_rows: 'http://localhost/rove/api/countrows.php?db=',
        endpoint_delete_rows: 'http://localhost/rove/api/delete_rows.php?db=',
        order_by: '',
        order_direction: '',
        sidebarisopen: false,
        sidebar_row_data: [],
        sidebar_column_data: [],
        selected_rows: [],
        is_fetching_data: false, // true when fetching data through ajax
        meta_box_open: false,
        initial_loading: true,
      }
    },

    components: {
      RowSidebar,
      TableNav,
      TableDataMeta
    },

    mixins: [
      TableKeyNavigation
    ],

    mounted() {
      this.getTableData();
      this.getAmountRows();
    },

    computed: {
      columns_halved: function () {
        if(this.columns.length == 0) return [];
        let halfwayThrough  = Math.floor(this.columns.length / 2)
        let arrayFirstHalf  = this.columns.slice(0, halfwayThrough);
        let arraySecondHalf = this.columns.slice(halfwayThrough, this.columns.length);
        return [arrayFirstHalf, arraySecondHalf];
      },

      active_database() {
        return this.$store.state.activeDatabase;
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

      getTableData() {

        let api_url = '';
        if (this.tableid) {
          api_url = this.endpoint_table_data + this.active_database + '&tablename=' + this.tableid;
        }
        if (this.column && this.value && this.value == 'groupby') {
          api_url += '&column=' + this.column + '&type=' + 'groupby';
        } else if (this.column && this.value) {
          api_url += '&column=' + this.column + '&value=' + this.value;
        }
        if (this.order_by && this.order_direction) {
          api_url += '&orderby=' + this.order_by + '&orderdirection=' + this.order_direction;
        }

        let vue_instance = this;

        this.is_fetching_data = true;

        axios.get(api_url).then(response => {
          this.tabledata = response.data.data;
          this.columns   = response.data.columns;
          this.initial_loading = false;
          this.$nextTick().then(function () {
            // DOM updated
            if (vue_instance.column && vue_instance.tabledata.length > 0) {
              vue_instance.gotocolumn(vue_instance.column);
            }

            // set focus on first cell, for cell navigation with keyboard
            if (vue_instance.tabledata.length > 0) {
              vue_instance.$refs['datatable'].getElementsByTagName('tbody')[0].rows[0].cells[1].focus();
            }

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
          vue_instance.is_fetching_data  = false;
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
        if (this.column && this.value && this.value == 'groupby') {
          api_url += '&column=' + this.column + '&type=' + 'groupby';
        } else if (this.column && this.value) {
          api_url += '&column=' + this.column + '&value=' + this.value;
        }
        if (this.order_by && this.order_direction) {
          api_url += '&orderby=' + this.order_by + '&orderdirection=' + this.order_direction;
        }
        if (this.offset_rows > 0) {
          api_url += '&offset=' + this.offset_rows;
        }

        let vue_instance              = this;
        vue_instance.is_fetching_data = true;
        axios.get(api_url).then(response => {
          let extended_tabledata        = this.tabledata.concat(response.data.data)
          vue_instance.tabledata        = extended_tabledata;
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
          if (this.column && this.value && this.value == 'groupby') {
            api_url += '&column=' + this.column + '&type=' + 'groupby';
          } else if (this.column && this.value) {
            api_url += '&column=' + this.column + '&value=' + this.value;
          }
          if (this.order_by && this.order_direction) {
            api_url += '&orderby=' + this.order_by + '&orderdirection=' + this.order_direction;
          }
          api_url += '&limit=none';

          let vue_instance = this;

          vue_instance.is_fetching_data = true;
          axios.get(api_url).then(response => {
            vue_instance.tabledata        = response.data.data;
            vue_instance.is_fetching_data = false;
          }).catch(error => {
            console.log('-----error-------');
            console.log(error);
          })
        }
      },

      toggleAllRows($event) {
        if ($event.target.checked) {
          this.selected_rows = [];
          for (let row_index in Object.keys(this.tabledata)) {
            this.selected_rows.push(row_index);
          }
        } else {
          this.selected_rows = [];
        }
      },

      deleteRows() {
        let unique_columns = this.getUniqueColumns();

        if (unique_columns.length == 0) {
          alert('The table has no primary or unique column. This is not yet supported.'); // todo: support this
          return;
        }

        let ask_confirm = confirm('Are you sure you want to delete the selected rows?');
        if (!ask_confirm) return;

        let delete_by_column = unique_columns[0].Field;

        let vue_instance   = this;
        let delete_by_rows = this.selected_rows.map(function (row_index) {
          return vue_instance.tabledata[row_index][delete_by_column];
        });

        let params = new URLSearchParams();
        params.append('delete_by_column', delete_by_column);
        for (let row_index in delete_by_rows) {
          params.append('delete_by_rows[]', delete_by_rows[row_index]);
        }

        let api_url = this.endpoint_delete_rows + this.active_database + '&tablename=' + this.tableid;
        axios.post(api_url, params).then(response => {
          // remove the selected rows from the data, sort by highest number first, else we will remove the wrong rows because of numerical order
          let selected_rows_sorted = this.selected_rows.sort(function (a, b) {
            return b - a
          });
          for (let row_index in selected_rows_sorted) {
            this.tabledata.splice(selected_rows_sorted[row_index], 1);
          }
          this.selected_rows = [];
        }).catch(error => {
          console.log('-----error-------');
          console.log(error);
        })

      },

      // get the column(s) of a table by which it is identifiable
      getUniqueColumns() {
        let key_of_primary_column = this.columns.filter(function (column_index) {
          if (column_index.Key === 'PRI') {
            return column_index;
          }
        });
        if (key_of_primary_column.length > 0) return key_of_primary_column;

        let key_of_unique_column = this.columns.filter(function (column_index) {
          if (column_index.Key === 'UNI') {
            return column_index;
          }
        });
        if (key_of_unique_column.length > 0) return key_of_unique_column;

        // todo: unique columns ophalen op basis van alle kolommen, grote text kolommen (json, TEXT) via MD5 doen, zie adminer: select.inc.php:381
        return [];
      },

      toggleMetaBox() {
        this.meta_box_open = !this.meta_box_open;
      }

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

</style>
