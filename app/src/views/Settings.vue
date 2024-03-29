<template>
  <div class="page-content-container mt-4">

    <ul class="pr-2 mt-12 w-64 mr-8">

      <li class="active">
        <router-link :to="{ name: 'settings' }">
          Settings
        </router-link>
      </li>

      <li class="">
        <router-link :to="{ name: 'help' }">
          Help
        </router-link>
      </li>
    </ul>

    <div>

      <div class="flex justify-between items-center mb-5">
        <h2 class="text-xl">
          Settings
        </h2>
      </div>

      <flash-message></flash-message>


      <div class="w-2/3">

        <form method="post" @submit.prevent="saveSettings()" autocomplete="off" refs="settings-form">

          <div class="vertical-form">

            <div class="input-row">
              <div
                  class="label-box w-3/5 leading-tight py-1">
                <div>Default table column order</div>
              </div>
              <select name="default_table_column_order" v-model="default_table_column_order"
                      class="default-select flex-grow" v-on:keyup.esc="focusToApp">
                <option value="">Default</option>
                <option value="primary_asc">Primary key: ascending (lowest id / first row)</option>
                <option value="primary_desc">Primary key: descending (highest id / last row)</option>
              </select>
            </div>

            <div class="input-row">
              <div class="label-box w-3/5 leading-tight py-1">
                Default rows per page
                <span class="text-gray-600 text-xs block">Between 10 and 300</span>
              </div>
              <input type="number" step="1" min="10" max="300" class="default-number-input w-32"
                     v-on:keyup.esc="focusToApp" v-model="default_rows_per_page">
            </div>

            <div class="input-row">
              <div
                  class="label-box w-3/5 leading-tight py-1">
                <div>
                  <span>Cell text display limit</span>
                  <span class="text-gray-600 text-xs block">Set to 0 for no limit</span>
                </div>
              </div>
              <input type="number" step="1" min="0" max="500" class="default-number-input w-32"
                     v-on:keyup.esc="focusToApp" v-model="cell_text_display_limit">
            </div>

            <div class="flex">
              <div class="w-3/5 mr-2"></div>
              <button class="btn show-focus mt-4" @click="saveSettings()">Save</button>
            </div>

          </div>

        </form>

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
      default_rows_per_page: 0,
      cell_text_display_limit: 0,
    }
  },

  created() {
    this.default_table_column_order = this.store_default_table_column_order;
    this.default_rows_per_page      = this.store_default_rows_per_page;
    this.cell_text_display_limit    = this.store_cell_text_display_limit;
  },

  components: {
    FlashMessage,
  },

  computed: {
    store_default_table_column_order() {
      return this.$store.getters["settings/getSetting"]('default_table_column_order');
    },
    store_default_rows_per_page() {
      return this.$store.getters["settings/getSetting"]('default_rows_per_page');
    },
    store_cell_text_display_limit() {
      return this.$store.getters["settings/getSetting"]('cell_text_display_limit');
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
      this.$store.commit("settings/set_settings", new_settings);
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
