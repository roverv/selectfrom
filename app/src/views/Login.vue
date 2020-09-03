<template>
  <div class="flex justify-center items-center w-full h-screen">
    <!--<div class="flex justify-center items-center w-full h-screen">-->

    <div class="bg-dark-600">
      <div class="header-bar px-6 py-3 text-center mb-2 font-semibold" style="background: rgba(33, 32, 63, 0.6);">
        Rove - Login
      </div>
      <form @submit.prevent="submitLogin()" ref="loginform" class="form px-6 py-5">
        <div class="flex items-center mb-4">
          <label for="" class="w-24">Server</label>
          <input type="text" name="server" v-model="server"
                 class="border border-gray-500 py-1 px-2 placeholder-gray-400 focus:outline-none"
                 placeholder="localhost" style="background-color: rgba(255,255,255,0.3);">
        </div>
        <div class="flex items-center mb-4">
          <label for="" class="w-24">Username</label>
          <input type="text" name="username" v-model="username"
                 class="border border-gray-500 py-1 px-2 placeholder-gray-400 focus:outline-none"
                 style="background-color: rgba(255,255,255,0.3);">
        </div>
        <div class="flex items-center mb-4">
          <label for="" class="w-24">Password</label>
          <input type="password" name="password" v-model="password"
                 class="border border-gray-500 py-1 px-2 placeholder-gray-400 focus:outline-none"
                 style="background-color: rgba(255,255,255,0.3);">
        </div>

        <div class="flex items-center">
          <div class="w-24"></div>
          <button class="btn flex py-1 px-5 items-center cursor-pointer">
            Login
          </button>

        </div>

      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "Login.vue",

  data() {
    return {
      endpoint: 'connect.php',
      server: '',
      username: '',
      password: '',
    }
  },

  methods: {

    submitLogin() {
      let api_url        = process.env.VUE_APP_API_ENDPOINT + this.endpoint;

      let params = new URLSearchParams();
      params.append('server', this.server);
      params.append('username', this.username);
      params.append('password', this.password);

      axios.post(api_url, params).then(response => {
        // @todo: handle the post
      }).catch(error => {
        this.handleApiError(error);

      })
    },

  }
}
</script>

<style scoped>

</style>
