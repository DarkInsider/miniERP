<template>
  <div class="home-wrapper">
    <div class="home">
      <div v-if="isAuth" class="content">
        <div class="home-control">
          <span class="home-control-title">Projects page</span>
          <div class="home-control-buttons">
            <div class="home-control-buttons-import">
              <div class="field__wrapper">
                <input ref="fileInput" @input="importProject()" accept=".xls,.xlsx" type="file" id="field__file-3" class="field field__file">

                <label class="field__file-wrapper" for="field__file-3">
                  <div class="field__file-fake">Import project</div>
                </label>

              </div>
            </div>
            <div class="home-control-buttons-create"><button @click="createProject()">Create project</button></div>
          </div>
        </div>
        <table id="projects">
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Organization</th>
            <th>Type</th>
            <th>Start</th>
            <th>End</th>
          </tr>
          <tr @click="goToProject(item.id)" class="project" v-for="item in $store.state.projects" v-bind:key="item.id" >
            <td>{{item.id}}</td>
            <td>{{item.title}}</td>
            <td>{{item.organization}}</td>
            <td>{{item.type}}</td>
            <td>{{item.start}}</td>
            <td>{{item.end}}</td>
          </tr>
        </table>
      </div>
      <div class="normalize" v-else>
        <span class="normalize-message" >Welcome to mini ERP, please login</span>
      </div>
      <div v-if="isAuth" class="pagination">
        <div><span style="margin-right: 5px">Limit:</span><input  @change="changeLimit()" v-model="limit" type="number" min="1" max="1000"></div>
        <button class="pagination-button" v-if="$store.state.pageLimit > 1" @click="first()">First</button>
        <button  class="pagination-button" v-if="$store.state.paginationPage > 1" @click="prev()">Prev</button>
        <div class="pagination-dots">
          <div class="pagination-dots-item" @click="goPage($store.state.paginationPage > 5 ? index + $store.state.paginationPage - 5 : index)" v-for="index   in Math.ceil($store.state.pageLimit) > 10 ? ($store.state.paginationPage + 5)>= Math.ceil($store.state.pageLimit)? Math.ceil($store.state.pageLimit) - ($store.state.paginationPage - 5) : 10  : Math.ceil($store.state.pageLimit) " :class="($store.state.paginationPage > 5 ? index + $store.state.paginationPage - 5 : index) === $store.state.paginationPage ? 'active' : ''" v-bind:key="$store.state.paginationPage > 5 ? index + $store.state.paginationPage -5 : index">{{$store.state.paginationPage > 5 ? index + $store.state.paginationPage -5: index}}</div>
        </div>
        <button class="pagination-button" v-if="$store.state.paginationPage < $store.state.pageLimit" @click="next()">Next</button>
        <button class="pagination-button" v-if="$store.state.pageLimit > 1" @click="last()">Last</button>
      </div>
    </div>
  </div>
</template>

<script>




export default {
  data(){
    return {
      limit: 10,
      isAuth: false
    }
  },
  methods:{
    importProject(){
      console.log(this.$refs.fileInput.files[0])
      this.$store.dispatch('importProject', {file: this.$refs.fileInput.files[0]}).then(()=>{
        this.$store.dispatch('getProjects', {page: this.$store.state.paginationPage, limit: this.limit});
      });
    },
    createProject(){
      this.$router.push('/projectCreate');
    },
    goToProject(id){
      this.$router.push('/project/'+id);
    },
    first(){
      this.$store.dispatch('getProjects', {page: 1, limit: this.limit});
    },
    last(){
      this.$store.dispatch('getProjects', {page: Math.ceil(this.$store.state.pageLimit), limit: this.limit});
    },
    goPage(page){
      this.$store.dispatch('getProjects', {page: page, limit: this.limit});
    },
    changeLimit(){
      this.$store.dispatch('setLimit', this.limit);
      this.$store.dispatch('getProjects', {page: 1, limit: this.limit});
    },
    prev(){
      this.$store.dispatch('getProjects', {page: this.$store.state.paginationPage - 1, limit: this.limit});
    },
    next(){
      this.$store.dispatch('getProjects', {page: this.$store.state.paginationPage + 1,  limit: this.limit});
    }
  },
  mounted() {
    console.log(this.$store)
    if(this.$store.state.user !== null){
      console.log(this.$store.state.user, 'user')
      this.isAuth = true;
      this.limit=  this.$store.state.limit;
      this.$store.dispatch('getProjects', {page: this.$store.state.paginationPage, limit: this.limit});
    }else {
      this.isAuth = false;
    }
    this.$store.watch(
            (state, getters) => getters.user,
            () => {
              if(this.$store.state.user !== null){
                this.isAuth = true;
                console.log(this.$store.state.user, 'user')
                this.$store.dispatch('getProjects', {page: this.$store.state.paginationPage, limit: this.limit});
              }else {
                this.isAuth = false;
              }
            })
  },
  components: {

  }
}
</script>
<style lang="scss" scoped>
.normalize{
  min-height: calc(100vh - 100px);
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: grey;
  &-message{
    color: white;
    font-size: 30px;
  }
}
  .home{
    &-control{
      display: flex;
      align-items: center;
      width: 100%;
      height: 60px;
      background-color: darkgreen;
      &-title{
        margin-left: 15px;
        color: white;
        font-size: 25px;
      }
      &-buttons {
        display: flex;
        align-items: center;
        margin-left: auto;
        &-import{
          width: 150px;
          color: white;
          background-color: cornflowerblue;
          border: none;
          padding: 8px;
          cursor: pointer;
          font-size: 15px;

        }
        &-create{
          button {
            margin-left: 25px;
            margin-right: 25px;
            outline: none;
            width: 150px;
            color: white;
            background-color: yellowgreen;
            border: none;
            padding: 8px;
            font-size: 15px;
            cursor: pointer;
          }

        }
      }
    }
  }
  .field__wrapper {
    width: 100%;
    position: relative;
  }

  .field__file {
    opacity: 0;
    visibility: hidden;
    position: absolute;
  }

  .field__file-wrapper {
    width: 100%;
  }


  #projects {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
  }


  #projects td, #projects th {
    border: 1px solid #ddd;
    padding: 8px;
  }

  #projects tr:nth-child(even){background-color: #f2f2f2;}

  #projects tr:hover {background-color: #ddd;}

  #projects th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #4CAF50;
    color: white;
  }
  .project{
    cursor: pointer;
  }
  .active{
    background-color: #4CAF50;
    color: white;
  }
  .content {
    min-height: calc(100vh - 214px);
    /*margin-top: 40px;*/
  }
  .pagination{
    margin-top: 40px;
    display: flex;
    flex-direction: row;
    width: 100%;
    align-items: center;
    margin-bottom: 40px;
    justify-content: space-around;
    &-button {
        background-color: #4CAF50;
        color: white;
      padding: 8px 16px;
      outline: none;
      border: 1px solid transparent;
      cursor: pointer;
      &:hover{
        border: 1px solid black;
      }
    }
    &-dots{
      display: flex;
      flex-direction: row;
      justify-content: center;
      width: 70%;
      &-item {
        float: left;
        padding: 8px 16px;
      }
      cursor: pointer;
      &-item:hover:not(.active) {background-color: #ddd;}

    }
  }
</style>