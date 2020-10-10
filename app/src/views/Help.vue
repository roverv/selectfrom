<template>
  <div class="page-content-container mt-4">

    <ul class="pr-2 mt-12 w-64 mr-8">

      <li class="">
        <router-link :to="{ name: 'settings' }">
          Information
        </router-link>
      </li>

      <li class="">
        <router-link :to="{ name: 'settings' }">
          Settings
        </router-link>
      </li>

      <li class="active">
        <router-link :to="{ name: 'help' }">
          Help
        </router-link>
      </li>
    </ul>

    <div>

      <div class="flex justify-between items-center mb-5">
        <h2 class="text-xl">
          Help
        </h2>
      </div>


      <div class="w-2/3">

        <h3 class="text-lg mb-4  border-b border-light-200">Global shortcuts</h3>

        <div class="flex items-center w-full mb-1">
          <div class="border-l-2 flex-shrink-0 border-r-2 border-highlight-700 bg-light-100 w-10 text-center text-2xl h-10 inline-flex justify-center items-center mb-2 mr-3">
            F
          </div>

          <div>Find - quickly search, find and go to a table/column/row</div>
        </div>


        <div class="flex items-center w-full mb-1">
          <div class="border-l-2 border-r-2 border-highlight-700 bg-light-100 w-10 text-center text-2xl h-10 inline-flex justify-center items-center mb-2 mr-3">
            E
          </div>

          <div>Recent tables</div>
        </div>

        <div class="flex items-center w-full mb-1">
          <div class="border-l-2 border-r-2 border-highlight-700 bg-light-100 w-10 text-center text-2xl h-10 inline-flex justify-center items-center mb-2 mr-3">
            D
          </div>

          <div>Databases</div>
        </div>

        <div class="flex items-center w-full mb-1">
          <div class="border-l-2 border-r-2 border-highlight-700 bg-light-100 w-10 text-center text-2xl h-10 inline-flex justify-center items-center mb-2 mr-3">
            Q
          </div>

          <div>Go to query page</div>
        </div>

        <div class="flex items-center w-full mb-1">
          <div class="border-l-2 border-r-2 border-highlight-700 bg-light-100 w-10 text-center text-2xl h-10 inline-flex justify-center items-center mb-2 mr-3">
            H
          </div>

          <div>Query history</div>
        </div>

        <div class="flex items-center w-full mb-1">
          <div class="border-l-2 flex-shrink-0 border-r-2 border-highlight-700 bg-light-100 w-10 text-center text-2xl h-10 inline-flex justify-center items-center mb-2 mr-3">
            U
          </div>

          <div>Move up one level in the hierarchy <br> Server &gt; database  &gt; table  &gt; page</div>
        </div>

        <div class="flex items-center w-full mb-1">
          <div class="border-l-2 flex-shrink-0 border-r-2 border-highlight-700 bg-light-100 w-10 text-center text-2xl h-10 inline-flex justify-center items-center mb-2 mr-3">
            R
          </div>

          <div>Refresh page - reload the current page, but does not renew cached data</div>
        </div>

      </div>


    </div>
  </div>
</template>

<script>

import FlashMessage from "@/components/FlashMessage";
import {clone} from "@/util";

export default {
  name: 'Settings',

  data() {
    return {
      default_table_column_order: '',
      default_rows_per_page : 0,
      cell_text_display_limit : 0,
    }
  },

  created() {
    this.default_table_column_order = this.store_settings.default_table_column_order;
    this.default_rows_per_page = this.store_settings.default_rows_per_page;
    this.cell_text_display_limit = this.store_settings.cell_text_display_limit;
  },

  components: {
    FlashMessage,
  },

  computed: {
    store_settings() {
      return this.$store.getters["settings/getAll"];
    }
  },

  methods: {

    saveSettings() {
      let default_rows_per_page = parseInt(this.default_rows_per_page);
      if (default_rows_per_page < 10) {
        default_rows_per_page = 10;
      } else if (default_rows_per_page > 500) {
        default_rows_per_page = 500;
      }

      let new_settings = {
        'default_table_column_order': this.default_table_column_order,
        'default_rows_per_page': default_rows_per_page,
        'cell_text_display_limit': parseInt(this.cell_text_display_limit),
      }

      this.$store.commit("settings/SET_SETTINGS", new_settings);
      this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", {
        type: 'success',
        message: 'Settings saved'
      });
      this.refreshPage();
    }

  },
}
</script>

<style scoped>

li {
  @apply px-2 py-1 mx-2 my-2 w-auto;
}

li.active {
  @apply border-l-2 border-highlight-700 bg-light-100;
}

</style>
