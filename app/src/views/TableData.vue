<template>
  <div class="page-content-container">

    <spinner v-if="initial_loading" delay="200"></spinner>

    <div v-show="initial_loading === false" class="grid-container-content"
         :class="[page_view == 'single' && columns_split.length > 1 ? (columns_split.length == 2 ? 'w-9/12' : 'w-full') : '']">

      <div class="content-header">

        <div class="table-page-header">
          <a class="inline-flex items-center" @click="toggleMetaBox()">
            <h2>
              {{ tableid }}
            </h2>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 ml-1 fill-current">
              <path class="text-gray-400"
                    d="M15.3 10.3a1 1 0 0 1 1.4 1.4l-4 4a1 1 0 0 1-1.4 0l-4-4a1 1 0 0 1 1.4-1.4l3.3 3.29 3.3-3.3z"></path>
            </svg>
          </a>
          <table-nav :tableid="tableid" v-on:toggleMetaBox="toggleMetaBox"></table-nav>
          <div class="flex">
            <a @click="togglePageView()" class="mr-3">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 fill-current view-page-multi"
                   :class="{ 'active' : page_view == 'multi'}">
                <rect width="7" height="3" x="1" y="1" class="primary"></rect>
                <rect width="7" height="3" x="9" y="1" class="primary"></rect>
                <rect width="7" height="3" x="17" y="1" class="primary"></rect>
                <rect width="24" height="1" x="1" y="6" class="secondary"></rect>
                <rect width="7" height="3" x="1" y="9" class="primary"></rect>
                <rect width="7" height="3" x="9" y="9" class="primary"></rect>
                <rect width="7" height="3" x="17" y="9" class="primary"></rect>
                <rect width="24" height="1" x="1" y="14" class="secondary"></rect>
                <rect width="7" height="3" x="1" y="17" class="primary"></rect>
                <rect width="7" height="3" x="9" y="17" class="primary"></rect>
                <rect width="7" height="3" x="17" y="17" class="primary"></rect>
                <rect width="24" height="1" x="1" y="22" class="secondary"></rect>
              </svg>
            </a>
            <a @click="togglePageView()" class="mr-2 view-page-single" :class="{ 'active' : page_view == 'single'}">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 fill-current">
                <rect width="20" height="12" x="2" y="10" class="primary"></rect>
                <path class="secondary"
                      d="M20 8H4c0-1.1.9-2 2-2h12a2 2 0 0 1 2 2zm-2-4H6c0-1.1.9-2 2-2h8a2 2 0 0 1 2 2z"></path>
              </svg>
            </a>

            <a @click="help_modal_open = true" class="text-light-300">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 fill-current">
                <path class="secondary"
                      d="M12 19.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm1-5.5a1 1 0 0 1-2 0v-1.41a1 1 0 0 1 .55-.9L14 10.5C14.64 10.08 15 9.53 15 9c0-1.03-1.3-2-3-2-1.35 0-2.49.62-2.87 1.43a1 1 0 0 1-1.8-.86C8.05 6.01 9.92 5 12 5c2.7 0 5 1.72 5 4 0 1.3-.76 2.46-2.05 3.24L13 13.2V14z"></path>
              </svg>
            </a>
          </div>
        </div>

        <div v-cloak v-if="is_fetching_data === false">
          <table-data-meta v-if="meta_box_open" v-on:confirmDropTable="confirmDropTable"
                           v-on:confirmTruncateTable="confirmTruncateTable" :totalrows="total_amount_rows"
                           :rows="tabledata.length" :columns="columns.length" :query="query"></table-data-meta>
        </div>

        <flash-message></flash-message>

        <result-message :message="query_result"></result-message>
      </div>


      <div class="content-body w-full">

        <div v-if="page_view == 'single'">

          <p class="text-center mb-2">Showing row {{ row_pointer + 1 + (offset_rows * rows_per_page) }} of {{ rows_limit_end }}</p>

          <div class="single-row-view" v-if="tabledata.length > 0">

            <div class="single-view-sidebar">
              <div v-show="column_for_list" class="h-full ">
                <h3 class="mr-8 bg-dark-400 px-3 py-1">
                  {{ columns[column_for_list].Field }}
                </h3>
                <div class="single-view-sidebar-scrollable scroll-bar mr-5 ">
                  <a v-for="(row, row_index) in tabledata"
                     class="block bg-light-100 border-b border-light-300 px-3 py-1 mr-1"
                     @click="row_pointer = row_index">
                  <span v-if="row[columns[column_for_list].Field] === null"
                        class="null-value"><i>NULL</i></span>
                    <span v-else class="">{{ row[columns[column_for_list].Field] }}</span>
                  </a>
                </div>
              </div>
            </div>

            <div @mouseenter="addScrollingEvent()" @mouseleave="removeScrollingEvent()">
              <div class="flex w-full mb-4">

                <div class="pr-4" v-for="(columns_part, indexab) in columns_split"
                     :class="[ (columns_split.length == 1) ? 'w-full' : ((columns_split.length == 2) ? 'w-1/2' : 'w-1/3') ]">

                  <div class="column-row" v-for="(column, index) in columns_part"
                       :class="[ (columns_split.length == 1) ? 'one-column' : ((columns_split.length == 2) ? 'two-columns' : 'three-columns') ]">

                    <div class=" header bg-dark-400 pl-3"
                         style="padding-top: 2px; padding-bottom: 2px;">
                      <div class="text-gray-300 mr-6">
                        <a @click="column_for_list = (indexab * columns_part.length) + index  ">
                          {{ column.Field }}
                        </a>
                      </div>
                    </div>

                    <div class="data bg-light-100 border-b border-light-300 pr-3 py-1 text-right"
                         style="padding-top: 2px; padding-bottom: 2px;">
                      <div class=" ml-6 overflow-hidden data-value">
                          <span v-if="tabledata[row_pointer][column.Field] === null"
                                class="null-value"><i>NULL</i></span>
                        <span v-else class="">{{ tabledata[row_pointer][column.Field] }}</span>
                      </div>
                    </div>

                  </div>
                </div>
              </div>

              <div class="flex items-start justify-center pr-4">

                <div class="inline-flex items-center justify-center">
                  <a class="btn mx-1" @click="editRowFromSingleView()">
                    <span>Edit</span>
                  </a>
                  <a class="btn mx-1" @click="confirmDeleteRowFromSingleView()">
                    <span>Delete</span>
                  </a>
                </div>
              </div>

            </div>

          </div>

        </div>


        <div v-else-if="page_view == 'multi'" class="flex">

          <div class="relative w-full">

            <div v-cloak v-if="is_fetching_data === false && tabledata.length == 0">
              <p class="bg-light-100 text-gray-400 px-2 py-2 inline-block">No rows found</p>
            </div>

            <table cellspacing="0" class="table-data" v-if="tabledata.length > 0" ref="datatable"
                   @keydown.right.prevent="focusCellNext($event, 1)"
                   @keydown.left.prevent="focusCellPrevious($event, 1)"
                   @keydown.up.prevent="focusRowUp($event, 1)" @keydown.down.prevent="focusRowDown($event, 1)"
                   @keydown.shift.right.prevent="focusCellNext($event,5)"
                   @keydown.shift.left.prevent="focusCellPrevious($event,5)"
                   @keydown.shift.up.prevent="focusRowUp($event,5)"
                   @keydown.shift.down.prevent="focusRowDown($event,5)"
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
                    :class="{ ' highlight-column' : (column_header.Field.toLowerCase() == column)}"
                    :key="column_header.Field">
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
              <tr v-for="(row, row_index) in tabledata" :key="row_index"  @click.ctrl="toggleRowSidebar(row_index)">
                <td class="toggle-row">
                  <label class="inline-block">
                    <input type="checkbox" class="hidden" :value="row_index" v-model.lazy="selected_rows" />
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         class="w-6 fill-current -mt-1 inline-block">
                      <circle cx="12" cy="12" r="10"></circle>
                      <path
                          d="M10 14.59l6.3-6.3a1 1 0 0 1 1.4 1.42l-7 7a1 1 0 0 1-1.4 0l-3-3a1 1 0 0 1 1.4-1.42l2.3 2.3z"></path>
                    </svg>
                  </label>
                </td>
                <TableDataRow v-bind:row="row" v-bind:truncate-amount="cell_text_display_limit"></TableDataRow>
              </tr>
              </tbody>

            </table>

            <div class="row-actions sticky bottom-0 left-0 z-30 w-full"
                 v-if="tabledata.length > 0 && selected_rows.length > 0">

              <div class="py-3 px-3  flex items-center bg-dark-600 text-white">

                <div class="font-bold mr-6">
                  {{ selected_rows.length }} rows
                </div>

                <a class="rows-action" v-if="selected_rows.length == 1" @click="editRowFromTable()">
                  <span>Edit</span>
                </a>

<!--                <a class="rows-action" href="">-->
<!--                  <span>Duplicate</span>-->
<!--                </a>-->

                <a class="rows-action" @click="confirmDeleteRows()">
                  <span>Delete</span>
                </a>

<!--                <a class="rows-action" href="">-->
<!--                  <span>Export</span>-->
<!--                </a>-->

              </div>
            </div>

            <br>

            <div class="flex items-center" v-if="this.tabledata.length > 0">
              <button class="btn icon p-2 mr-3" @click="prevPage()" v-if="offset_rows > 0">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 fill-current text-light-200" style="transform: rotate(-90deg);">
                  <path class="text-light-200"
                        d="M12 20.1L3.4 21.9a1 1 0 0 1-1.3-1.36l9-18a1 1 0 0 1 1.8 0l9 18a1 1 0 0 1-1.3 1.36L12 20.1z"></path>
                  <path class="text-light-200" d="M12 2c.36 0 .71.18.9.55l9 18a1 1 0 0 1-1.3 1.36L12 20.1V2z"></path>
                </svg>
              </button>
              <button class="btn icon p-2 mr-3" @click="nextPage()" v-if="rows_limit_end < total_amount_rows">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 fill-current text-light-200" style="transform: rotate(90deg);">
                  <path class="text-light-200"
                        d="M12 20.1L3.4 21.9a1 1 0 0 1-1.3-1.36l9-18a1 1 0 0 1 1.8 0l9 18a1 1 0 0 1-1.3 1.36L12 20.1z"></path>
                  <path class="" d="M12 2c.36 0 .71.18.9.55l9 18a1 1 0 0 1-1.3 1.36L12 20.1V2z"></path>
                </svg>
              </button>
              <div class="text-light-300 mr-4">
                Showing {{ rows_limit_start }}-{{ rows_limit_end }} of {{ total_amount_rows }}
              </div>
              <spinner v-if="is_fetching_data === true"></spinner>
            </div>

          </div>

        </div>

      </div>

      <row-sidebar :sidebarisopen="sidebarisopen" v-on:closeRowSidebar="closeRowSidebar"
                   v-on:editRow="editRowFromSidebar"
                   :rowdata="sidebar_row_data" :columndata="sidebar_column_data" :from="tabledata"></row-sidebar>

    </div>

    <confirm-modal v-if="confirm_modal_open" v-on:close="closeConfirmModal()"
                   v-on:confirm="confirmConfirmModal()">
      {{ confirm_modal_message }}
    </confirm-modal>

    <TableDataHelpModal :modalisopen="help_modal_open" v-on:closehelp="closeHelp()" v-if="help_modal_open"></TableDataHelpModal>

  </div>

</template>

<script>

import TableNav from '@/components/TableNav.vue'
import TableDataMeta from '@/components/TableDataMeta.vue'
import TableKeyNavigation from '@/mixins/TableKeyNavigation.js'
import RowSidebar from "@/components/RowSidebar";
import FlashMessage from "@/components/FlashMessage";
import Spinner from "@/components/Spinner";
import ConfirmModal from "@/components/ConfirmModal";
import ConfirmModalMixin from "@/mixins/ConfirmModal";
import TableDataRow from "@/components/TableDataRow";
import ApiMixin from "@/mixins/Api";
import ResultMessage from "@/components/ResultMessage";
import TableDataHelpModal from "@/components/TableDataHelpModal";

export default {
  name: 'TableData',
  props: ['tableid', 'column', 'comparetype', 'value'],
  data() {
    return {
      page_view: 'multi',
      tabledata: [],
      columns: [],
      total_amount_rows: 0,
      offset_rows: 0,
      api_error: '',
      endpoint_table_data: 'row/list',
      endpoint_delete_rows: 'row/delete',
      endpoint_truncate_tables: 'table/truncate',
      endpoint_drop_tables: 'table/drop',
      query: '',
      order_by: '',
      order_direction: '',
      sidebarisopen: false,
      sidebar_row_data: [],
      sidebar_column_data: [],
      sidebar_row_index: 0,
      selected_rows: [],
      is_fetching_data: false, // true when fetching data through ajax
      meta_box_open: false,
      initial_loading: true,
      row_pointer: 0,
      column_for_list: 0,
      query_result: {},
      help_modal_open: false,
    }
  },

  components: {
    TableDataHelpModal,
    ResultMessage,
    Spinner,
    FlashMessage,
    RowSidebar,
    TableNav,
    TableDataMeta,
    ConfirmModal,
    TableDataRow,
  },

  mixins: [
    TableKeyNavigation,
    ConfirmModalMixin,
    ApiMixin,
  ],

  created() {
    this.$store.commit("recenttables/ADD_RECENT_TABLE", this.tableid);
    document.addEventListener('keydown', this.triggerKeyDown);

    // only show once, on the first visit
    if(this.do_not_show_table_data_help_message === false) {
      this.help_modal_open = true;
    }

    this.$emit('setcontextoptions', [
      {
        'shortkey': '1',
        'label': 'Truncate table',
        'action': 'confirmTruncateTable'
      },
      {
        'shortkey': '2',
        'label': 'Drop table',
        'action': 'confirmDropTable'
      },
    ]);
  },

  destroyed() {
    document.removeEventListener("keydown", this.triggerKeyDown);
  },

  mounted() {
    if (this.default_table_column_order != '' && this.primary_key !== false) {
      this.order_by        = this.primary_key;
      this.order_direction = (this.default_table_column_order == 'primary_desc') ? 'desc' : 'asc';
    }
    this.getTableData();
  },

  computed: {
    columns_split: function () {
      if (this.columns.length == 0) return [];
      if (this.columns.length <= 20) return [this.columns];

      if (this.columns.length < 40) {
        let halfwayThrough  = Math.round(this.columns.length / 2)
        let arrayFirstHalf  = this.columns.slice(0, halfwayThrough);
        let arraySecondHalf = this.columns.slice(halfwayThrough, this.columns.length);
        return [arrayFirstHalf, arraySecondHalf];
      }

      let split_by_3 = Math.round(this.columns.length / 3);
      let array_1_3  = this.columns.slice(0, split_by_3);
      let array_2_3  = this.columns.slice(split_by_3, split_by_3 + split_by_3);
      let array_3_3  = this.columns.slice(split_by_3 + split_by_3, this.columns.length);
      return [array_1_3, array_2_3, array_3_3];
    },

    active_database() {
      return this.$store.state.activeDatabase;
    },

    primary_key() {
      return this.$store.getters["tables/primaryKeyOfTable"](this.tableid);
    },

    rows_per_page() {
      return this.$store.getters["settings/getSetting"]('default_rows_per_page');
    },

    rows_limit_start() {
      return (this.rows_per_page * this.offset_rows) + 1;
    },

    rows_limit_end() {
      return ((this.offset_rows + 1) * this.rows_per_page > this.total_amount_rows) ? this.total_amount_rows : ((this.offset_rows + 1) * this.rows_per_page);
    },

    cell_text_display_limit() {
      return this.$store.getters["settings/getSetting"]('cell_text_display_limit');
    },

    default_table_column_order() {
      return this.$store.getters["settings/getSetting"]('default_table_column_order');
    },

    nodes_skip_on_key() {
      return this.$store.state.nodes_skip_on_key;
    },

    do_not_show_table_data_help_message() {
      return this.$store.getters["settings/getSetting"]('do_not_show_table_data_help_message');
    },

    tables() {
      return this.$store.getters["tables/tables"];
    },
    is_view() {
      let current_table_data = this.tables.find(table_data => table_data.name == this.tableid);
      if (current_table_data.type == 'VIEW') return true;
      return false;
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

  },

  methods: {

    toStructure() {
      this.$router.push({name: 'structure', params: {database: this.active_database, tableid: this.tableid}});
    },

    toIndexes() {
      this.$router.push({name: 'indexes', params: {database: this.active_database, tableid: this.tableid}});
    },

    toForeignKeys() {
      this.$router.push({name: 'foreignkeys', params: {database: this.active_database, tableid: this.tableid}});
    },

    toAddRow() {
      this.$router.push({name: 'addrow', params: {database: this.active_database, tableid: this.tableid}});
    },

    getTableData() {

      let api_url_params = {'db': this.active_database, 'tablename': this.tableid, 'limit' : this.rows_per_page };
      if (this.column && this.value && this.comparetype) {
        api_url_params.column = this.column;
        api_url_params.comparetype = this.comparetype;
        api_url_params.value = this.value;
      }
      if (this.order_by && this.order_direction) {
        api_url_params.orderby = this.order_by;
        api_url_params.orderdirection = this.order_direction;
      }

      let api_url = this.buildApiUrl(this.endpoint_table_data, api_url_params);

      let vue_instance = this;

      this.is_fetching_data = true;

      this.$http.get(api_url).then(response => {

        if(this.validateApiResponse(response) === false) return;

        if(response.data.data.result == 'error') {
          this.initial_loading  = false;
          this.is_fetching_data = false;
          vue_instance.query_result = {type: 'error', message: response.data.data.message};
          scroll(0,0);
          return;
        }

        let response_data = response.data.data;
        let data = response_data.data.map(item => {
          return Object.freeze(item);
        });
        this.tabledata         = data;
        this.columns           = Object.freeze(response_data.columns);
        this.total_amount_rows = response_data.amount_rows;
        this.query = response_data.query;
        if (this.tabledata.length == 1) {
          this.page_view = 'single';
        }
        this.initial_loading  = false;
        this.is_fetching_data = false;
        this.$nextTick().then(function () {
          // DOM updated
          if (vue_instance.column && vue_instance.tabledata.length > 0) {
            vue_instance.gotocolumn(vue_instance.column);
          }

          // set focus on first cell, for cell navigation with keyboard
          if (vue_instance.tabledata.length > 0) {
            let cell_nr = 1;
            if (vue_instance.column) {
              let obj = vue_instance.columns.find(column_object => column_object.Field.toLowerCase() == vue_instance.column.toLowerCase());
              cell_nr = vue_instance.columns.indexOf(obj) + 1; // set the focus on the same column as the column highlight
              vue_instance.$refs['datatable'].getElementsByTagName('tbody')[0].rows[0].cells[cell_nr].focus();
            }
          }
        });

      }).catch(error => {
        this.initial_loading = false;
        this.handleApiError(error);
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
      if (this.sidebarisopen === true) {
        let row_num_keys = [];
        for (var key in this.tabledata[row_index]) {
          row_num_keys.push(this.tabledata[row_index][key]);
        }
        this.sidebar_row_data.push(row_num_keys);
        return;
      }

      let row_num_keys    = [];
      let column_num_keys = [];
      for (var key in this.tabledata[row_index]) {
        row_num_keys.push(this.tabledata[row_index][key]);
        column_num_keys.push(key);
      }
      this.sidebar_row_index   = row_index;
      this.sidebar_row_data    = [row_num_keys];
      this.sidebar_column_data = column_num_keys;
      this.sidebarisopen       = true;
    },

    nextPage() {
      if((this.offset_rows + 1) * this.rows_per_page >= this.total_amount_rows) return;
      this.offset_rows += 1;
      this.loadRows();
      this.row_pointer = 0;
    },

    prevPage() {
      if(this.offset_rows <= 0) return;
      this.offset_rows -= 1;
      this.loadRows();
      this.row_pointer = this.rows_per_page - 1;
    },

    loadRows() {
      let api_url_params = {'db': this.active_database, 'tablename': this.tableid, 'limit' : this.rows_per_page };
      if (this.column && this.value && this.comparetype) {
        api_url_params.column = this.column;
        api_url_params.comparetype = this.comparetype;
        api_url_params.value = this.value;
      }
      if (this.order_by && this.order_direction) {
        api_url_params.orderby = this.order_by;
        api_url_params.orderdirection = this.order_direction;
      }
      if (this.offset_rows > 0) {
        api_url_params.offset = this.offset_rows;
      }

      let api_url = this.buildApiUrl(this.endpoint_table_data, api_url_params);

      let vue_instance              = this;
      vue_instance.is_fetching_data = true;
      this.$http.get(api_url).then(response => {

        if(this.validateApiResponse(response) === false) return;

        if (response.data.data.result == 'error') {
          this.initial_loading      = false;
          this.is_fetching_data     = false;
          vue_instance.query_result = {type: 'error', message: response.data.data.message};
          scroll(0, 0);
          return;
        }

        vue_instance.tabledata        = Object.freeze(response.data.data.data);
        vue_instance.is_fetching_data = false;
      }).catch(error => {
        this.handleApiError(error);
      })
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

    handleDeleteRows(delete_by_rows, delete_by_column) {
      let params = new URLSearchParams();
      params.append('delete_by_column', delete_by_column);
      for (let row_index in delete_by_rows) {
        params.append('delete_by_rows[]', delete_by_rows[row_index]);
      }

      let api_url_params = {'db': this.active_database, 'tablename': this.tableid};
      let api_url = this.buildApiUrl(this.endpoint_delete_rows, api_url_params);

      this.$http.post(api_url, params).then(response => {
        if(this.validateApiResponse(response) === false) return;

        if(response.data.data.result == 'error') {
          if(response.data.data.affected_rows > 0) {
            let msg = response.data.data.affected_rows + ' row' + (response.data.data.affected_rows > 1 ? 's' : '') + ' deleted';
            this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", { type: 'success', message: msg });
          }
          this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", { type: 'error', message: response.data.data.message});
          this.refreshPage();
        }
        else if(response.data.data.result == 'success') {
          let msg = response.data.data.affected_rows + ' row' + (response.data.data.affected_rows > 1 ? 's' : '') + ' deleted';
          this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", { type: 'success', message: msg });
          this.refreshPage();
        }
      }).catch(error => {
        this.handleApiError(error);
      })
    },

    deleteRow() {
      let unique_columns = this.getUniqueColumns();

      if (unique_columns.length == 0) {
        alert('The table has no primary or unique column. This is not yet supported.'); // todo: support this
        return;
      }

      let delete_by_column = unique_columns[0].Field;

      let delete_by_rows = [this.tabledata[this.row_pointer][delete_by_column]];
      this.handleDeleteRows(delete_by_rows, delete_by_column);
    },

    deleteRows() {
      let unique_columns = this.getUniqueColumns();

      if (unique_columns.length == 0) {
        alert('The table has no primary or unique column. This is not yet supported.'); // todo: support this
        return;
      }

      let delete_by_column = unique_columns[0].Field;
      let vue_instance   = this;
      let delete_by_rows = this.selected_rows.map(function (row_index) {
        return vue_instance.tabledata[row_index][delete_by_column];
      });
      this.handleDeleteRows(delete_by_rows, delete_by_column);
    },

    confirmDeleteRows() {
      this.confirm_modal_message = 'Delete ' + this.selected_rows.length + ' row';
      if (this.selected_rows.length > 1) {
        this.confirm_modal_message += 's';
      }
      this.confirm_modal_open   = true;
      this.confirm_modal_action = 'deleteRows';
    },

    confirmDeleteRowFromSingleView() {
      this.confirm_modal_message = 'Delete 1 row';
      this.confirm_modal_open   = true;
      this.confirm_modal_action = 'deleteRow';
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
    },

    editRow(row_index) {
      let unique_columns = this.getUniqueColumns();

      if (unique_columns.length == 0) {
        alert('The table has no primary or unique column. This is not yet supported.'); // todo: support this
        return;
      }

      let unique_column       = unique_columns[0].Field;
      let unique_column_value = this.tabledata[row_index][unique_column];

      this.$router.push({
        name: 'editrow',
        params: { database: this.active_database, 'tableid': this.tableid, 'column': unique_column, 'rowid': unique_column_value}
      });
    },

    editRowFromSidebar() {
      this.editRow(this.sidebar_row_index);
    },

    editRowFromTable() {
      this.editRow(this.selected_rows[0]);
    },

    editRowFromSingleView() {
      this.editRow(this.row_pointer);
    },

    confirmDropTable() {
      this.confirm_modal_message = 'Drop table ' + this.tableid;
      this.confirm_modal_open    = true;
      this.confirm_modal_action  = 'dropTable';
    },

    dropTable() {
      let params = new URLSearchParams();
      if(this.is_view) {
        params.append('views[]', this.tableid);
      }
      else {
        params.append('tables[]', this.tableid);
      }

      let vue_instance = this;
      let api_url_params = {'db': this.active_database};
      let api_url = this.buildApiUrl(this.endpoint_drop_tables, api_url_params);
      this.$http.post(api_url, params).then(response => {
        if(this.validateApiResponse(response) === false) return;

        if(response.data.data.result == 'error') {
          vue_instance.query_result = {type: 'error', message: response.data.data.message};
          scroll(0,0);
          return;
        }

        this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", {
          type: 'success',
          message: 'Table ' + vue_instance.tableid + ' dropped.',
          query: response.data.data.query
        });
        this.$store.dispatch('refreshTables');
        vue_instance.$router.push({name: 'database', params: {database: vue_instance.active_database}});
      }).catch(error => {
        this.handleApiError(error);
      })
    },

    confirmTruncateTable() {
      this.confirm_modal_message = 'Truncate table ' + this.tableid;
      this.confirm_modal_open    = true;
      this.confirm_modal_action  = 'truncateTable';
    },

    truncateTable() {
      let params = new URLSearchParams();
      if(this.is_view) {
        params.append('views[]', this.tableid);
      }
      else {
        params.append('tables[]', this.tableid);
      }

      let vue_instance = this;
      let api_url_params = {'db': this.active_database};
      let api_url = this.buildApiUrl(this.endpoint_truncate_tables, api_url_params);

      this.$http.post(api_url, params).then(response => {

        if(this.validateApiResponse(response) === false) return;

        if(response.data.data.result == 'error') {
          vue_instance.query_result = {type: 'error', message: response.data.data.message};
          scroll(0,0);
          return;
        }

        this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", {
          type: 'success',
          message: 'Table ' + vue_instance.tableid + ' truncated.',
          query: response.data.query,
        });
        this.$store.dispatch('refreshTables');
        this.refreshPage();
      }).catch(error => {
        this.handleApiError(error);
      })
    },

    handleScroll(event) {
      if (event.deltaY < 0) {
        this.rowPointerDown()
      } else {
        this.rowPointerUp()
      }
    },

    triggerKeyDown: function (evt) {
      const { nodeName } = document.activeElement;
      if (this.nodes_skip_on_key.includes(nodeName)) return;

      if (evt.key === 'v') {
        this.togglePageView();
      }
      else if (evt.key === 'n') {
        this.nextPage();
      }
      else if (evt.key === 'p') {
        this.prevPage();
      }
    },

    rowPointerUp() {
      if(this.is_fetching_data === true) return;
      if((this.row_pointer + 1 + (this.offset_rows * this.rows_per_page)) >= this.total_amount_rows) return;
      this.row_pointer += 1;
      if(this.row_pointer >= this.rows_per_page) {
        this.nextPage();
      }
    },

    rowPointerDown() {
      if(this.is_fetching_data === true) return;
      if(this.row_pointer <= 0) {
        if(this.offset_rows > 0) {
          this.prevPage();
        }
        else {
          this.row_pointer = 0;
        }
      }
      else {
        this.row_pointer -= 1;
      }
    },

    addScrollingEvent() {
      document.addEventListener("wheel", this.handleScroll);
    },

    removeScrollingEvent() {
      document.removeEventListener("wheel", this.handleScroll);
    },

    togglePageView() {
      this.page_view = (this.page_view == 'single') ? 'multi' : 'single';
    },

    closeHelp() {
      this.help_modal_open = false;
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

/** SINGLE PAGE VIEW **/
div.column-row {
  display: grid;
}

div.column-row.one-column {
  grid-template-columns: auto minmax(50px, 70%);
  width:                 auto;
}

div.column-row.two-columns {
  grid-template-columns: auto minmax(50px, 50%);
  min-width:             70%;
}

div.column-row.three-columns {
  grid-template-columns: auto minmax(50px, 50%);
}

div.column-row .data {
  word-break: break-word;
}

.data-value {
  @apply truncate;
}

.data-value:hover {
  overflow:      visible;
  text-overflow: unset;
  white-space:   normal;
}

.single-row-view {
  display:               grid;
  grid-template-columns: auto 1fr;
  grid-template-rows: minmax(250px, 650px) 1fr;
}

.single-view-sidebar-scrollable {
  overflow: auto;
  height:   100%;
}

.view-page-multi.active .primary,
.view-page-single.active .primary,
.view-page-multi.active .secondary,
.view-page-single.active .secondary {
  @apply text-highlight-400;
}

.view-page-multi .primary,
.view-page-single .primary {
  @apply text-light-200;
}

.view-page-multi .secondary,
.view-page-single .secondary {
  @apply text-light-300;
}


</style>
