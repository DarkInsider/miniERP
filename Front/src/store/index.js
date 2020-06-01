import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)
Vue.prototype.$http = axios;
//let url = 'http://85.217.170.8:81/api';
let url = 'http://172.17.202.252/api';


export default new Vuex.Store({
  state: {
    user: null,
    status: null,
    token: null,
      projects: [],
      paginationPage: 1,
      pageLimit: null,
      limit: 10
  },
  mutations: {
    set_user(state, payload){
      state.user = payload;
    },
    request(state){
      state.status= 'loading'
    },
    success(state){
      state.status= 'success'
    },
    error(state){
      state.status= 'error'
    },
    set_token(state, payload){
        state.token = payload;
    },
      set_projects(state, payload){
          state.projects = payload;
      },
      set_paginationPage(state, payload){
          state.paginationPage = payload;
      },
      set_pageLimit(state, payload){
          state.pageLimit = payload;
      },
      set_limit(state, payload){
          state.limit = payload;
      },
  },
  actions: {
    setUser ({commit}, payload) {
      commit('set_user', payload)
    },
      setLimit ({commit}, payload) {
          commit('set_limit', payload)
      },
    registration({commit}, payload){
      return new Promise((resolve, reject) => {
        commit('request')
        axios({
          url: url+'/user/registration',
          data: payload,
          method: 'POST'
        })
            .then(resp => {
              console.log(resp, 'resp')
              commit('success')
              resolve(resp)
            })
            .catch(err => {
              commit('error')
              reject(err)
            })
      })
    },
      addSkill({commit}, payload){
          return new Promise((resolve, reject) => {
              commit('request')
              axios({
                  url: url+'/projectHasSkill',
                  data: payload,
                  method: 'POST'
              })
                  .then(resp => {
                      commit('success')
                      resolve(resp)
                  })
                  .catch(err => {
                      commit('error')
                      reject(err)
                  })
          })
      },
     editProject({commit}, payload){
          return new Promise((resolve, reject) => {
              commit('request')
              axios({
                  url: url+'/project'+payload,
                  method: 'PUT'
              })
                  .then(resp => {
                      commit('success')
                      resolve(resp)
                  })
                  .catch(err => {
                      commit('error')
                      reject(err)
                  })
          })
      },

      createProject({commit}, payload){
          return new Promise((resolve, reject) => {
              commit('request')
              axios({
                  url: url+'/project',
                  data: payload,
                  method: 'POST'
              })
                  .then(resp => {
                      commit('success')
                      resolve(resp)
                  })
                  .catch(err => {
                      commit('error')
                      reject(err)
                  })
          })
      },
      getProjects({commit}, payload){
        let params = '?';
        if(payload !== undefined){
            if(payload.limit !== undefined){
                params+='limit='+payload.limit+'&';
            }
            if(payload.page !== undefined){
                params+='page='+payload.page+'&';
                commit('set_paginationPage',payload.page )

            }
        }
          return new Promise((resolve, reject) => {
              commit('request')
              axios({
                  url: url+'/project'+params,
                  method: 'GET'
              })
                  .then(resp => {

                      commit('set_projects', resp.data.data)
                      commit('set_pageLimit', resp.data.total/resp.data.per_page)
                      commit('success')
                      resolve(resp)
                  })
                  .catch(err => {
                      commit('error')
                      reject(err)
                  })
          })
      },
      getProjectById({commit}, payload){
          return new Promise((resolve, reject) => {
              commit('request')
              axios({
                  url: url+'/project/'+payload,
                  method: 'GET'
              })
                  .then(resp => {
                      commit('success')
                      resolve(resp)
                  })
                  .catch(err => {
                      commit('error')
                      reject(err)
                  })
          })
      },


      importFile({commit}, payload){
          let formData = new FormData();
          formData.append('file', payload.file);
          formData.append('project_id', payload.project_id);
          return new Promise((resolve, reject) => {
              commit('request')
              axios({
                  url: url+'/projectHasAttachment',
                  method: 'POST',
                  data: formData,
                  headers:{
                      'Content-Type': 'multipart/form-data'
                  }
              })
                  .then(resp => {
                      commit('success')
                      console.log(resp, 'import')
                      resolve(resp)
                  })
                  .catch(err => {
                      commit('error')
                      console.log(err, 'err')
                      reject(err)
                  })
          })
      },


      importProject({commit}, payload){
          let formData = new FormData();
          formData.append('file', payload.file);
          return new Promise((resolve, reject) => {
              commit('request')
              axios({
                  url: url+'/projectImport',
                  method: 'POST',
                  data: formData,
                  headers:{
                      'Content-Type': 'multipart/form-data'
                  }
              })
                  .then(resp => {
                      commit('success')
                      console.log(resp, 'import')
                      resolve(resp)
                  })
                  .catch(err => {
                      commit('error')
                      console.log(err, 'err')
                      reject(err)
                  })
          })
      },



      removeFile({commit}, payload){
          return new Promise((resolve, reject) => {
              commit('request')
              axios({
                  url: url+'/projectHasAttachment?project_has_attachment_id='+payload,
                  method: 'DELETE'
              })
                  .then(resp => {
                      commit('success')
                      resolve(resp)
                  })
                  .catch(err => {
                      commit('error')
                      reject(err)
                  })
          })
      },

      removeSkill({commit}, payload){
          return new Promise((resolve, reject) => {
              commit('request')
              axios({
                  url: url+'/projectHasSkill?project_has_skill_id='+payload,
                  method: 'DELETE'
              })
                  .then(resp => {
                      commit('success')
                      resolve(resp)
                  })
                  .catch(err => {
                      commit('error')
                      reject(err)
                  })
          })
      },


      deleteProject({commit}, payload){
          return new Promise((resolve, reject) => {
              commit('request')
              axios({
                  url: url+'/project?project_id='+payload,
                  method: 'DELETE'
              })
                  .then(resp => {
                      commit('success')
                      resolve(resp)
                  })
                  .catch(err => {
                      commit('error')
                      reject(err)
                  })
          })
      },

    login({commit}, payload){
      return new Promise((resolve, reject) => {
        commit('request')
        axios({
          url: url+'/user/login',
          data: payload,
          method: 'POST'
        })
            .then(resp => {
              console.log(resp, 'resp')
                commit('set_token', resp.data[0].token)
                localStorage.setItem('token', resp.data[0].token)
              commit('set_user', resp.data[0])
              commit('success')
              resolve(resp)
            })
            .catch(err => {
              commit('error')
              reject(err)
            })
      })
    },
    getUserInfo({commit}){
      return new Promise((resolve, reject) => {
        commit('request')
        axios({
          url: url+'/user',
          method: 'GET'
        })
            .then(resp => {
              console.log(resp, 'resp')
              commit('set_user', resp.data)
                commit('set_token', resp.data.token)
              commit('success')
              resolve(resp)
            })
            .catch(err => {
              commit('error')
              reject(err)
            })
      })
    },
      logout({commit}){
          return new Promise((resolve, reject) => {
              commit('request')
              axios({
                  url: url+'/user/logout',
                  method: 'POST'
              })
                  .then(resp => {
                      console.log(resp, 'resp')
                      commit('set_user', null)
                      commit('success')
                      localStorage.removeItem('token');
                      commit('set_token', null)
                      resolve(resp)
                  })
                  .catch(err => {
                      commit('error')
                      reject(err)
                  })
          })
      }

  },
    getters:{
        token: state => state.token,
        user: state => state.user,
    },
  modules: {
  }
})
