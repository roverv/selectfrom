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
  }
</script>

<style>

</style>

