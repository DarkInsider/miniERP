<template>
    <div class="form-wrapper">
        <div class="form">
            <label for="title">Title *</label>
            <input type="text" id="title" @input="removeError('title')" ref="title" placeholder="Project title..">

            <label for="organization">Organization</label>
            <input type="text" id="organization" ref="organization" placeholder="Project organization..">

            <label for="type">Type *</label>
            <select id="type"  @select="removeError('type')" ref="type">
                <option value="work">work</option>
                <option value="book">book</option>
                <option value="course">course</option>
                <option value="blog">blog</option>
                <option value="other">other</option>
            </select>

            <label for="description">Description *</label>
            <input type="text" @input="removeError('description')" id="description" ref="description" placeholder="Project description..">

            <label for="start">Start</label>
            <input type="date" id="start" ref="start" placeholder="Project start..">

            <label for="end">End</label>
            <input type="date" id="end" ref="end" placeholder="Project end..">

            <input type="submit" @click="createProject()" value="Submit">
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {

            }
        },
        methods:{
            createProject(){
                let obj = {};
                let flag = false;
                if(this.$refs.title.value !== ''){
                    obj.title = this.$refs.title.value;
                }else {
                    flag = true;
                    this.$refs.title.classList.add('error');
                }
                if(this.$refs.type.value !== ''){
                    obj.type = this.$refs.type.value;
                }else {
                    flag = true;
                    this.$refs.type.classList.add('error');
                }
                if(this.$refs.description.value !== ''){
                    obj.description = this.$refs.description.value;
                }else {
                    flag = true;
                    this.$refs.description.classList.add('error');
                }
                if(this.$refs.organization.value !== ''){
                    obj.organization = this.$refs.organization.value;
                }
                if(this.$refs.start.value !== ''){
                    obj.start = this.$refs.start.value;
                }
                if(this.$refs.end.value !== ''){
                    obj.end = this.$refs.end.value;
                }

                if (!flag){
                    this.$store.dispatch('createProject', obj).then(() => {
                        this.$router.push('/');
                    }).catch(() => {
                        alert('Error has occurred')
                    })
                }
            },
            removeError(ref){
                this.$refs[ref].classList.remove('error');
            }
        }
    }
</script>
<style lang="scss" scoped>
    .error{
        border-color: red !important;
    }
    .form{
        max-width: 400px;
        &-wrapper{
            display: flex;
            justify-content: center;
            height: calc(100vh - 140px);
        }
    }
    input[type=text],input[type=date], select {
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
        padding: 20px;
    }
</style>