<template>
  <div class="flex justify-center items-center w-full h-screen">

    <div id="login-background" class="fixed z-0 justify-center items-center w-full h-screen"></div>

    <div class="max-w-sm relative" id="login-box">
      <div>
        <div class="header-bar px-6 py-3 text-center font-semibold" style="background: rgba(33, 32, 63, 0.6);">
          Rove - Login
        </div>
        <div class="error-box text-center m-3 break-words" v-show="login_error != ''">
          {{ login_error }}
        </div>
        <form @submit.prevent="submitLogin()" ref="loginform" class="form px-6 py-5">
          <div class="flex items-center mb-4">
            <label class="w-32">Host</label>
            <input type="text" name="host" v-model="host"
                   class="border border-gray-500 py-1 px-2 placeholder-gray-400 focus:outline-none"
                   placeholder="localhost" style="background-color: rgba(255,255,255,0.3);">
          </div>
          <div class="flex items-center mb-4">
            <label class="w-32">Username</label>
            <input type="text" name="username" v-model="username" ref="username"
                   class="border border-gray-500 py-1 px-2 placeholder-gray-400 focus:outline-none"
                   style="background-color: rgba(255,255,255,0.3);">
          </div>
          <div class="flex items-center mb-4">
            <label class="w-32">Password</label>
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

  mounted() {
    this.$refs.username.focus();
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
          this.$store.commit("setCsrfToken", response.data.data.csrf_token);
          this.$http.defaults.headers.post['X-CSRF-NAME'] = response.data.data.csrf_token.csrf_name;
          this.$http.defaults.headers.post['X-CSRF-VALUE'] = response.data.data.csrf_token.csrf_value;

          // we need to use nexttick because the redirect might be faster then the commit
          let vue_instance = this;
          this.$nextTick().then(function() {
            vue_instance.$store.dispatch('databases/get');
            vue_instance.$router.push({name: 'server'});
          });
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
#login-background {
  /*background-position-y: -220px;*/
  background-image:      url('../assets/images/login-background.jpg');
  background-position: center center;
  background-size: cover;
  animation: 10s ease-in 0s 1 fadeBackgroundIn;
  animation-fill-mode: forwards; /** stop at 100% keyframe **/
}

@keyframes fadeBackgroundIn {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 0.7;
  }
}

#login-box {

  background: linear-gradient(45deg, hsl(242, 25%, 25%), hsl(242, 25%, 25%), #536D92, #5A4688);
  background-size: 400% 400%;
  animation: 10s ease-in 0s 1 fadeBackgroundOut;
  animation-fill-mode: forwards; /** stop at 100% keyframe **/
}

@keyframes fadeBackgroundOut {
  0% {
    background-position: 0% 50%;
  }
  100% {
    background-position: 100% 50%;
  }

}


</style>
