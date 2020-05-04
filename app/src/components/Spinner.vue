<template>
  <div class="flex items-center justify-center" v-if="show">

    <svg version="1.1" id="L3" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
         y="0px"
         viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve" class="w-8">
      <circle fill="none" stroke="#fff" stroke-width="6" cx="50" cy="50" r="44" style="opacity:0.5;" />
      <circle fill="#fff" cx="8" cy="54" r="8">
      <animateTransform
        attributeName="transform"
        dur="2s"
        type="rotate"
        from="0 50 48"
        to="360 50 52"
        repeatCount="indefinite" />
    </circle>
</svg>

  </div>
</template>

<script>

  export default {
    name: 'Spinner',

    props: { delay: 0, },

    data() {
      return {
        show: false
      }
    },

    created() {
      let vue_instance = this;
      // only show the loader after the small timeout, else each fast load will show a ugly flash
      if(this.delay > 0) {
        setTimeout(function () {
          vue_instance.show = true;
        }, this.delay);
      }
      else {
        vue_instance.show = true;
      }
    }
  }
</script>
