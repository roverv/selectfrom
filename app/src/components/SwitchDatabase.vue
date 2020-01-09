<template>
  <div>
    <select name="" id="" @change="switchDatabase" v-model="active_database">
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
    props: ['databases'],
    data() {
      return {
        active_database: '',
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
    @apply bg-gray-600 text-gray-200 py-1 px-2 mb-4;
  }
</style>
