<template>
  <div v-if="show">

    <div v-if="message" class="success-box mb-3">
      {{ message }}
    </div>

    <div v-if="query" class="query-message">
      <div @click="toggleQuerySize($event)" class="query-sql w-64 truncate flex-grow">{{ query | format }}</div>
      <a class="edit-query">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path class="primary"
                d="M4 14a1 1 0 0 1 .3-.7l11-11a1 1 0 0 1 1.4 0l3 3a1 1 0 0 1 0 1.4l-11 11a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-3z"></path>
          <rect width="20" height="2" x="2" y="20" class="secondary" rx="1"></rect>
        </svg>
      </a>
    </div>

  </div>
</template>

<script>

  import sqlFormatter from "sql-formatter";

  export default {
    name: 'FlashMessage',

    data() {
      return {
        show: false,
        message: null,
        query: null,
      }
    },

    mounted() {
      if (this.$store.getters["flashmessage/isset"] === false) {
        this.$destroy();
      }

      this.message = this.$store.getters["flashmessage/message"];
      this.query   = this.$store.getters["flashmessage/query"];

      this.$store.commit('flashmessage/empty');

      this.show = true;
    },

    filters: {
      format: function (query_string) {
        return sqlFormatter.format(query_string);
      }
    },

    methods: {
      toggleQuerySize(event) {
        if (event.target.classList.contains('truncate')) {
          event.target.classList.remove('w-64', 'truncate');
        } else {
          event.target.classList.add('w-64', 'truncate');
        }
      },
    }

  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>


</style>
