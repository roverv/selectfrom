<template>
  <div id="dropdown" class="relative inline-block">
    <button @click="isOpen = true">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 mr-4 fill-current text-gray-400">
        <path class="primary"
              d="M6.8 3.45c.87-.52 1.82-.92 2.83-1.17a2.5 2.5 0 0 0 4.74 0c1.01.25 1.96.65 2.82 1.17a2.5 2.5 0 0 0 3.36 3.36c.52.86.92 1.8 1.17 2.82a2.5 2.5 0 0 0 0 4.74c-.25 1.01-.65 1.96-1.17 2.82a2.5 2.5 0 0 0-3.36 3.36c-.86.52-1.8.92-2.82 1.17a2.5 2.5 0 0 0-4.74 0c-1.01-.25-1.96-.65-2.82-1.17a2.5 2.5 0 0 0-3.36-3.36 9.94 9.94 0 0 1-1.17-2.82 2.5 2.5 0 0 0 0-4.74c.25-1.01.65-1.96 1.17-2.82a2.5 2.5 0 0 0 3.36-3.36zM12 16a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" />
        <circle cx="12" cy="12" r="2" class="secondary" />
      </svg>
    </button>

    <div
      v-if="isOpen"
      @click="isOpen = false"
      class="fixed inset-0"
      tabindex="-1"
    ></div>

    <transition
      enter-class="opacity-0 scale-90"
      enter-active-class="ease-out transition-fastest"
      enter-to-class="opacity-100 scale-100"
      leave-class="opacity-100 scale-100"
      leave-active-class="ease-in transition-fastest"
      leave-to-class="opacity-0 scale-90"
    >
      <div
        v-if="isOpen"
        class="mt-2 absolute left-0 origin-top-right text-left z-50"
      >
        <div class="w-auto bg-gray-700 text-gray-200 rounded-lg shadow-lg">
          <div class="w-64 border-t-2 border-gray-200 py-1">
            <a class="block px-6 py-3 leading-tight">Create new table</a>
            <a class="block px-6 py-3 leading-tight" @click="$emit('toggleTablesWithoutRows'), isOpen=false">Hide tables
              without data</a>
            <a class="block px-6 py-3 leading-tight" @click="$emit('toggleTableList'),  isOpen=false">Hide table list
              sidebar</a>
          </div>

        </div>
      </div>
    </transition>
  </div>
</template>

<script>
  export default {
    name: "TableListSettingDropdown",

    data() {
      return {
        isOpen: false
      }
    }
  }
</script>

<style scoped>

</style>
