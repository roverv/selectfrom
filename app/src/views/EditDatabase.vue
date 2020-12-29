<template>
  <div class="page-content-container">

    <div>

      <div v-if="page_is_edit">
        <h2 class="text-xl mb-4">
          {{ database }}
        </h2>
      </div>

      <h1 class="text-xl mb-4">
        <span v-if="page_is_create">Create new database</span>
        <span v-else>Edit database</span>
      </h1>

      <result-message :message="query_result"></result-message>

      <form method="post" autocomplete="off">

        <div class="vertical-form">

          <div class="input-row">
            <div class="label-box">
              <div>Database name</div>
            </div>
            <input type="text" class="default-text-input w-64" v-on:keyup.esc="focusToApp" v-model="database_name"
                   v-focus>
          </div>

          <div class="input-row">
            <div class="label-box">
              <div>Collation</div>
            </div>
            <select name="Collation" class="default-select w-64" v-model="collation">
              <option value=""></option>
              <optgroup v-for="(collation_group, charset) in collations" :label="charset">
                <option v-for="collation in collation_group">
                  {{ collation }}
                </option>
              </optgroup>
            </select>
          </div>

        </div>

      </form>

      <div class="flex justify-center">
        <button class="btn show-focus" @click="saveDatabase()">Save</button>
      </div>

    </div>
  </div>
</template>

<script>

import sqlFormatter from "sql-formatter";
import ApiMixin from "@/mixins/Api";
import ResultMessage from "@/components/ResultMessage";

export default {
  name: 'editdatabase',
  components: {ResultMessage},
  props: ['database'],
  data() {
    return {
      endpoint_create_database: 'database/create',
      endpoint_alter_database: 'database/alter',
      endpoint_databbase_creation_data: 'database/creationdata',
      collations: [],
      database_name: ((typeof this.database !== 'undefined') ? this.database : ''),
      collation: '',
      query_result: {},
    }
  },

  mixins: [
    ApiMixin
  ],

  mounted() {
    this.getDatabaseCreationData();
  },

  computed: {
    active_database() {
      return this.$store.state.activeDatabase;
    },
    page_is_create() {
      return (this.$route.name == 'adddatabase');
    },
    page_is_edit() {
      return (this.$route.name == 'editdatabase');
    }
  },

  filters: {
    format: function (query_string) {
      return sqlFormatter.format(query_string);
    }
  },

  methods: {

    getDatabaseCreationData() {
      let api_url_params = {};
      if (this.page_is_edit) {
        api_url_params.db = this.active_database;
      }

      let api_url = this.buildApiUrl(this.endpoint_databbase_creation_data, api_url_params);

      this.$http.get(api_url).then(response => {
        let reponse_data = response.data.data;
        this.collations  = reponse_data.collations;
        if (this.page_is_edit) {
          this.collation = reponse_data.collation;
        }
      }).catch(error => {
        this.handleApiError(error);
      })
    },

    focusToApp() {
      document.getElementById('app').focus();
    },

    saveDatabase() {
      let api_url_params = {};
      if (this.page_is_edit) {
        api_url_params.db = this.active_database;
      }

      let api_url = '';
      if (this.page_is_create) {
        api_url = this.buildApiUrl(this.endpoint_create_database, api_url_params);
      } else {
        api_url = this.buildApiUrl(this.endpoint_alter_database, api_url_params);
      }

      let vue_instance = this;

      let params = new URLSearchParams();
      params.append('database_name', this.database_name);
      params.append('collation', this.collation);

      this.$http.post(api_url, params).then(response => {

        if (this.validateApiPostResponse(response) === false) return;

        if (response.data.data.result == 'error') {
          vue_instance.query_result = {type: 'error', message: response.data.data.message};
          scroll(0, 0);
          return;
        }

        vue_instance.query_result = response.data.data;
        if (vue_instance.query_result.result == 'success' && this.$route.name == 'adddatabase') {
          this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", {
            type: 'success',
            message: 'Database created.',
            query: vue_instance.query_result.query
          });
          this.$store.dispatch('refreshDatabases');
          vue_instance.$router.push({name: 'database', params: {'database': vue_instance.database_name}});
        } else if (vue_instance.query_result.result == 'success' && this.$route.name == 'editdatabase') {
          if (vue_instance.query_result.query == '') {
            this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", {type: 'success', message: 'No changes'});
            vue_instance.$router.push({name: 'database', params: {'database': vue_instance.database_name}});
            return;
          } else {
            let message = 'Database updated';
            this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", {
              type: 'success',
              message: message,
              query: vue_instance.query_result.query
            });
            this.$store.dispatch('refreshDatabases');
            // tables will be refreshed upon page visit
            vue_instance.$router.push({name: 'database', params: {'database': vue_instance.database_name}});
          }
        }
        scroll(0, 0);
      }).catch(error => {
        this.handleApiError(error);
      })
    },

  }
}
</script>
