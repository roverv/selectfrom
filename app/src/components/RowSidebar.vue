<template>
  <div
    class="sidebar fixed right-0 top-0 bg-white h-screen z-50 bg-dark-600 p-4 pt-12 pb-8 flex flex-col border-l border-light-100 "
    :class="[{ 'hidden' : !sidebarisopen}, getSidebarWidthClass ]"
    style="box-shadow: -3px 0px 10px 0px rgba(0,0,0,0.2);">

    <a @click="close()" class="absolute left-0 top-0 ml-2 mt-2">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-8 fill-current text-gray-600">
        <path class="secondary" fill-rule="evenodd"
              d="M15.78 14.36a1 1 0 0 1-1.42 1.42l-2.82-2.83-2.83 2.83a1 1 0 1 1-1.42-1.42l2.83-2.82L7.3 8.7a1 1 0 0 1 1.42-1.42l2.83 2.83 2.82-2.83a1 1 0 0 1 1.42 1.42l-2.83 2.83 2.83 2.82z" />
      </svg>
    </a>

    <div class="flex items-center justify-between mb-4">
      <h2 class="font-semibold text-xl">Row</h2>

      <div class="text-right">
        <a @click="toggleDifferences()" class="flex items-center" v-if="rowdata.length > 1">
          <span v-if="only_show_differences == true">Show duplicate values</span>
          <span v-else>Hide duplicate values</span>
        </a>
        <a @click="toggleColumnOrder()" class="flex items-center">
          Sort&nbsp;
          <span v-if="column_order == 'default'">alphabetical</span>
          <span v-else>default</span>
        </a>
      </div>

    </div>


    <div class="row-data overflow-y-scroll overflow-x-hidden pl-2 -ml-2">
      <div>

        <table class="w-full">
          <tr v-for="rows in rows_data">
            <td class="px-1 py-1 relative border-b border-gray-600" valign="top" :class="{ 'pt-2' : rows.table }">
              {{ rows.field }}
              <div v-if="rows.table" class="absolute right-0 top-0 text-xs text-light-200 mr-1">{{ rows.table }}</div>
            </td>
            <td class="px-1 py-1 break-all border-b border-gray-600" v-for="row in rows.data" valign="top">
              <span v-if="row === null" class="null-value"><i>NULL</i></span>
              <span v-else class="font-semibold">{{ row }}</span>
            </td>
          </tr>
        </table>

      </div>
    </div>

    <a @click="$emit('editRow')" v-if="from == 'tabledata'"
      class="mt-4 px-2  py-2 mr-2 inline-flex items-center">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 mr-2 fill-current text-gray-600">
        <path class="text-gray-600"
              d="M4 14a1 1 0 0 1 .3-.7l11-11a1 1 0 0 1 1.4 0l3 3a1 1 0 0 1 0 1.4l-11 11a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-3z" />
        <rect width="20" height="2" x="2" y="20" class="text-gray-500" rx="1" />
      </svg>
      <span>Edit</span>
    </a>

  </div>
</template>


<script>

  export default {
    name: 'RowSidebar',
    props: ['sidebarisopen', 'rowdata', 'columndata', 'columntabledata', 'from'],
    data() {
      return {
        column_order: 'default',
        only_show_differences: false,
      }
    },

    created() {
      document.addEventListener('keydown', this.triggerKeyDown);
    },

    beforeDestroy() {
      document.removeEventListener('keydown', this.triggerKeyDown);
    },

    computed: {
      rows_data: function () {
        let row_data_order = [];
        let vue_instance   = this;
        this.columndata.forEach(function (column, index) {
          let row_data = {'field': column, 'data': []};
          if(typeof vue_instance.columntabledata !== "undefined") {
            row_data.table = vue_instance.columntabledata[index];
          }

          vue_instance.rowdata.forEach(function (row) {
            row_data.data.push(row[index]);
          });

          row_data_order.push(row_data);
        });

        if(this.only_show_differences) {
          row_data_order = row_data_order.filter(function(row_data) {
            return !(row_data.data.every((row_value, key, array) => row_value === array[0]));
          });
        }

        if (this.column_order == 'alphabetical') {
          row_data_order.sort(function (a, b) {
            if (a.field < b.field) return -1;
            else if (a.field > b.field) return 1;
            else return 0;
          });
        }

        return row_data_order;
      },

      getSidebarWidthClass: function() {
        if(this.rowdata.length == 1) {
          return 'width-1';
        }
        if(this.rowdata.length == 2) {
          return 'width-2';
        }

        return 'width-3';
      }
    },

    methods: {
      triggerKeyDown: function (evt) {
        if (evt.key === 'Escape') {
          this.close();
          evt.preventDefault();
        }
      },

      toggleColumnOrder: function () {
        if (this.column_order == 'default') {
          this.column_order = 'alphabetical';
        } else {
          this.column_order = 'default';
        }
      },

      toggleDifferences: function () {
        this.only_show_differences = !this.only_show_differences;
      },

      close() {
        this.only_show_differences = false; // reset
        this.$emit('closeRowSidebar');
      }

    }
  }
</script>


<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

  .row-data {
    /** scrollbar on the left **/
    direction: rtl;
  }

  .row-data div {
    /** revert childs direction **/
    direction: ltr;
  }

  html {
    --scrollbarBG: transparent;
    --thumbBG:     #90A4AE;
  }

  .row-data::-webkit-scrollbar {
    width:  7px;
    height: 7px;
  }

  .row-data {
    scrollbar-width: 7px;
    scrollbar-color: var(--thumbBG) var(transparent);
  }

  .row-data::-webkit-scrollbar-track {
    background: var(transparent);
  }

  .row-data::-webkit-scrollbar-thumb {
    background-color: var(--thumbBG);
    border-radius:    5px;
    border:           3px solid var(transparent);
  }

  .sidebar.width-1 {
    width: 550px;
  }

  .sidebar.width-2 {
    width: 700px;
  }

  .sidebar.width-3 {
    width: 850px;
  }


</style>
