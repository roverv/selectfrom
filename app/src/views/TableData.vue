<template>
    <TableData :tableid="tableid" :column="column" :comparetype="comparetype" :value="value" />
</template>

<script>
  import TableData from '@/components/TableData.vue'

  export default {
    name: 'table',
    props: ['tableid', 'column', 'comparetype', 'value'],
    components: {
      TableData,
    },
    created() {
      this.$store.commit("recenttables/ADD_RECENT_TABLE", this.tableid);
    },
  }
</script>

