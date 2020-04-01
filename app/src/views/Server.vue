<template>
  <div class="server">
    <br>
    <DatabaseList />
  </div>
</template>

<script>
  import DatabaseList from '@/components/DatabaseList.vue'

  export default {
    name: 'server',
    components: {
      DatabaseList,
    },
  }
</script>

<style>
  .server {
    @apply flex justify-center;
  }
</style>
