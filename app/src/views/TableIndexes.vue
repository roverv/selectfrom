<template>
  <div class="page-content-container">
    <div>

      <div class="table-page-header indexes-container">
        <h2>
          {{ tableid }}
        </h2>
        <table-nav :tableid="tableid"></table-nav>
        <div></div>
      </div>

      <flash-message></flash-message>

      <div v-if="is_fetching_data === false">

        <div v-cloak v-if="indexes.length == 0">
          <p class="bg-light-100 text-gray-400 px-2 py-2 inline-block">No indexes found</p>
        </div>


        <table cellspacing="0" class="items-table with-actions" v-if="indexes.length > 0">
          <thead>
          <tr>
            <th>Type</th>
            <th>Name</th>
            <th>Unique</th>
            <th>Column(s)</th>
            <th></th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(index, index_key) in indexes">
            <td>{{ index.type }}</td>
            <td>{{ index.name }}</td>
            <td>{{ index.is_unique ? 'Yes' : 'No' }}</td>
            <td>
              <p v-for="column in index.columns">
                {{ column.name }}
                <span v-if="column.length">
                    ({{ column.length }})
                  </span>
              </p>
            </td>
            <td>
              <div class="flex">
                <router-link :to="{ name: 'editindex', params: { database: active_database, tableid: tableid, indexname: index.name } }" class="btn btn-icon ml-2 show-focus">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 fill-current">
                    <path class="text-light-300"
                          d="M4 14a1 1 0 0 1 .3-.7l11-11a1 1 0 0 1 1.4 0l3 3a1 1 0 0 1 0 1.4l-11 11a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-3z"></path>
                    <rect width="20" height="2" x="2" y="20" class="text-light-200" rx="1"></rect>
                  </svg>
                </router-link>
                <a @click="confirmDeleteIndex(index_key)" class="btn btn-icon ml-2">
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

        <router-link :to="{ name: 'addindex', params: { database: active_database, tableid: tableid } }"
                     class="btn mt-4">
          Add index
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
  name: 'TableIndexes',
  props: ['tableid'],
  data() {
    return {
      indexes: [],
      is_fetching_data: false,
      modal_index_key: null,
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
    this.getTableIndexes();
  },

  computed: {
    active_database() {
      return this.$store.state.activeDatabase;
    },
  },


  methods: {

    getTableIndexes() {

      this.is_fetching_data = true;
      let api_url_params    = {'db': this.active_database, 'tablename': this.tableid};
      let api_url           = this.buildApiUrl('index/list', api_url_params);

      this.$http.get(api_url).then(response => {
        this.indexes          = response.data.data.data;
        this.is_fetching_data = false;

      }).catch(error => {
        this.handleApiError(error);
      })
    },

    confirmDeleteIndex(index_key) {
      this.confirm_modal_message = "Delete index '" + this.indexes[index_key].name + "'";
      this.confirm_modal_open   = true;
      this.confirm_modal_action = 'deleteIndex';
      this.modal_index_key = index_key;
    },

    deleteIndex() {
      if(this.modal_index_key === null) return;

      let params = new URLSearchParams();
      params.append('index_name', this.indexes[this.modal_index_key].name);

      let api_url_params = {'db': this.active_database, 'tablename': this.tableid};
      let api_url = this.buildApiUrl('index/drop', api_url_params);

      let vue_instance = this;

      this.$http.post(api_url, params).then(response => {

        if(this.validateApiPostResponse(response) === false) return;

        if(response.data.data.result == 'error') {
          vue_instance.query_result = {type: 'error', message: response.data.data.message};
          scroll(0,0);
          return;
        }

        this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", {
          type: 'success',
          message: 'Index dropped',
          query: response.data.data.query
        });
        this.refreshPage();
      }).catch(error => {
        this.handleApiError(error);
      });

      this.modal_index_key = null;
    },

  }
}
</script>

<style scoped>
.indexes-container {
  min-width: 900px;
}
</style>
