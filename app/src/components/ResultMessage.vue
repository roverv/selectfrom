<template>

  <div v-if="message">

    <div v-if="message.message" class="mb-3"
         :class="{ 'success-box' : (message.type == 'success'), 'error-box' : (message.type == 'error') }">
      {{ message.message }}
    </div>

    <div v-if="message.query" class="query-message">
      <div @click.once="toggleQuerySize($event)"
           class="query-sql cursor-pointer w-64 truncate flex-grow">{{ message.query | format }}</div>
      <a class="query-message-icon-btn" @click="copyQuery()">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <rect width="14" height="14" x="3" y="3" class="secondary" rx="2"></rect>
          <rect width="14" height="14" x="7" y="7" class="primary" rx="2"></rect>
        </svg>
      </a>
      <a class="query-message-icon-btn" @click="editQuery()">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path class="primary"
                d="M4 14a1 1 0 0 1 .3-.7l11-11a1 1 0 0 1 1.4 0l3 3a1 1 0 0 1 0 1.4l-11 11a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-3z"></path>
          <rect width="20" height="2" x="2" y="20" class="secondary" rx="1"></rect>
        </svg>
      </a>
      <a class="query-message-icon-btn" @click="removeQuery()">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10" class="primary"></circle>
          <path class="text-gray-800"
                d="M13.41 12l2.83 2.83a1 1 0 0 1-1.41 1.41L12 13.41l-2.83 2.83a1 1 0 1 1-1.41-1.41L10.59 12 7.76 9.17a1 1 0 0 1 1.41-1.41L12 10.59l2.83-2.83a1 1 0 0 1 1.41 1.41L13.41 12z"></path>
        </svg>
      </a>
    </div>
  </div>

</template>

<script>

import sqlFormatter from "sql-formatter";

export default {
  name: 'ResultMessage',

  props: ['message'],

  data() {
    return {}
  },

  filters: {
    format: function (query_string) {
      return sqlFormatter.format(query_string);
    }
  },

  methods: {
    toggleQuerySize(event) {
      if (event.target.classList.contains('truncate')) {
        event.target.classList.remove('w-64', 'truncate', 'cursor-pointer');
      }
    },

    editQuery() {
      this.$store.commit("queryedit/ADD_QUERY_EDIT", this.message.query);
      this.$router.push({name: 'query'});
    },

    copyQuery() {
      const el = document.createElement('textarea');
      el.value = this.message.query;
      document.body.appendChild(el);
      el.select();
      document.execCommand('copy');
      document.body.removeChild(el);
    },

    removeQuery() {
      this.message.query = null;
    }
  }

}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>


</style>
