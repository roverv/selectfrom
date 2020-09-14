export default {
  methods: {
    handleApiError(error) {
      if (error.response && error.response.status && error.response.status == 400 && error.response.error == 'Invalid CSRF') {
        this.$store.commit("apierror/add_error_message", '400 - Invalid CSRF token, please login again');
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
  }
}
