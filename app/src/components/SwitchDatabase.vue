<template>
  <div>
    <select name="" @change="switchDatabase" v-bind:value="active_database">
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
    props: ['databases', 'active_database'],

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
