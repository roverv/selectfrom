<template>
  <TableData :tableid="tableid" :column="column" :value="value" :querytype="querytype" :active_database="active_database" />
</template>

<script>
  import TableData from '@/components/TableData.vue'

  export default {
    name: 'table',
    props: ['tableid', 'column', 'value', 'querytype', 'active_database'],
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

