<template>
  <div class="page-content-container">
    <div>

      <div class="table-page-header foreign-keys-container">
        <h2>
          {{ tableid }}
        </h2>
        <table-nav :tableid="tableid"></table-nav>
        <div></div>
      </div>

      <flash-message></flash-message>

      <div v-if="is_fetching_data === false">


        <div v-cloak v-if="foreign_keys.length == 0">
          <p class="bg-light-100 text-gray-400 px-2 py-2 inline-block">No foreign keys found</p>
        </div>


        <table cellspacing="0" class="items-table with-actions" v-if="foreign_keys.length > 0">
          <thead>
          <tr>
            <th>Name</th>
            <th>Column(s)</th>
            <th>Foreign key constraints</th>
            <th>On delete</th>
            <th>On update</th>
            <th></th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(foreign_key, foreign_key_key) in foreign_keys">
            <td>{{ foreign_key.name }}</td>
            <td>
              <p v-for="column in foreign_key.columns">
                <span class="font-semibold">{{ column.column_name }}</span>
              </p>
            </td>
            <td>
              <p v-for="column in foreign_key.columns">
                <span class="text-gray-500">{{ column.ref_table }}.</span>{{ column.ref_column_name }}
              </p>
            </td>
            <td>{{ foreign_key.on_delete }}</td>
            <td>{{ foreign_key.on_update }}</td>
            <td>
              <div class="flex">
                <router-link
                    :to="{ name: 'editforeignkey', params: { database: active_database, tableid: tableid, foreignkeyname: foreign_key.name } }"
                    class="btn btn-icon ml-2 show-focus">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 fill-current">
                    <path class="text-light-300"
                          d="M4 14a1 1 0 0 1 .3-.7l11-11a1 1 0 0 1 1.4 0l3 3a1 1 0 0 1 0 1.4l-11 11a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-3z"></path>
                    <rect width="20" height="2" x="2" y="20" class="text-light-200" rx="1"></rect>
                  </svg>
                </router-link>
                <a @click="confirmDeleteForeignKey(foreign_key_key)" class="btn btn-icon ml-2">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 fill-current">
                    <path class="text-light-200"
                          d="M5 5h14l-.89 15.12a2 2 0 0 1-2 1.88H7.9a2 2 0 0 1-2-1.88L5 5zm5 5a1 1 0 0 0-1 1v6a1 1 0 0 0 2 0v-6a1 1 0 0 0-1-1zm4 0a1 1 0 0 0-1 1v6a1 1 0 0 0 2 0v-6a1 1 0 0 0-1-1z"></path>
                    <path class="text-light-300"
                          d="M8.59 4l1.7-1.7A1 1 0 0 1 11 2h2a1 1 0 0 1 .7.3L15.42 4H19a1 1 0 0 1 0 2H5a1 1 0 1 1 0-2h3.59z"></path>
                  </svg>
                </a>
              </div>
            </td>
          </tr>
          </tbody>
        </table>

        <router-link :to="{ name: 'addforeignkey', params: { database: active_database, tableid: tableid } }"
                     class="btn mt-4">
          Add foreign key
        </router-link>

      </div>

      <confirm-modal v-if="confirm_modal_open" v-on:close="closeConfirmModal()"
                     v-on:confirm="confirmConfirmModal()">
        {{ confirm_modal_message }}
      </confirm-modal>

    </div>
  </div>
</template>

<script>

import TableNav from '@/components/TableNav.vue'
import ApiMixin from "@/mixins/Api";
import FlashMessage from "@/components/FlashMessage";
import ConfirmModal from "@/components/ConfirmModal";
import ConfirmModalMixin from "@/mixins/ConfirmModal";

export default {
  name: 'TableForeignKeys',
  props: ['tableid'],
  data() {
    return {
      foreign_keys: [],
      is_fetching_data: false,
      modal_foreign_key_key: null,
    }
  },

  components: {
    ConfirmModal,
    FlashMessage,
    TableNav
  },

  mixins: [
    ApiMixin,
    ConfirmModalMixin
  ],

  mounted() {
    this.getTableForeignKeys();
  },

  computed: {
    active_database() {
      return this.$store.state.activeDatabase;
    },
  },


  methods: {

    getTableForeignKeys() {

      this.is_fetching_data = true;
      let api_url_params    = {'db': this.active_database, 'tablename': this.tableid};
      let api_url           = this.buildApiUrl('foreignkey/list', api_url_params);

      this.$http.get(api_url).then(response => {
        this.foreign_keys     = response.data.data.data;
        this.is_fetching_data = false;

      }).catch(error => {
        this.handleApiError(error);
      })
    },

    confirmDeleteForeignKey(foreign_key_key) {
      this.confirm_modal_message = "Delete foreign key '" + this.foreign_keys[foreign_key_key].name + "'";
      this.confirm_modal_open    = true;
      this.confirm_modal_action  = 'deleteForeignKey';
      this.modal_foreign_key_key = foreign_key_key;
    },

    deleteForeignKey() {
      if (this.modal_foreign_key_key === null) return;

      let params = new URLSearchParams();
      params.append('foreign_key_name', this.foreign_keys[this.modal_foreign_key_key].name);

      let api_url_params = {'db': this.active_database, 'tablename': this.tableid};
      let api_url        = this.buildApiUrl('foreignkey/drop', api_url_params);

      let vue_instance = this;

      this.$http.post(api_url, params).then(response => {

        if (this.validateApiPostResponse(response) === false) return;

        if (response.data.data.result == 'error') {
          vue_instance.query_result = {type: 'error', message: response.data.data.message};
          scroll(0, 0);
          return;
        }

        this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", {
          type: 'success',
          message: 'Foreign key dropped',
          query: response.data.data.query
        });
        this.refreshPage();
      }).catch(error => {
        this.handleApiError(error);
      });

      this.modal_foreign_key_key = null;
    },

  }
}
</script>

<style scoped>
.foreign-keys-container {
  min-width: 900px;
}
</style>

