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

    <h2 class="font-semibold text-xl mb-4">Row</h2>

    <div class="row-data overflow-y-scroll overflow-x-hidden pl-2 -ml-2">
      <div>

        <div class="flex w-full border-b border-gray-600" v-for="(row) in Object.keys(rowdata)">
          <div class="w-1/2 px-1 py-1">{{ row }}</div>
          <div class="w-1/2 px-1 py-1 font-semibold">{{ rowdata[row] }}</div>
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
      return {}
    },

    created() {
      document.addEventListener('keydown', this.triggerKeyDown);
    },

    beforeDestroy() {
      document.removeEventListener('keydown', this.triggerKeyDown);
    },

    methods: {
      triggerKeyDown: function (evt) {
        if (evt.key === 'Escape') {
          this.$emit('closeRowSidebar')
          evt.preventDefault();
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
