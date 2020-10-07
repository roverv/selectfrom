import Vue from 'vue'

// global mixin, available in ALL vue instances
// use with caution

Vue.mixin({
  methods: {

    refreshPage() {
      this.$store.state.reloadMainComponentKey += 1;
    },


  }
})
