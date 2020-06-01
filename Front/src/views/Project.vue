<template>
    <div class="project-wrapper">
        <div class="project" v-if="resp !== null">
            <div class="project-head">
                <span class="project-head-title">Project title:</span>
                <span class="project-head-title">{{resp.project.title}}</span>
                <div class="project-head-control">
                    <button class="project-head-control-button blue" @click="editProject()" v-if="($store.state.user.id === resp.project.user_id) || ($store.state.user.role === 'administrator')">Edit project</button>
                    <button class="project-head-control-button red" @click="deleteProject()" v-if="($store.state.user.id === resp.project.user_id) || ($store.state.user.role === 'administrator')">Delete project</button>
                </div>
            </div>
            <div class="project-content">
                    <table class="project-content-table">
                        <tr class="project-content-table-row" >
                            <td>Author</td>
                            <td>{{resp.project.user_name}}</td>
                        </tr>
                        <tr class="project-content-table-row" >
                            <td>Type</td>
                            <td>{{resp.project.type}}</td>
                        </tr>
                        <tr  v-if="resp.project.organization" class="project-content-table-row" >
                            <td>Organization</td>
                            <td>{{resp.project.organization}}</td>
                        </tr>
                        <tr v-if="resp.project.start" class="project-content-table-row" >
                            <td>Start</td>
                            <td>{{resp.project.start}}</td>
                        </tr>
                        <tr v-if="resp.project.end" class="project-content-table-row" >
                            <td>End</td>
                            <td>{{resp.project.end}}</td>
                        </tr>
                        <tr class="project-content-table-row" >
                            <td>Description</td>
                            <td>{{resp.project.description}}</td>
                        </tr>
                    </table>
            </div>
            <div class="project-skills">
                <span class="project-skills-title">Skills</span>
                <carousel :per-page="5" :spacePadding=10 :mouse-drag="true">
                    <slide v-for="skill in resp.skills" v-bind:key="skill.id">
                        <div class="project-skills-item">
                            <span class="project-skills-item-text">{{skill.title}}</span>
                            <button class="project-skills-item-delete" @click="removeSkill(skill.id_key)"  v-if="($store.state.user.id === resp.project.user_id) || ($store.state.user.role === 'administrator')">Delete</button>
                        </div>
                    </slide>
                    <slide v-if="($store.state.user.id === resp.project.user_id) || ($store.state.user.role === 'administrator')">
                        <div class="project-skills-item">
                            <input  class="project-skills-item-text"  type="text" ref="skill" placeholder="Type skill name...">
                            <button class="project-skills-item-delete green"  @click="addSkill()">Add skill</button>
                        </div>
                    </slide>
                </carousel>


            </div>
            <div class="project-files">
                <span class="project-files-title">Attachments</span>
                <carousel :per-page="2"  :mouse-drag="true">
                    <slide v-for="file in resp.attachments" v-bind:key="file.id">
                        <div class="card">
                            <span class="card-title">{{file.url}}</span>
                            <a  class="card-link" :href="'http://172.17.202.252/prjFiles/'+file.url"> <img class="card-img" v-if="!file.url.match(/(^.*\.doc$)|(^.*\.pdf$)|(^.*\.docx$)/) "  :src="'http://172.17.202.252/prjFiles/'+file.url">
                                <div  class="card-img" v-else><svg  class="card-img" version="1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" enable-background="new 0 0 48 48">
                                    <polygon fill="#90CAF9" points="40,45 8,45 8,3 30,3 40,13"/>
                                    <polygon fill="#E1F5FE" points="38.5,14 29,14 29,4.5"/>
                                    <g fill="#1976D2">
                                        <rect x="16" y="21" width="17" height="2"/>
                                        <rect x="16" y="25" width="13" height="2"/>
                                        <rect x="16" y="29" width="17" height="2"/>
                                        <rect x="16" y="33" width="13" height="2"/>
                                    </g>
                                </svg></div></a>
                            <button class="card-delete" @click="removeFile(file.id)"  v-if="($store.state.user.id === resp.project.user_id) || ($store.state.user.role === 'administrator')">Delete</button>
                        </div>
                    </slide>
                    <slide v-if="($store.state.user.id === resp.project.user_id) || ($store.state.user.role === 'administrator')">
                        <div class="card">
                            <span class="card-title">Upload file</span>
                            <div  class="card-link" >
                                <div class="card-img">
                                    <svg class="card-img" viewBox="0 0 60 60"  xmlns="http://www.w3.org/2000/svg"><g id="Page-1" fill="none" fill-rule="evenodd"><g id="135---Upload-File" fill-rule="nonzero"><path id="Shape" d="m45 5v5.64c-.3100728-.0017336-.6028238.1427891-.79.39l-7.97 10.36c-.2321692.3015898-.2730081.7088268-.1053486 1.0505127.1676595.3416858.5147463.5585742.8953486.5594873h2.97c.5522847 0 1 .4477153 1 1v8.7c-2.4140699.8930859-4.4288624 2.6205577-5.68 4.87-1.3148871-.924088-2.88287-1.4199757-4.49-1.42-4.098463-.0798372-7.4887279 3.1717757-7.58 7.27.0025703 1.2394906.3301312 2.4566379.95 3.53-2.4876122.8596901-4.1679958 3.1882217-4.2 5.82-.0024343.7641659.143604 1.5215278.43 2.23h-15.43c-2.209139 0-4-1.790861-4-4v-37h9c2.209139 0 4-1.790861 4-4v-9h27c2.209139 0 4 1.790861 4 4z" fill="#a4c2f7"/><path id="Shape" d="m29.289 36.3c-.4803677-.0971145-.9689302-.1480134-1.459-.152-4.098463-.0798372-7.4887279 3.1717757-7.58 7.27.0025703 1.2394906.3301312 2.4566379.95 3.53-2.4882959.8599378-4.1688345 3.1894844-4.2 5.822-.0024343.7641659.143604 1.5215278.43 2.23h3c-.286396-.7084722-.4324343-1.4658341-.43-2.23.0320042-2.6317783 1.7123878-4.9603099 4.2-5.82-.6198688-1.0733621-.9474297-2.2905094-.95-3.53.0579363-3.5096239 2.5858828-6.4900808 6.039-7.12z" fill="#7facfa"/><path id="Shape" d="m41 1h-3c2.209139 0 4 1.790861 4 4v5.64c-.3100728-.0017336-.6028238.1427891-.79.39l-7.97 10.36c-.2321692.3015898-.2730081.7088268-.1053486 1.0505127.1676595.3416858.5147463.5585742.8953486.5594873h2.97c.5522847 0 1 .4477153 1 1v8.7c-1.9933891.7448886-3.724038 2.0589578-4.977 3.779.8206538.2368081 1.5974232.605267 2.3 1.091 1.2503977-2.2488979 3.2640474-3.9763021 5.677-4.87v-8.7c0-.5522847-.4477153-1-1-1h-2.97c-.3806023-.0009131-.7276891-.2178015-.8953486-.5594873-.1676595-.3416859-.1268206-.7489229.1053486-1.0505127l7.97-10.36c.1871762-.2472109.4799272-.3917336.79-.39v-5.64c0-2.209139-1.790861-4-4-4z" fill="#7facfa"/><path id="Shape" d="m14 1v9c0 2.209139-1.790861 4-4 4h-9z" fill="#e8edfc"/><path id="Shape" d="m59 52.77c-.0765094 3.5144351-2.9854814 6.3025729-6.5 6.23h-26c-2.6494875.0284709-5.0510605-1.5541143-6.07-4-.286396-.7084722-.4324343-1.4658341-.43-2.23.0320042-2.6317783 1.7123878-4.9603099 4.2-5.82-.6198688-1.0733621-.9474297-2.2905094-.95-3.53.0912721-4.0982243 3.481537-7.3498372 7.58-7.27 1.60713.0000243 3.1751129.495912 4.49 1.42 1.2511376-2.2494423 3.2659301-3.9769141 5.68-4.87v12.3c0 .5522847.4477153 1 1 1h6c.5522847 0 1-.4477153 1-1v-12.23c4.0203621 1.4998194 6.7031019 5.319246 6.75 9.61-.0037166 1.5713127-.3771119 3.1197043-1.09 4.52 2.5585622.8196318 4.3063464 3.1835704 4.34 5.87z" fill="#e8edfc"/><path id="Shape" d="m38 34.412v10.588c0 .5522847.4477153 1 1 1h3c-.5522847 0-1-.4477153-1-1v-12.3c-1.083917.4091207-2.0963967.9869091-3 1.712z" fill="#cad9fc"/><path id="Shape" d="m54.66 46.9c.7128881-1.4002957 1.0862834-2.9486873 1.09-4.52-.0468981-4.290754-2.7296379-8.1101806-6.75-9.61v1.768c2.3611081 1.9206712 3.7372103 4.7983762 3.75 7.842-.0037166 1.5713127-.3771119 3.1197043-1.09 4.52 2.5585622.8196318 4.3063464 3.1835704 4.34 5.87-.0765094 3.5144351-2.9854814 6.3025729-6.5 6.23h3c3.5145186.0725729 6.4234906-2.7155649 6.5-6.23-.0336536-2.6864296-1.7814378-5.0503682-4.34-5.87z" fill="#cad9fc"/><path id="Shape" d="m52.97 23h-2.97c-.5522847 0-1 .4477153-1 1v21c0 .5522847-.4477153 1-1 1h-6c-.5522847 0-1-.4477153-1-1v-21c0-.5522847-.4477153-1-1-1h-2.97c-.3806023-.0009131-.7276891-.2178015-.8953486-.5594873-.1676595-.3416859-.1268206-.7489229.1053486-1.0505127l7.97-10.36c.1894263-.2440794.4810387-.3868932.79-.3868932s.6005737.1428138.79.3868932l7.97 10.36c.2321692.3015898.2730081.7088268.1053486 1.0505127-.1676595.3416858-.5147463.5585742-.8953486.5594873z" fill="#e8edfc"/><path id="Shape" d="m53.76 21.39-7.97-10.36c-.1894263-.2440794-.4810387-.3868932-.79-.3868932s-.6005737.1428138-.79.3868932l-.71.923 7.26 9.437c.2321692.3015898.2730081.7088268.1053486 1.0505127-.1676595.3416858-.5147463.5585742-.8953486.5594873h-2.97c-.5522847 0-1 .4477153-1 1v21c0 .5522847-.4477153 1-1 1h3c.5522847 0 1-.4477153 1-1v-21c0-.5522847.4477153-1 1-1h2.97c.3806023-.0009131.7276891-.2178015.8953486-.5594873.1676595-.3416859.1268206-.7489229-.1053486-1.0505127z" fill="#cad9fc"/><path id="Shape" d="m5 55h3c-2.209139 0-4-1.790861-4-4v-37l13-13h-3l-13 13v37c0 2.209139 1.790861 4 4 4z" fill="#fff"/><path id="Rectangle-path" d="m0 43h2v8h-2z" fill="#fff"/><g fill="#428dff"><path id="Shape" d="m35 6c.5522847 0 1-.44771525 1-1s-.4477153-1-1-1h-6c-.5522847 0-1 .44771525-1 1s.4477153 1 1 1z"/><path id="Shape" d="m41 6c.5522847 0 1-.44771525 1-1s-.4477153-1-1-1h-2c-.5522847 0-1 .44771525-1 1s.4477153 1 1 1z"/><path id="Shape" d="m9 20h9c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1h-9c-.55228475 0-1 .4477153-1 1s.44771525 1 1 1z"/><path id="Shape" d="m22 19c0 .5522847.4477153 1 1 1h8c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1h-8c-.5522847 0-1 .4477153-1 1z"/><path id="Shape" d="m9 25h15c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1h-15c-.55228475 0-1 .4477153-1 1s.44771525 1 1 1z"/><path id="Shape" d="m9 30h9c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1h-9c-.55228475 0-1 .4477153-1 1s.44771525 1 1 1z"/><path id="Shape" d="m55.989 46.375c.5032696-1.2717784.7614736-2.6272645.761-3.995-.0273794-4.4543198-2.6714766-8.4760894-6.75-10.267v-8.113h2.969c.7619202.000163 1.457747-.4325997 1.7944905-1.116066.3367436-.6834663.2558999-1.4988943-.2084905-2.102934l-7.97-10.36c-.1591429-.2000741-.3584195-.364605-.585-.483v-4.938c-.0033061-2.76005315-2.2399468-4.99669388-5-5h-27c-.2651948.00005664-.5195073.10545063-.707.293l-13 13c-.18754937.1874927-.29294336.4418052-.293.707v29c0 .5522847.44771525 1 1 1s1-.4477153 1-1v-28h8c2.7600532-.0033061 4.9966939-2.2399468 5-5v-8h26c1.6568542 0 3 1.34314575 3 3v4.938c-.2265805.118395-.4258571.2829259-.585.483l-7.969 10.359c-.4648847.6038939-.5461601 1.4195059-.2095895 2.1032645.3365706.6837587 1.0324835 1.1168106 1.7945895 1.1167355h2.969v8.036c-2.0289252.8891847-3.7660443 2.3323937-5.012 4.164-1.2777798-.688627-2.7064739-1.0494083-4.158-1.05-4.6502555-.0786277-8.487525 3.6199993-8.58 8.27.0014189 1.0429338.2051843 2.0756848.6 3.041-2.3424917 1.2385948-3.819752 3.6593831-3.85 6.309.0009251.4125035.0384051.8241136.112 1.23h-14.112c-1.65685425 0-3-1.3431458-3-3 0-.5522847-.44771525-1-1-1s-1 .4477153-1 1c.00330612 2.7600532 2.23994685 4.9966939 5 5h14.812c1.3021307 2.4826769 3.8847011 4.0272764 6.688 4h26c4.0652284.0685868 7.41956-3.1649889 7.5-7.23-.022792-2.7178626-1.5741326-5.1912666-4.011-6.395zm-45.989-33.375h-6.586l9.586-9.586v6.586c0 1.6568542-1.3431458 3-3 3zm27.031 9 7.969-10.36 7.969 10.36h-2.969c-1.1045695 0-2 .8954305-2 2v21h-6v-21c0-1.1045695-.8954305-2-2-2zm15.469 36h-26c-2.2392108.0179433-4.2688701-1.3143783-5.143-3.376-.2380639-.5890132-.3593143-1.2187004-.357-1.854.021157-2.2146836 1.4427787-4.1729255 3.542-4.879.2773347-.1008183.4959814-.3189803.597415-.5960906.1014335-.2771103.0752965-.584873-.071415-.8409094-.5352204-.9215976-.817412-1.9682595-.818-3.034.091668-3.5457415 3.0339629-6.3494177 6.58-6.27 1.4023371.0002504 2.7703638.4336742 3.917 1.241.2335156.1606817.523672.2161741.8.153.276524-.0647208.5124865-.2439651.649-.493.8843883-1.5953695 2.2035278-2.9068799 3.804-3.782v10.731c0 1.1045695.8954305 2 2 2h6c1.1045695 0 2-.8954305 2-2v-10.646c2.9148594 1.622971 4.7299075 4.6898291 4.75 8.026-.0027233 1.4154499-.339522 2.8102693-.983 4.071-.1312369.2592533-.1432161.5627699-.0328202.8315601.110396.2687901.3322483.4762688.6078202.5684399 2.1497627.6831341 3.6225199 2.6635858 3.658 4.919-.0801662 2.9605146-2.5392002 5.2988324-5.5 5.23z"/><circle id="Oval" cx="1" cy="47" r="1"/></g></g></g></svg>
                                </div>
                            </div>
                            <div class="card-upload">
                                <div class="field__wrapper">

                                    <input @input="importFile()" name="file" ref="fileInput" type="file"  id="field__file-2" class="field field__file" >

                                    <label class="field__file-wrapper" for="field__file-2">
                                        <div class="field__file-fake">Select file</div>
                                    </label>

                                </div>
                            </div>
                        </div>
                    </slide>
                </carousel>
            </div>
        </div>
    </div>
</template>
<script>
    import { Carousel, Slide } from 'vue-carousel';
    export default {
        data(){

          return {
              resp: null
          }
        },
        components: {
            Carousel,
            Slide
        },
        methods:{
            deleteProject(){
                this.$store.dispatch('deleteProject', this.resp.project.id).then(()=>{
                    this.$router.back();
                })
            },
            editProject(){
                this.$router.push('/projectEdit/'+this.$route.params.id);
            },
            removeSkill(id){
                this.$store.dispatch('removeSkill', id).then(()=>{
                    this.$store.dispatch('getProjectById', this.$route.params.id).then((resp)=>{
                        this.resp = resp.data;
                    })
                })
            },
            addSkill(){
                if(this.$refs.skill.value !== ''){
                    this.$store.dispatch('addSkill', {title: this.$refs.skill.value, project_id: this.$route.params.id}).then(()=>{
                        this.$store.dispatch('getProjectById', this.$route.params.id).then((resp)=>{
                            this.resp = resp.data;
                            this.$refs.skill.value = '';
                        })
                    })
                }
            },
            removeFile(id){
                this.$store.dispatch('removeFile', id).then(()=>{
                    this.$store.dispatch('getProjectById', this.$route.params.id).then((resp)=>{
                        this.resp = resp.data;
                    })
                });
            },
            importFile(){
                this.$store.dispatch('importFile', {file: this.$refs.fileInput.files[0], project_id:this.$route.params.id}).then(()=>{
                    this.$store.dispatch('getProjectById', this.$route.params.id).then((resp)=>{
                        this.resp = resp.data;
                    })
                });
            },
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
        }
    }
</script>
<style lang="scss" scoped>

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

    .red {
        background-color: red;
    }
    .blue {
        background-color: cornflowerblue;
    }


    .project{
        min-height: calc(100vh - 100px);
        &-content{
            display: flex;
            justify-content: center;
            width: 100%;
            &-table{
                font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: 100%;
                &-row {
                    &:nth-child(even){background-color: #f2f2f2;}

                    &:hover {background-color: #ddd;}
                    td{
                        border: 1px solid #ddd;
                        padding: 8px;

                        &:first-child{
                            width: 18%;
                            background-color: darkgreen;
                            color: white;
                        }
                    }
                }
            }
        }
        &-head {
            display: flex;
            align-items: center;
            width: 100%;
            height: 60px;
            background-color: darkgreen;
            &-title {
                margin-left: 15px;
                color: white;
                font-size: 25px;
            }
            &-control {
                margin-left: auto;
                margin-right: 15px;
                &-button {
                    padding: 8px;
                    outline: none;
                    border: none;
                    color: white;
                    font-size: 15px;
                    margin-left: 40px;
                    min-width: 150px;
                    cursor: pointer;
                    &:hover {
                        background-color: #333333;
                    }
                }
            }
        }




        &-files {
            margin-bottom: 40px;
            &-title{
                margin-bottom: 25px;
                width: 100%;
                background-color: yellowgreen;
                color: white;
                display: flex;
                align-items: center;
                padding: 10px 0;
                justify-content: center;
                font-size: 20px;
            }
        }
        &-skills{
            margin: 0 0 40px 0;
            &-title{
                margin-bottom: 25px;
                width: 100%;
                background-color: #45a049;
                color: white;
                display: flex;
                align-items: center;
                padding: 10px 0;
                justify-content: center;
                font-size: 20px;
            }
            &-item{
                margin-left: 20px;
                margin-right: 20px;
                display: flex;
                align-items: center;
                border: 1px solid #45a049;
                border-radius: 10px;
                padding: 5px;
                &-text {
                    margin-left: 15px;
                    margin-right: 10px;
                }
                &-delete {
                    margin-left: auto;
                    cursor: pointer;
                    background-color: red;
                    color: white;
                    outline: none;
                    border: none;
                    min-width: 90px;
                    &:hover{
                        background-color: #333333;
                    }
                    padding: 5px;
                }

            }
        }
    }
.green{
    background-color: #45a049;
}


    .card {
        max-width: 600px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 400px;
        border: 1px solid black;
        &-img{
            width: 100%;
            max-height: 300px;
            object-fit: contain;
        }
        &-title{
            width: 100%;
            height: 30px;
            background-color: yellowgreen;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        &-upload{
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 30px;
            background-color: yellowgreen;
            outline: none;
            color: white;
            cursor: pointer;
            border: none;
            &:hover{
                background-color: #333333;
            }
        }
        &-delete {
            width: 100%;
            height: 30px;
            background-color: yellowgreen;
            outline: none;
            padding: 5px;
            color: white;
            cursor: pointer;
            border: none;
            &:hover{
                background-color: #333333;
            }
        }
    }

</style>