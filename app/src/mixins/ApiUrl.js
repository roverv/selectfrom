import {urlencode} from "@/util";

export default {

  computed: {
    api_endpoint() {
      return this.$store.state.apiEndPoint;
    },
  },

  methods: {
    buildApiUrl(endpoint_url, params) {

      let api_url = this.api_endpoint;
      api_url += endpoint_url;

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
  }
}
