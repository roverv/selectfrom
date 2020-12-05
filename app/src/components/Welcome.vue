<template>

  <div class="modal-container" :class="{ 'open' : modalisopen }">

    <div class="modal-content max-w-2xl border border-highlight-100">
      <div class="text-lg bg-dark-600 px-5">
        <h3 class="modal-title mt-2 mb-1 px-0 font-bold">
          Welcome to selectfrom
        </h3>
        <hr class="border-light-100 mb-4">

        <div>
          <p class="mb-3">To you get you started, here are some tips to navigate through the interface.</p>
          <p class="mb-5">Hover to the far left of the window to open the main menu. There you will find the default
            navigation and the settings and help pages.</p>

          <p class="mb-4">
            <strong>Important shortcuts</strong>
            <br>
            With these shortcuts, you can easily access any part of your database.
            <br>
            You can find all shortcuts on the help page.
          </p>

          <div class="shortcut-box">
            <div class="shortcut-key">F</div>

            <div class="shortcut-description">
              <span>Find</span>
              <span class="text-light-300">Quickly search, find and go to a table/column/row</span>
              <span class="text-light-300">When opened, click on the question mark to see the available options</span>
            </div>
          </div>

          <div class="shortcut-box">
            <div class="shortcut-key">D</div>
            <div class="shortcut-description">
              <span>Databases</span>
              <span class="text-light-300">Switch to any database</span>
            </div>
          </div>

          <div class="shortcut-box">
            <div class="shortcut-key">Q</div>
            <div class="shortcut-description">
              <span>Query</span>
              <span class="text-light-300">Go to query page</span>
            </div>
          </div>

          <div class="shortcut-box">
            <div class="shortcut-key">R</div>
            <div class="shortcut-description">
              <span>Refresh page</span>
              <span class="text-light-300">Reload the current page, but does not renew cached data</span>
            </div>
          </div>

          <div class="shortcut-box">
            <div class="shortcut-key">U</div>
            <div class="shortcut-description">
              <span>Up</span>
              <span
                  class="text-light-300">Move up one level in the hierarchy: Server &gt; database &gt; table &gt; page</span>
            </div>
          </div>

          <div class="shortcut-box">
            <div class="shortcut-key">C</div>
            <div class="shortcut-description">
              <span>Context menu</span>
              <span
                  class="text-light-300">Opens a page specific context menu to quickly execute certain actions</span>
            </div>
          </div>

          <div class="shortcut-box">
            <div class="shortcut-key long">Esc</div>
            <div class="shortcut-description">
              <span>Close</span>
              <span class="text-light-300">Close modal, close sidebar or remove focus from input</span>
            </div>
          </div>


        </div>


        <div class="flex justify-between my-6">
          <label class="custom-checkbox w-1/3 ml-2">
            <input type="checkbox" value="1" class="hidden" autocomplete="off" v-model="do_not_show_again">
            <span class="input-box"></span>
            <span><span class="">Do not show again</span></span>
          </label>

          <div class="w-1/3 text-center">
            <a @click="close()" class="btn light">Close</a>
          </div>
          <div class="w-1/3"></div>
        </div>

      </div>
    </div>

  </div>
</template>

<script>

export default {
  name: 'Welcome',
  props: ['modalisopen'],
  data() {
    return {
      do_not_show_again: false
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
      if (this.do_not_show_again) {
        let pay_load = {setting_name: 'do_not_show_welcome_message', setting_value: true};
        this.$store.commit("settings/set_setting", pay_load);
      }
      this.$emit('closewelcome');
    }

  },

}
</script>
