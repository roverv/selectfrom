<template>
  <div class="flex justify-center">
    <br>
    <TableList />
  </div>
</template>

<script>
  import TableList from '@/components/TableList.vue'

  export default {
    name: 'database',
    props: ['database'],
    components: {
      TableList,
    },

    mounted() {
      // check if database is changed or not yet set
      if(this.$store.state.activeDatabase == '' || this.$store.state.activeDatabase != this.database) {
        this.$store.commit("setActiveDatabase", this.database);
      }
    }
  }
</script>

<style>

</style>
