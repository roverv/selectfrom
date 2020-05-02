<template>

  <div class="modal-container" :class="{ 'open' : modalisopen }">

    <div class="modal-content modal-confirm max-w-sm">
      <h3 class="modal-title text-center">
        Are you sure?
      </h3>

      <div class="px-4 pb-4">
        <div class="text-center mb-6">
          <slot></slot>
        </div>

        <div class="flex justify-center">
          <a class="btn mr-4" @click="close()">Cancel</a>
          <a class="btn" @click="confirm()">Confirm</a>
        </div>
      </div>
    </div>

  </div>
</template>

<script>

  export default {
    name: 'ConfirmModal',
    props: ['modalisopen'],
    data() {
      return {}
    },

    created() {
      document.addEventListener('keydown', this.triggerKeyDown);
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
          this.confirm();
          evt.preventDefault();
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

