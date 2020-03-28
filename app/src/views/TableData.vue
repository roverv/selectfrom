<template>
  <TableData :tableid="tableid" :column="column" :value="value" />
</template>

<script>
  import TableData from '@/components/TableData.vue'

  export default {
    name: 'table',
    props: ['tableid', 'column', 'value'],
    components: {
      TableData,
    },
    created() {
      this.$emit('addrecenttable', this.tableid);
    },
  }
</script>

<style>
  .database {
    flex-grow: 1;
  }
</style>

