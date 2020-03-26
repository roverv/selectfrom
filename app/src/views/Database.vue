<template>
  <div class="database">
    <br>
    <TableList :active_database="active_database" />
  </div>
</template>

<script>
  import TableList from '@/components/TableList.vue'

  export default {
    name: 'database',
    props: ['active_database'],
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
