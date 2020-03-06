<template>
  <div
    class="sidebar fixed right-0 top-0 bg-white h-screen z-50 bg-dark-600 p-4 pt-12 pb-8 flex flex-col border-l border-light-100 "
    :class="{ 'hidden' : !sidebarisopen }"
    style="width: 500px; box-shadow: -3px 0px 10px 0px rgba(0,0,0,0.2);">

    <a @click="$emit('closeRowSidebar')" class="absolute left-0 top-0 ml-2 mt-2">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-8 fill-current text-gray-600">
        <path class="secondary" fill-rule="evenodd"
              d="M15.78 14.36a1 1 0 0 1-1.42 1.42l-2.82-2.83-2.83 2.83a1 1 0 1 1-1.42-1.42l2.83-2.82L7.3 8.7a1 1 0 0 1 1.42-1.42l2.83 2.83 2.82-2.83a1 1 0 0 1 1.42 1.42l-2.83 2.83 2.83 2.82z" />
      </svg>
    </a>

    <div class="flex items-center justify-between mb-4">
      <h2 class="font-semibold text-xl">Row</h2>

      <a @click="toggleColumnOrder()" class="flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 mr-2 fill-current">
          <path class="text-gray-600"
                d="M7 18.59V9a1 1 0 0 1 2 0v9.59l2.3-2.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-4-4a1 1 0 1 1 1.4-1.42L7 18.6z"></path>
          <path class="primary"
                d="M17 5.41V15a1 1 0 1 1-2 0V5.41l-2.3 2.3a1 1 0 1 1-1.4-1.42l4-4a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1-1.4 1.42L17 5.4z"></path>
        </svg>
        Sort&nbsp;
        <span v-if="column_order == 'default'">alphabetical</span>
        <span v-else>default</span>
      </a>
    </div>


    <div class="row-data overflow-y-scroll overflow-x-hidden pl-2 -ml-2">
      <div>

        <div class="flex w-full border-b border-gray-600" v-for="(row) in row_data">
          <div class="w-1/2 px-1 py-1">{{ row.field }}</div>
          <div class="w-1/2 px-1 py-1 font-semibold">{{ row.data }}</div>
        </div>

      </div>
    </div>

    <a
      class="mt-4 px-2  py-2 mr-2 inline-flex items-center"
      href="">
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
    props: ['sidebarisopen', 'rowdata'],
    data() {
      return {
        'column_order': 'default',
      }
    },

    created() {
      document.addEventListener('keydown', this.triggerKeyDown);
    },

    beforeDestroy() {
      document.removeEventListener('keydown', this.triggerKeyDown);
    },

    computed: {
      row_data: function() {
        let row_data_keys;
        if(this.column_order == 'default') {
          row_data_keys = Object.keys(this.rowdata);
        }
        else {
          row_data_keys = Object.keys(this.rowdata).sort();
        }

        let row_data_order = [];
        let vue_instance = this;
        row_data_keys.forEach(function (column) {
          row_data_order.push({'field': column, 'data': vue_instance.rowdata[column]});
        });
        return row_data_order;
      }
    },

    methods: {
      triggerKeyDown: function (evt) {
        if (evt.key === 'Escape') {
          this.$emit('closeRowSidebar')
          evt.preventDefault();
        }
      },

      toggleColumnOrder: function() {
        if(this.column_order == 'default') {
          this.column_order = 'alphabetical';
        }
        else {
          this.column_order = 'default';
        }
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


</style>
