import {has_deep_property, urlencode} from "@/util";

export default {

  methods: {
    buildApiUrl(endpoint_url, params) {
      let api_url = endpoint_url;

      let url_params_array = Object.entries(params);
      if(url_params_array.length == 0) return api_url;

      api_url += '?';
      for(const [key, value] of url_params_array) {
        api_url += key + '=' + urlencode(value);
        api_url += '&';
      }

      // remove the last &
      api_url = api_url.substring(0, api_url.length -1);

      return api_url;
    },

    handleApiError(error) {
      if (error.response && error.response.status && error.response.status == 400 && error.response.data.error == 'Invalid CSRF') {
        this.$store.commit("apierror/add_error_message", '400 - Invalid CSRF token, please login again');
      }
      else if (error.response && error.response.status && error.response.status == 500 && has_deep_property(error.response, 'data', 'data', 'message')) {
        // specific message returned from server side
        this.$store.commit("apierror/add_error_message", 'Error 500 - ' + error.data.data.message);
      }
      else if (error.response) {
        // Request made and server responded
        console.log(error.response.data);
        console.log(error.response.status);
        console.log(error.response.headers);
        this.$store.commit("apierror/add_error_message", 'Request returned ' + error.response.status);
      } else if (error.request) {
        // The request was made but no response was received
        console.log(error.request);
        this.$store.commit("apierror/add_error_message", 'Request returned nothing');
      } else {
        // Something happened in setting up the request that triggered an Error
        console.log(error);
        this.$store.commit("apierror/add_error_message", error.message);
      }
    },

    /**
     * Only use this on POST calls, because it will refresh the page
     * (causing a fetch loop if a request is made on mounted)
     *
     * @param response
     * @returns {boolean}
     */
    validateApiPostResponse(response) {
      if(has_deep_property(response, 'data', 'data', 'result') === false) {
        this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", { type: 'error', message: 'Invalid response'});
        this.refreshPage();
        return false;
      }
      return true;
    },

  }
}
