<template>
    <div class="form-wrapper">
        <div class="form" v-if="resp !== null">
            <label for="title">Title *</label>
            <input type="text" :value="resp.project.title" id="title" @input="removeError('title')" ref="title" placeholder="Project title..">

            <label for="organization">Organization</label>
            <input type="text" :value="resp.project.organization" id="organization" ref="organization" placeholder="Project organization..">

            <label for="type">Type *</label>
            <select id="type" :value="resp.project.type"  @select="removeError('type')" ref="type">
                <option value="work">work</option>
                <option value="book">book</option>
                <option value="course">course</option>
                <option value="blog">blog</option>
                <option value="other">other</option>
            </select>

            <label for="description">Description *</label>
            <input type="text"  :value="resp.project.description" @input="removeError('description')" id="description" ref="description" placeholder="Project description..">

            <label for="start">Start</label>
            <input type="date"  :value="resp.project.start" id="start" ref="start" placeholder="Project start..">

            <label for="end">End</label>
            <input type="date"  :value="resp.project.end" id="end" ref="end" placeholder="Project end..">

            <input type="submit" @click="editProject()" value="Submit">
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                resp: null
            }
        },
        mounted() {
            if(this.$route.params.id !== null){
                if(this.$store.state.user !== null) {
                    this.$store.dispatch('getProjectById', this.$route.params.id).then((resp)=>{
                        this.resp = resp.data;
                    })
                }
                this.$store.watch(
                    (state, getters) => getters.user,
                    () => {
                        if(this.$store.state.user !== null){
                            this.$store.dispatch('getProjectById', this.$route.params.id).then((resp)=>{
                                this.resp = resp.data;
                            })
                        }
                    })
            }
        },
        methods:{
            editProject(){
                let params = '?project_id='+this.$route.params.id+'&';
                let flag = false;
                if(this.$refs.title.value !== ''){
                    params+= 'title='+this.$refs.title.value+'&';
                }else {
                    flag = true;
                    this.$refs.title.classList.add('error');
                }
                if(this.$refs.type.value !== ''){
                    params+= 'type='+this.$refs.type.value+'&';
                }else {
                    flag = true;
                    this.$refs.type.classList.add('error');
                }
                if(this.$refs.description.value !== ''){
                    params+= 'description='+this.$refs.description.value+'&';
                }else {
                    flag = true;
                    this.$refs.description.classList.add('error');
                }
                if(this.$refs.organization.value !== ''){
                    params+= 'organization='+this.$refs.organization.value+'&';
                }
                if(this.$refs.start.value !== ''){
                    params+= 'start='+this.$refs.start.value+'&';
                }
                if(this.$refs.end.value !== ''){
                    params+= 'end='+this.$refs.end.value+'&';
                }

                if (!flag){
                    this.$store.dispatch('editProject', params).then(() => {
                        this.$router.back();
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