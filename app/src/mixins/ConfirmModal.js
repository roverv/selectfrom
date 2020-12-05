export default {

  data() {
    return {
      confirm_modal_open: false,
      confirm_modal_message: '',
      confirm_modal_action: '',
    }
  },

  methods: {
    closeConfirmModal() {
      this.confirm_modal_open   = false;
      this.confirm_modal_action = '';
      document.getElementById('app').focus();
    },

    confirmConfirmModal() {
      // execute dynamic action
      this[this.confirm_modal_action]();
      this.confirm_modal_open = false;
      this.confirm_modal_action = '';
    }
  }
}
