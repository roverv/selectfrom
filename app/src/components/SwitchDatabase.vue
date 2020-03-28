<template>
  <div>
    <select name="" @change="switchDatabase">
      <option value="">Choose a database</option>
      <option v-for="database in databases" :value="database">
        {{ database }}
      </option>
    </select>
  </div>
</template>

<script>

  export default {
    name: 'SwitchDatabase',

    computed: {
      databases() {
        return this.$store.getters["databases/databases"];
      }
    },

    methods: {
      switchDatabase(event) {
        this.$emit('setActiveDatabase', event.target.value);
      }
    }
  }
</script>

<style scoped>

  select {
    @apply bg-light-100 text-on-bg-light py-1 px-2 outline-none;
  }

  select option {
    @apply bg-dark-400 text-white;
  }
</style>
