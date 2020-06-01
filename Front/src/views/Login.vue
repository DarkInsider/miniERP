<template>
    <div class="login-wrapper">
        <div class="login">
            <input type="email" v-model="email" placeholder="Email">
            <input type="password" v-model="password" placeholder="Password">
            <input type="submit"  @click="login()">
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                email: '',
                password: '',
            }
        },
        methods:{
            login() {
                this.$store.dispatch('login', {
                    email: this.email,
                    password: this.password
                }).then(() => {

                    this.$router.push('/');
                }).catch(() => {
                    alert('All fields are required')
                })
            }
        }
    }
</script>
<style lang="scss" scoped>
    input[type=text],input[type=date], input[type=email], input[type=password], select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type=submit] {
        width: 100%;
        background-color: #4CAF50;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type=submit]:hover {
        background-color: #45a049;
    }

    div {
        border-radius: 5px;
        background-color: #f2f2f2;
        //padding: 20px;
    }
    .login{
        display: flex;
        flex-direction: column;
        width: 50%;
        justify-content: center;
        min-width: 600px;
        &-wrapper {
            display: flex;
            width: 100vw;
            min-height: calc(100vh - 100px);
            align-items: center;
            justify-content: center;
        }
    }
</style>