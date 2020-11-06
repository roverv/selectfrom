<template>

  <div class="modal-container" :class="{ 'open' : modalisopen }">
    <div class="modal-content max-w-md">
      <div>

        <div v-if="!contextoptions || contextoptions.length == 0" class="context-item-box">
          <div class="context-item-key"></div>
          <div class="context-item-label">
            <span>No available actions</span>
          </div>
        </div>

        <div v-for="contextoption in contextoptions" class="context-item-box">
          <div class="context-item-key">{{ contextoption.shortkey }}</div>
          <div class="context-item-label">
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
    // prevents from executing keypress of c twice, because of app.vue trigger and trigger of this component
    let self = this
    setTimeout(function () {
      document.addEventListener('keydown', self.triggerKeyDown);
    }, 50);
  },

  beforeDestroy() {
    document.removeEventListener('keydown', this.triggerKeyDown);
  },

  methods: {

    triggerKeyDown: function (evt) {
      if (evt.key === 'Escape' || evt.key === 'c') {
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
