<template>
  <div class="page-content-container">
    <div>

      <div class="table-page-header">
        <h2>
          {{ tableid }}
        </h2>
        <table-nav :tableid="tableid"></table-nav>
        <div></div>
      </div>

      <div class="w-full flex items-start">

        <div class="relative w-full">

          <p class="bg-light-100 text-gray-400 px-2 py-2 block text-center border-t border-b border-light-200">Not yet available</p>

        </div>

      </div>

    </div>
  </div>
</template>

<script>

import TableNav from '@/components/TableNav.vue'
import HandleApiError from '@/mixins/HandleApiError.js'
import ApiUrl from "@/mixins/ApiUrl";

export default {
  name: 'TableForeignKeys',
  props: ['tableid'],
  data() {
    return {

    }
  },

  components: {
    TableNav
  },

  mixins: [
    HandleApiError,
    ApiUrl
  ],

  computed: {
    active_database() {
      return this.$store.state.activeDatabase;
    },
  },

  watch: {
    // force update on route change
    '$route.params': function () {
      this.$nextTick();
    }
  },


  methods: {


  }
}
</script>
