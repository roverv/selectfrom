<template>
  <div v-html="posts">
  </div>
</template>

<script>

  import axios from 'axios'

  export default {
    name: 'EditRow',

    data() {
      return {
        posts: null,
        endpoint: 'http://localhost/adminer/?username=root&db=rove',
      }
    },

    created() {
      this.getAllPosts();
    },

    methods: {
      getAllPosts() {
        console.log(this.endpoint);
        
        axios.get(this.endpoint).then(response => {
          this.posts = response.data;
        }).catch(error => {
          console.log('-----error-------');
          console.log(error);
        })
      }
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  h3 {
    margin: 40px 0 0;
  }

  ul {
    list-style-type: none;
    padding:         0;
  }

  li {
    display: inline-block;
    margin:  0 10px;
  }

  a {
    color: #42b983;
  }
</style>
