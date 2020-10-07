import {has_deep_property} from '../util';

export default {
  methods: {
    validateApiResponse(response) {
      if(has_deep_property(response, 'data', 'data', 'result') === false) {
        this.$store.commit("flashmessage/ADD_FLASH_MESSAGE", { type: 'error', message: 'Invalid response'});
        this.refreshPage();
        return false;
      }
      return true;
    },
  }
}
