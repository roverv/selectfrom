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


      <div>

        <h3 class="text-lg mb-4  border-b border-light-200">Global shortcuts</h3>

        <div class="shortcut-box">
          <div class="shortcut-key">F</div>

          <div class="shortcut-description">
            <span>Find</span>
            <span class="text-light-300">Quickly search, find and go to a table/column/row</span>
            <span class="text-light-300">When opened, click on the question mark to see the available options</span>
          </div>
        </div>


        <div class="shortcut-box">
          <div class="shortcut-key">E</div>
          <div class="shortcut-description">
            <span>Recent tables</span>
            <span class="text-light-300">Scroll through your recently visited tables</span>
          </div>
        </div>

        <div class="shortcut-box">
          <div class="shortcut-key">D</div>
          <div class="shortcut-description">
            <span>Databases</span>
            <span class="text-light-300">Switch to any database</span>
          </div>
        </div>

        <div class="shortcut-box">
          <div class="shortcut-key">Q</div>
          <div class="shortcut-description">
            <span>Query</span>
            <span class="text-light-300">Go to query page</span>
          </div>
        </div>

        <div class="shortcut-box">
          <div class="shortcut-key">H</div>
          <div class="shortcut-description">
            <span>Query history</span>
            <span class="text-light-300">Scroll through your recently executed queries</span>
          </div>
        </div>

        <div class="shortcut-box">
          <div class="shortcut-key">U</div>
          <div class="shortcut-description">
            <span>Up</span>
            <span class="text-light-300">Move up one level in the hierarchy: Server &gt; database &gt; table &gt; page</span>
          </div>
        </div>

        <div class="shortcut-box">
          <div class="shortcut-key">R</div>
          <div class="shortcut-description">
            <span>Refresh page</span>
            <span class="text-light-300">Reload the current page, but does not renew cached data</span>
          </div>
        </div>

        <div class="shortcut-box">
          <div class="shortcut-key">C</div>
          <div class="shortcut-description">
            <span>Context menu</span>
            <span class="text-light-300">Opens a page specific context menu to quickly execute certain actions</span>
          </div>
        </div>

        <div class="shortcut-box">
          <div class="shortcut-key long">Esc</div>
          <div class="shortcut-description">
            <span>Close</span>
            <span class="text-light-300">Close modal, close sidebar or remove focus from input</span>
          </div>
        </div>

        <h3 class="text-lg mb-4  border-b border-light-200">Table view shortcuts</h3>

        <p class="mb-4">
          <strong>Navigation shortcuts</strong>
        </p>

        <div class="shortcut-box">
          <div class="shortcut-key">1</div>

          <div class="shortcut-description">
            <span class="text-light-500">To table data</span>
          </div>
        </div>

        <div class="shortcut-box">
          <div class="shortcut-key">2</div>
          <div class="shortcut-description">
            <span class="text-light-500">To structure</span>
          </div>
        </div>

        <div class="shortcut-box">
          <div class="shortcut-key">3</div>
          <div class="shortcut-description">
            <span class="text-light-500">To indexes</span>
          </div>
        </div>

        <div class="shortcut-box">
          <div class="shortcut-key">4</div>
          <div class="shortcut-description">
            <span class="text-light-500">To foreign keys</span>
          </div>
        </div>

        <div class="shortcut-box">
          <div class="shortcut-key">5</div>
          <div class="shortcut-description">
            <span class="text-light-500">To add row</span>
          </div>
        </div>

        <p class="mb-4">
          <strong>Table shortcuts</strong>
        </p>

        <div class="shortcut-box">
          <div class="shortcut-key">v</div>

          <div class="shortcut-description">
            <span>View switch</span>
            <span class="text-light-300">Switch between table view and single row view</span>
          </div>
        </div>

        <div class="shortcut-box">
          <div class="shortcut-key long">Ctrl</div>
          <div class="shortcut-key long">Click</div>

          <div class="shortcut-description">
            <span>Show sidebar</span>
            <span class="text-light-300">CTRL + click on a row to show the row vertically in the sidebar. Click multiple rows for comparison.</span>
          </div>
        </div>

        <div class="shortcut-box">
          <div class="shortcut-key">n</div>

          <div class="shortcut-description">
            <span>Next page</span>
            <span class="text-light-300">Go to next batch of rows</span>
          </div>
        </div>

        <div class="shortcut-box">
          <div class="shortcut-key">p</div>

          <div class="shortcut-description">
            <span>Previous page</span>
            <span class="text-light-300">Go to previous batch of rows</span>
          </div>
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
