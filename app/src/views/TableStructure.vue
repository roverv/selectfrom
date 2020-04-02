<template>
  <div class="flex justify-center">
    <TableStructure :tableid="tableid" />
  </div>
</template>

<script>
  import TableStructure from '@/components/TableStructure.vue'

  export default {
    name: 'structure',
    props: ['tableid'],
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

