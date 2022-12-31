<template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 v-if="!video.id" class="page-title">Add Video</h4>
                <h4 v-if="video.id" class="page-title">Update Video</h4>
            </div>
        </div>

        <div class="page-content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form>
                                            <input type="hidden" v-model="video.id">
                                            <div class="form-group">
                                                <label>Video Name</label>
                                                <div>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" placeholder="Enter name of video" v-model="video.name">
                                                        <span class="input-group-addon bg-custom b-0">
                                                            <i class="mdi mdi-videopaper"></i>
                                                        </span>
                                                    </div>
                                                    <!-- input-group -->
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Video URL</label>
                                                <div>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" placeholder="Enter embeded URL" v-model="video.link">
                                                        <span class="input-group-addon bg-custom b-0">
                                                            <i class="mdi mdi-videopaper"></i>
                                                        </span>
                                                    </div>
                                                    <!-- input-group -->
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Description</label>
                                                <div>
                                                    <div class="input-group">
                                                        <textarea class="form-control textarea-xlg" placeholder="Enter description" v-model="video.text"></textarea>
                                                    </div>
                                                    <!-- input-group -->
                                                </div>
                                            </div>
                                            <!-- <label>Description</label>
                                            <vue-editor v-model="video.text"></vue-editor> -->
                                            <!-- <div class="summernote"></div> -->
                                            <div class="text-center">
                                                <button v-if="!video.id" type="button" class="btn btn-primary waves-effect waves-light" @click="addvideo">Add Video</button>
                                                <button v-if="video.id" type="button" class="btn btn-primary waves-effect waves-light" @click="updatevideo">Update Video</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End row -->
            </div>
            <!-- container -->
        </div>
        <!-- Page content Wrapper -->
    </div>
    <!-- content -->
</template>

<script>
    import { apiAdminHost } from'./../../admin-config/config';
    import moment from 'moment';

    export default {
        data() {
            return {
                arrProducts:[],
                video:{
                    id:'',
                    name:'',
                    link:'',
                    text:'',
                }
            }
        },
        mounted() {
            if(this.$route.params.id){
                this.getVideoById(this.$route.params.id);
            }
        },
        methods: {
            
            addvideo(){
                axios.post('/store/video',this.video)
                .then(resp => {
                    if(resp.data.code === 200){
                        this.$toaster.success(resp.data.message);
                        this.$router.push({ name:'managevideos'});
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                }).catch(err => {
                    console.log(err);
                })
            },
            updatevideo(){
                axios.post('/update/video',this.video)
                .then(resp => {
                    if(resp.data.code === 200){
                        this.$toaster.success(resp.data.message);
                        this.$router.push({ name:'managevideos'});
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                }).catch(err => {
                    console.log(err);
                })
            },
            getVideoById(id){
                axios.post('/edit/video',{
                    id: id
                }).then(resp => {
                    if(resp.data.code === 200){
                        this.video = {
                            id: resp.data.data.id,
                            name: resp.data.data.name,
                            text: resp.data.data.text,
                            link: resp.data.data.link,
                        }
                    }

                }).catch(err => {
                    console.log(err);
                })
            },
        }
    }
</script>   