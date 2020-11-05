<template>

  <div class="modal-container" :class="{ 'open' : modalisopen }">
    <div class="modal-content max-w-md">
      <div>

        <div v-for="contextoption in contextoptions" class="shortcut-box bg-dark-600 shadow-lg ">
          <div class="shortcut-key bg-highlight-800 border-0">{{ contextoption.shortkey }}</div>
          <div class="shortcut-description">
            <span>{{ contextoption.label }}</span>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'ContextMenu',
  props: ['modalisopen', 'contextoptions'],
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

      let context_option = this.contextoptions.find(contextoption => contextoption.shortkey == evt.key);
      console.log(context_option, evt.key);

      if(context_option) {
        this.$emit('runContextAction', context_option.action);
        this.close();
      }
    },

    close() {
      this.$emit('closecontextmenu');
    }

  },

}
</script>
