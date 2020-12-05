<template>

  <div class="modal-container open">

    <div class="modal-content modal-confirm max-w-sm">
      <h3 class="modal-title text-center">
        Are you sure?
      </h3>

      <div class="px-4 pb-4">
        <div class="text-center mb-6">
          <slot></slot>
        </div>

        <div class="flex justify-center">
          <a id="confirm-modal-cancel" class="btn show-focus mr-4" autofocus @click="close()" tabindex="1">Cancel</a>
          <a id="confirm-modal-confirm" class="btn show-focus" tabindex="2" @click="confirm()">Confirm</a>
        </div>
      </div>
    </div>

  </div>
</template>

<script>

  export default {
    name: 'ConfirmModal',
    props: [],
    data() {
      return {}
    },

    created() {
      document.addEventListener('keydown', this.triggerKeyDown);
    },

    mounted() {
      document.getElementById('confirm-modal-confirm').focus();
    },

    beforeDestroy() {
      document.removeEventListener('keydown', this.triggerKeyDown);
    },

    methods: {

      triggerKeyDown: function (evt) {
        if (evt.key === 'Escape') {
          this.close();
          evt.preventDefault();
        }
        if (evt.key === 'Enter') {
          if(document.activeElement.id == 'confirm-modal-confirm') {
            this.confirm();
          }
          else {
            this.close();
          }

          evt.preventDefault();
        }
        if(evt.key == 'Tab') {
          evt.preventDefault();
          evt.stopPropagation();
          // tab between confirm and cancel (and exclude other tabindex elements on the page outside the modal)
          if(document.activeElement.id == 'confirm-modal-confirm') {
            document.getElementById('confirm-modal-cancel').focus();
          }
          else {
            document.getElementById('confirm-modal-confirm').focus();
          }
        }
      },

      close() {
        this.$emit('close');
      },

      confirm() {
        this.$emit('confirm');
      }

    },

  }
</script>

