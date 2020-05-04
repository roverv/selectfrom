<template>
  <div v-if="show">
    <div class="error-box text-center">
      {{ error_message }}
    </div>
  </div>
</template>

<script>

  export default {
    name: 'ApiError',

    data() {
      return {}
    },

    beforeDestroy() {
      this.$store.commit('apierror/empty');
    },

    computed: {

      show() {
        return this.$store.getters["apierror/isset"];
      },

      error_message() {
        return this.$store.getters["apierror/error_message"];
      }
    },

  }
</script>
