<template>
  <div id="app">
    <app-header></app-header>
    <router-view/>
    <app-footer></app-footer>
  </div>
</template>
<script>
  import Header from './components/layout/Header';
  import Footer from './components/layout/Footer';
  import axios from 'axios'
  import Vue from 'vue'
  Vue.prototype.$http = axios;
  export default {
    components:{
      'app-header' : Header,
      'app-footer' : Footer
    },
    mounted() {
      this.$store.watch(
              (state, getters) => getters.token,
              () => {
                let token = localStorage.getItem('token');
                if((token !== undefined) && (token !== null)){
                  Vue.prototype.$http.defaults.headers.common['token'] = token;
                  this.$store.dispatch('getUserInfo').then((resp)=> {
                    console.log(resp)
                  }).catch(()=>{
                    localStorage.removeItem('token');
                  })
                }
              })
      let token = localStorage.getItem('token');
      if((token !== undefined) && (token !== null)){
        Vue.prototype.$http.defaults.headers.common['token'] = token;
        this.$store.dispatch('getUserInfo').then((resp)=> {
          console.log(resp)
        }).catch(()=>{
          localStorage.removeItem('token');
        })
      }
    }
  }
</script>
<style lang="scss">
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

body {
  margin: 0 !important;
}
</style>
