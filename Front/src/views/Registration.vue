<template>
    <div class="registration-wrapper">
        <div class="registration">
            <input type="text" v-model="name" placeholder="Name">
            <input type="email" v-model="email" placeholder="Email">
            <input type="password" v-model="password1" placeholder="Password">
            <input type="password" v-model="password2" placeholder="Confirm password">
            <input type="submit"   @click="reg()">
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                name: '',
                email: '',
                password1: '',
                password2: ''
            }
        },
        methods:{
            reg(){
                if(this.password1 !== this.password2){
                    alert('Passwords doesn\'t match')
                }else {
                    this.$store.dispatch('registration', {
                        name: this.name,
                        email: this.email,
                        password: this.password1
                    }).then(()=>{
                        this.$router.push('/login');
                    }).catch(()=>{
                        alert('All fields are required')
                    })
                }
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
    .registration{
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