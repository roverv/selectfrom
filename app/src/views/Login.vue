<template>
  <div class="flex justify-center items-center w-full h-screen">

    <div class="max-w-sm">
      <div class="bg-dark-600">
        <div class="header-bar px-6 py-3 text-center font-semibold" style="background: rgba(33, 32, 63, 0.6);">
          Rove - Login
        </div>
        <div class="error-box text-center m-3 break-words" v-show="login_error != ''">
          {{ login_error }}
        </div>
        <form @submit.prevent="submitLogin()" ref="loginform" class="form px-6 py-5">
          <div class="flex items-center mb-4">
            <label for="" class="w-32">Host</label>
            <input type="text" name="host" v-model="host"
                   class="border border-gray-500 py-1 px-2 placeholder-gray-400 focus:outline-none"
                   placeholder="localhost" style="background-color: rgba(255,255,255,0.3);">
          </div>
          <div class="flex items-center mb-4">
            <label for="" class="w-32">Username</label>
            <input type="text" name="username" v-model="username"
                   class="border border-gray-500 py-1 px-2 placeholder-gray-400 focus:outline-none"
                   style="background-color: rgba(255,255,255,0.3);">
          </div>
          <div class="flex items-center mb-4">
            <label for="" class="w-32">Password</label>
            <input type="password" name="password" v-model="password"
                   class="border border-gray-500 py-1 px-2 placeholder-gray-400 focus:outline-none"
                   style="background-color: rgba(255,255,255,0.3);">
          </div>

          <div class="flex items-center">
            <div class="w-32"></div>
            <button class="btn flex py-1 px-5 items-center cursor-pointer">
              Login
            </button>

          </div>

        </form>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: "Login.vue",

  data() {
    return {
      host: '',
      username: '',
      password: '',
      login_error: ''
    }
  },

  methods: {

    submitLogin() {
      let params = new URLSearchParams();
      params.append('host', this.host);
      params.append('username', this.username);
      params.append('password', this.password);

      this.$http.post('connect', params).then(response => {
        if (response.data.data.result == 'success') {
          this.$store.commit("setAuthenticated", true);
          this.$router.push({name: 'server'});
        } else if (response.data.data.result == 'error') {
          this.login_error = response.data.data.message;
        }
      }).catch(error => {
        if (error.response) {
          this.login_error = error.response.data.statusCode + ': ' + error.response.data.error.type + ' - ' + error.response.data.error.description;
        } else if (error.request) {
          // The request was made but no response was received
          this.login_error = error.request
        } else {
          this.login_error = 'No API response';
        }
      });
    },

  }
}
</script>

<style scoped>

</style>
