<template>
  <div class="px-4 py-4 my-5 bg-light-50">
    <div class="mb-6 flex items-center">
      <div class="inline-flex flex-col items-center mr-16">
        <div class="statistic-key text-normal text-gray-300 ">Rows</div>
        <div class="statistic-value text-2xl text-gray-100">{{ rows }}</div>
      </div>

      <div class="inline-flex flex-col items-center mr-16">
        <div class="statistic-key text-normal text-gray-300 ">Total rows</div>
        <div class="statistic-value text-2xl text-gray-100">{{ totalrows }}</div>
      </div>

      <div class="inline-flex flex-col items-center mr-16">
        <div class="statistic-key text-normal text-gray-300 ">Columns</div>
        <div class="statistic-value text-2xl text-gray-100">{{ columns }}</div>
      </div>

<!--      <div class="inline-flex flex-col items-center mr-16">-->
<!--        <div class="statistic-key text-normal text-gray-300 ">Indexes</div>-->
<!--        <div class="statistic-value text-2xl text-gray-100">3</div>-->
<!--      </div>-->

<!--      <div class="inline-flex flex-col items-center mr-16">-->
<!--        <div class="statistic-key text-normal text-gray-300 ">Foreign keys</div>-->
<!--        <div class="statistic-value text-2xl text-gray-100">6</div>-->
<!--      </div>-->
    </div>

    <div class="my-6 query-message">
      <div class="query-sql">{{ query }}</div>
    </div>

    <div class="flex mt-6 ">
<!--      <a class="btn mr-6">Export all</a>-->
      <a class="btn mr-6" @click="$emit('confirmTruncateTable')">Truncate</a>
      <a class="btn mr-6" @click="$emit('confirmDropTable')">Drop</a>
    </div>
  </div>
</template>

<script>

  export default {
    name: 'TableDataMeta',
    props: [
      'totalrows',
      'rows',
      'columns',
      'query',
    ]
  }
</script>

<style scoped>

  .statistic-key {
    color: hsl(215, 45%, 85%);
  }

  .statistic-value {
    color:      hsl(215, 27%, 95%);
    /*background: hsla(215, 27%, 20%, 0.15);*/
    /*border:     1px solid hsla(215, 27%, 20%, 0.7);*/
    /*box-shadow: 1px 1px 1px 0px hsla(215, 27%, 22%, 1);*/
    /*outline: none !important;*/
    /*cursor: pointer;*/
    transition: all 0.3s ease;
    /*position: relative;*/
    /*padding: 0.25rem 1.25rem;*/
  }

</style>
