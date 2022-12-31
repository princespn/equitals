<template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 v-if="!gallery.id" class="page-title">Add Gallery</h4>
                <h4 v-if="gallery.id" class="page-title">Update Gallery</h4>
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
                                            <input type="hidden" v-model="gallery.id">
                                            <div class="form-group">
                                                <label>Title</label>
                                                <div>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" placeholder="Name For Gallery" v-model="gallery.name">
                                                        <span class="input-group-addon bg-custom b-0">
                                                            <i class="mdi mdi-gallerypaper"></i>
                                                        </span>
                                                    </div>
                                                    <!-- input-group -->
                                                </div>
                                            </div>
                                            <!-- <label>Description</label>
                                            <vue-editor v-model="gallery.text"></vue-editor> -->
                                            <!-- <div class="summernote"></div> -->
                                            <div class="text-center">
                                                <button v-if="!gallery.id" type="button" class="btn btn-primary waves-effect waves-light" @click="addGallery">Add Gallery</button>
                                                <button v-if="gallery.id" type="button" class="btn btn-primary waves-effect waves-light" @click="updateGallery">Update Gallery</button>
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
                gallery:{
                    id:'',
                    name:'',
                    text:'',
                }
            }
        },
        mounted() {
            this.getProducts();
            if(this.$route.params.id){
                this.getGalleryById(this.$route.params.id);
            }
        },
        methods: {
            getProducts(){
                axios.get('/getproducts')
                .then(resp => {
                    if(resp.data.code === 200){
                        this.arrProducts = resp.data.data;
                    }
                }).catch(err => {
                    console.log(err);
                })
            },
            addGallery(){
                axios.post('/store/gallery',this.gallery)
                .then(resp => {
                    if(resp.data.code === 200){
                        this.$toaster.success(resp.data.message);
                        this.$router.push({ name:'managegallery'});
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                }).catch(err => {
                    console.log(err);
                })
            },
            updateGallery(){
                axios.post('/update/gallery',this.gallery)
                .then(resp => {
                    if(resp.data.code === 200){
                        this.$toaster.success(resp.data.message);
                        this.$router.push({ name:'managegallery'});
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                }).catch(err => {
                    console.log(err);
                })
            },
            getGalleryById(id){
                axios.post('/edit/gallery',{
                    id: id
                }).then(resp => {
                    if(resp.data.code === 200){
                        this.gallery = {
                            id: resp.data.data.id,
                            name: resp.data.data.name,
                            text: resp.data.data.text,
                        }
                    }

                }).catch(err => {
                    console.log(err);
                })
            },
        }
    }
</script>   