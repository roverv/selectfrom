<template>
  <div class="page-content-container">
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
