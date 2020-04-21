<template>
  <div class="flex justify-center">
    <TableData :tableid="tableid" :column="column" :value="value" />
  </div>
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
      this.$store.commit("recenttables/ADD_RECENT_TABLE", this.tableid);
    },
  }
</script>

<style>

</style>

