<template>
  <div v-if="show">

    <div v-for="(message_data) in messages">
      <result-message :message="message_data"></result-message>
    </div>

  </div>
</template>

<script>

  import ResultMessage from "@/components/ResultMessage";

  export default {
    name: 'FlashMessage',
    components: {ResultMessage},
    data() {
      return {
        show: false,
        messages: [],
      }
    },

    mounted() {
      if (this.$store.getters["flashmessage/isset"] === false) {
        this.$destroy();
      }
      this.messages = this.$store.getters["flashmessage/messages"];
      this.$store.commit('flashmessage/empty');
      this.show = true;
    },

  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>


</style>
