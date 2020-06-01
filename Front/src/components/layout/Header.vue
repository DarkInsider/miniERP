<template>
    <div class="header-wrapper">
        <div class="header">
            <router-link active-class="active" exact class="nav" to="/">HOME</router-link>
            <div  class="nav" @click="back()"> Back</div>
            <div class="right" v-if="( token === undefined) || (token === null)">
                <router-link class="nav" exact active-class="active" to="/reg">Registration</router-link>
                <router-link class="nav" exact active-class="active" to="/login">Login</router-link>
            </div>
            <div class="right" v-else>
                <router-link class="nav" exact @click.native="logout()" to="/login">Logout</router-link>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                token: localStorage.getItem('token')
            }
        },
        mounted() {
            this.$store.watch(
                (state, getters) => getters.token,
                (r) => {
                    console.log('aa', r)
                    this.token=localStorage.getItem('token');
                })
        },
        methods: {
            back(){
              this.$router.back();
            },
            logout(){
                this.$store.dispatch('logout', ).then(() => {
                    if(this.$route.path !== '/login'){
                        this.$router.push('/login');
                    }
                })
            }
        }
    }
</script>
<style lang="scss" scoped>
    .active{
        background-color: #4CAF50;
    }
    .right{
        margin-left: auto;
        display: flex;
    }
    .nav {
        cursor: pointer;
        margin-right: 15px;
        color: white;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;

    }
    .header{
        display: flex;
        width: 98%;
        height: 50px;

        &-wrapper {
            display: flex;
            background-color: #333;
            align-items: center;
            justify-content: center;
        }
    }
</style>