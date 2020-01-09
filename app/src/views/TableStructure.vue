<template>
  <TableStructure :tableid="tableid" :active_database="active_database" />
</template>

<script>
  import TableStructure from '@/components/TableStructure.vue'

  export default {
    name: 'structure',
    props: ['tableid', 'active_database'],
    components: {
      TableStructure,
    },
    created() {
      console.log(' tablestructure.vue');
    },
  }
</script>

<style>
  .database {
    flex-grow: 1;
  }
</style>

