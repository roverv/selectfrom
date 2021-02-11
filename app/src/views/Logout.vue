<template></template>
<script>

export default {
  name: "Logout",

  data() {
    return {}
  },

  mounted() {
    this.logout()
  },

  methods: {

    logout() {
      let vue_instance = this;
      this.$http.get('logout').then(response => {
        this.$store.dispatch('logout');
        vue_instance.$router.push({name: 'login'});
      }).catch(error => {
        vue_instance.$router.push({name: 'login'});
      });
    },

  }
}
</script>
