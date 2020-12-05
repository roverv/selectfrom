<template>

  <div class="modal-container" :class="{ 'open' : modalisopen }">

    <div class="modal-content max-w-2xl border border-highlight-100">
      <div class="text-lg bg-dark-600 px-5">
        <h3 class="modal-title mt-2 mb-1 px-0 font-bold">
          Table data
        </h3>
        <hr class="border-light-100 mb-4">

        <div>
          <p class="mb-3">Click on the table title to view additional options.</p>

          <p class="mb-4">
            <strong>Navigation shortcuts</strong>
          </p>

          <div class="shortcut-box">
            <div class="shortcut-key">1</div>

            <div class="shortcut-description">
              <span class="text-light-500">To table data</span>
            </div>
          </div>

          <div class="shortcut-box">
            <div class="shortcut-key">2</div>
            <div class="shortcut-description">
              <span class="text-light-500">To structure</span>
            </div>
          </div>

          <div class="shortcut-box">
            <div class="shortcut-key">3</div>
            <div class="shortcut-description">
              <span class="text-light-500">To indexes</span>
            </div>
          </div>

          <div class="shortcut-box">
            <div class="shortcut-key">4</div>
            <div class="shortcut-description">
              <span class="text-light-500">To foreign keys</span>
            </div>
          </div>

          <div class="shortcut-box">
            <div class="shortcut-key">5</div>
            <div class="shortcut-description">
              <span class="text-light-500">To add row</span>
            </div>
          </div>

          <p class="mb-4">
            <strong>Table shortcuts</strong>
          </p>

          <div class="shortcut-box">
            <div class="shortcut-key">v</div>

            <div class="shortcut-description">
              <span>View switch</span>
              <span class="text-light-300">Switch between table view and single row view</span>
            </div>
          </div>

          <div class="shortcut-box">
            <div class="shortcut-key long">Ctrl</div>
            <div class="shortcut-key long">Click</div>

            <div class="shortcut-description">
              <span>Show sidebar</span>
              <span class="text-light-300">CTRL + click on a row to show the row vertically in the sidebar. Click multiple rows for comparison.</span>
            </div>
          </div>

          <div class="shortcut-box">
            <div class="shortcut-key">n</div>

            <div class="shortcut-description">
              <span>Next page</span>
              <span class="text-light-300">Go to next batch of rows</span>
            </div>
          </div>

          <div class="shortcut-box">
            <div class="shortcut-key">p</div>

            <div class="shortcut-description">
              <span>Previous page</span>
              <span class="text-light-300">Go to previous batch of rows</span>
            </div>
          </div>

        </div>


        <div class="flex flex-col justify-center w-full my-6">

          <p class="mb-4">You can find all shortcuts on the help page.</p>
          <div class="text-center">
            <a @click="close()" class="btn light">Close</a>
          </div>
        </div>

      </div>
    </div>

  </div>
</template>

<script>

export default {
  name: 'TableDataHelpModal',
  props: ['modalisopen'],
  data() {
    return {

    }
  },

  created() {
    document.addEventListener('keydown', this.triggerKeyDown);
  },

  beforeDestroy() {
    document.removeEventListener('keydown', this.triggerKeyDown);
  },

  methods: {

    triggerKeyDown: function (evt) {
      if (evt.key === 'Escape') {
        this.close();
        evt.preventDefault();
      }
    },

    close() {
      let pay_load = {setting_name: 'do_not_show_table_data_help_message', setting_value: true};
      this.$store.commit("settings/set_setting", pay_load);
      this.$emit('closehelp');
    }

  },

}
</script>
