<template>
  <div class="page-content-container">
    <div class="import">

      <h1 class="text-xl mb-4">Import</h1>

      <result-message :message="import_result"></result-message>

      <div class="vertical-form mb-2">

        <div class="input-row">
          <div class="label-box justify-start">
            <div>File</div>
            <span class="text-gray-600 ml-2">(.sql or .gzip)</span>
          </div>
          <input type="file" name="import_file" class="default-text-input" ref="importfile"
                 v-on:change="handleFileUpload()">
        </div>

      </div>

      <div class="flex  mb-3 mt-5">
        <a @click="submitImport()" class="btn">
          <div v-if="is_importing_data" class="flex">
            <spinner class="w-5 mr-2"></spinner>
            Processing...
          </div>
          <span v-else>Import</span>
        </a>
      </div>


      <div class="progress-bar mx-auto" v-if="progress_bar.active">
        <div class="flex justify-between">
          <h3 class="mb-1">{{ progress_bar.text }}</h3>
          <div>{{ progress_bar.percentage }}%</div>
        </div>
        <div class="flex">
          <div v-for="percentage_bar in ([10,20,30,40,50,60,70,80,90,100])" class="border w-5 h-5 mx-1"
               :class="[progress_bar.percentage >= percentage_bar ? 'bg-highlight-300 border-highlight-400' : 'bg-light-50 border-light-200' ]">
          </div>
        </div>
      </div>

      <hr class="border-light-200 my-4" v-if="query_results.length > 1">

      <div v-if="query_results.length > 1 && query_result_summary_success > 0" class="success-box mb-3">
        {{ query_result_summary_success }} successful executed
        <span v-if="query_result_summary_success == 1">query</span>
        <span v-else>queries</span>
      </div>

      <div v-if="query_results.length > 1 && query_result_summary_error > 0" class="error-box mb-3">
        {{ query_result_summary_error }} executed
        <span v-if="query_result_summary_error == 1">query</span>
        <span v-else>queries</span>
        returned an error
      </div>

      <div v-if="query_result_errors.length > 1">
        <h2 class="text-xl font-semibold mt-4">Errors</h2>
      </div>

      <div v-if="query_result_errors.length > 0">
        <div v-for="(query_result, query_result_index) in query_result_errors" :key="query_result_index"
             class="mb-8 mt-1">

          <div class="error-box">
            {{ query_result.message }}
            <hr class="border-light-200 my-2">
            {{ query_result.query }}
          </div>

        </div>
      </div>

    </div>
  </div>
</template>

<script>

import Spinner from "@/components/Spinner";
import FlashMessage from "@/components/FlashMessage";
import ApiMixin from "@/mixins/Api";
import ResultMessage from "@/components/ResultMessage";
import {has_deep_property} from "@/util";

export default {
  name: 'ImportDatabase',

  data() {
    return {
      query_result: {},
      is_importing_data: false,
      endpoint_database_import: 'database/import',
      import_result: {},
      import_file: '',
      query_results: [],
      progress_bar: {active: false, type: '', percentage: 0, text: ''}
    }
  },

  components: {
    ResultMessage,
    Spinner,
    FlashMessage,
  },

  mixins: [
    ApiMixin
  ],

  computed: {

    active_database() {
      return this.$store.state.activeDatabase;
    },

    query_result_summary_success() {
      if (this.query_results.length < 1) return;

      return this.query_results.reduce(function (accumulator, query_result) {
        if (query_result.result === 'success') {
          accumulator += 1;
        }
        return accumulator;
      }, 0);
    },

    query_result_summary_error() {
      if (this.query_results.length < 1) return;

      return this.query_results.reduce(function (accumulator, query_result) {
        if (query_result.result === 'error') {
          accumulator += 1;
        }
        return accumulator;
      }, 0);
    },

    query_result_errors() {
      if (!this.query_results || this.query_results.length == 0) return [];
      return this.query_results.filter(query_result => query_result.result === 'error');
    },

  },

  methods: {

    submitImport() {
      if (this.is_importing_data === true) return;

      let api_url_params = {'db': this.active_database};
      let api_url        = this.buildApiUrl(this.endpoint_database_import, api_url_params);

      let vue_instance = this;

      let formData = new FormData();
      formData.append('import_file', this.import_file);

      vue_instance.is_importing_data = true;
      this.$http.post(api_url, formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            },
            onUploadProgress: progressEvent => {
              vue_instance.progress_bar.active     = true;
              vue_instance.progress_bar.type       = 'upload';
              vue_instance.progress_bar.text       = 'Uploading file...';
              vue_instance.progress_bar.percentage = Math.floor(progressEvent.loaded / progressEvent.total * 100);
            },
            onDownloadProgress: progressEvent => {
              let progress_message = vue_instance.getLastResultOfOutputBuffer(progressEvent.target.responseText);
              if(!progress_message) {
                return;
              }

              vue_instance.progress_bar.active     = true;
              vue_instance.progress_bar.type       = 'processing';
              vue_instance.progress_bar.text       = 'Running SQL queries ' + progress_message.queries_executed + '/' + progress_message.total_queries;
              vue_instance.progress_bar.percentage = Math.floor(progress_message.queries_executed / progress_message.total_queries * 100);
            }
          }
      ).then(response => {

        if (has_deep_property(response, 'data', 'data', 'result') && response.data.data.result == 'error') {
          vue_instance.import_result = {type: 'error', message: response.data.data.message};
          scroll(0, 0);
          this.is_importing_data   = false;
          this.progress_bar.active = false;
          return;
        }

        let response_data = vue_instance.getLastResultOfOutputBuffer(response.data);
        if(!response_data) {
          vue_instance.import_result = {type: 'error', message: 'Could not read server response'};
          scroll(0, 0);
          this.is_importing_data   = false;
          this.progress_bar.active = false;
          return;
        }

        this.query_results = Object.freeze(response_data.query_results);

        // always refresh
        this.$store.dispatch('refreshTables');
        this.is_importing_data   = false;
        this.progress_bar.active = false;

      }).catch(error => {
        this.handleApiError(error);
        vue_instance.is_importing_data = false;
      })
    },

    handleFileUpload() {
      this.import_file = this.$refs.importfile.files[0];
    },

    getLastResultOfOutputBuffer(message) {
      // the api backend will buffer out the progress each 0.5 second, but each new buffer output is the full response
      // each new buffered output is a json encoded array, like [{'progress': 10}]
      // build an array around the message
      message = '[' + message + ']';
      // separate each output, this way it will be a valid array
      message = message.replaceAll('][', '],[');

      let messages = JSON.parse(message);
      if (!messages || messages.length === 0) {
        return;
      }

      return messages[messages.length - 1][0];
    }

  },
}
</script>


<style scoped>

.progress-bar {
  @apply mt-10;
  width: 280px;
}

</style>
