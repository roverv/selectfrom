<template>
  <div class="page-content-container">
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

