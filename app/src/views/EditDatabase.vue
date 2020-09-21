<template>
  <div class="page-content-container">

    <div>

      <div v-if="page_is_edit">
        <h2>
          {{ tableid }}
        </h2>
      </div>

      <h1 class="text-xl mb-4">
        <span v-if="page_is_create">Create new database</span>
        <span v-else>Edit database</span>
      </h1>

      <div v-if="query_result.result == 'error'" class="error-box mb-4">
        {{ query_result.message }}
      </div>

      <form method="post" autocomplete="off">

        <div class="mb-8">
          <div class="flex w-full mb-1">

            <div class="bg-dark-400 flex justify-between items-center w-64 pl-3 flex-shrink-0 relative flex-wrap mr-2">
              <div>Database name</div>
            </div>

            <input type="text" class="default-text-input w-64" v-on:keyup.esc="focusToApp" v-model="database_name">
          </div>

          <div class="flex w-full mb-1">

            <div class="bg-dark-400 flex justify-between items-center w-64 pl-3 flex-shrink-0 relative flex-wrap mr-2">
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
  import HandleApiError from '@/mixins/HandleApiError.js'
  import ApiUrl from "@/mixins/ApiUrl";

  export default {
    name: 'editdatabase',
    props: ['database'],
    data() {
      return {
        endpoint_create_database: 'database/create',
        endpoint_alter_database: 'database/alter',
        endpoint_collations: 'server/collations',
        collations: [],
        database_name: '',
        collation: '',
        query_result: {},
      }
    },

    mixins: [
      HandleApiError,
      ApiUrl
    ],

    mounted() {
      this.getCollations();
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

      getCollations() {
        let api_url = this.buildApiUrl(this.endpoint_collations, {});

        this.$http.get(api_url).then(response => {
          let reponse_data = response.data.data;
          this.collations           = reponse_data.collations;
          if (this.page_is_edit) {
            // this.database_name                      = reponse_data.database_data.name;
            // this.collation                       = reponse_data.table_data.collation;
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
        if(this.page_is_create) {
          api_url = this.buildApiUrl(this.endpoint_create_database, api_url_params);
        }
        else {
          api_url = this.buildApiUrl(this.endpoint_alter_database, api_url_params);
        }

        let vue_instance = this;

        let params = new URLSearchParams();
        params.append('database_name', this.database_name);
        params.append('collation', this.collation);

        this.$http.post(api_url, params).then(response => {
          vue_instance.query_result = response.data.data;
          if (vue_instance.query_result.result == 'success' && this.$route.name == 'adddatabase') {
            this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", 'Database created.');
            this.$store.commit("flashmessage/ADD_FLASH_QUERY", vue_instance.query_result.query);
            vue_instance.$router.push({name: 'database', params: {'database': vue_instance.database_name}});
          } else if (vue_instance.query_result.result == 'success') {
            let message = 'Database updated';
            this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", message);
            this.$store.commit("flashmessage/ADD_FLASH_QUERY", vue_instance.query_result.query);
            this.$store.dispatch('refreshTables');
            vue_instance.$router.push({name: 'table', params: { database: this.active_database, tableid: vue_instance.table_name}});
          }
          scroll(0, 0);
        }).catch(error => {
          this.handleApiError(error);
        })
      },

    }
  }
</script>
