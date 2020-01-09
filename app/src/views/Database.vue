<template>
  <div class="database">
    <br>
    <TableList />
  </div>
</template>

<script>
  import TableList from '@/components/TableList.vue'

  export default {
    name: 'database',
    components: {
      TableList,
    },
  }
</script>

<style>
  .database {
    flex-grow: 1;
  }
</style>
