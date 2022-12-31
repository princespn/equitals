<template>
	<!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 v-if="!category.id" class="page-title">Add Category</h4>
                <h4 v-if="category.id" class="page-title">Update Category</h4>
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
                                        <form class="ecommerce-product-form" v-on:submit.prevent="saveUpdateCategory">
                                            <input type="hidden" v-model="category.id">
                                            
                                            <div class="form-group">
                                                <label>Category Name</label>
                                                <div>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" placeholder="Category Name" v-model="category.name" name="name" v-validate="'required|regex:^[A-Za-z]+[A-Za-z0-9][A-Za-z0-9 -]*$'">
                                                        <span class="input-group-addon bg-custom b-0">
                                                            <i class="mdi mdi-productpaper"></i>
                                                        </span>
                                                        <div class="tooltip2" v-show="errors.has('name')">
                                                            <div class="tooltip-inner">
                                                                <span v-show="errors.has('name')">{{ errors.first('name') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                          <!--   <div class="form-group">
                                                <label>Category Image</label>
                                                <div v-if="category.id">
                                                    <a :href="category.image" target="_blank"><img :src="category.image" class="img-responsive" width="100"></a>
                                                </div>
                                                <input class="filestyle" data-buttonname="btn-default" id="filestyle-0" name="attachment" type="file" ref="file">
                                            </div>    -->                                         
                                            <div class="text-center">
                                                <button v-if="!category.id" type="submit" name="submit" class="btn btn-primary waves-effect waves-light" :disabled="errors.any() || !isCompleted" id="product-btn">Add Category</button>
                                                <button v-if="category.id" type="submit" class="btn btn-primary waves-effect waves-light" :disabled="errors.any()" id="product-btn">Update Category</button>
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
                category:{
                    id:'',
                    name:'',
                    cost:'',
                    image : '',
                    description : '',
                }
            }
        },
        mounted() {
            if(this.$route.params.id){
                this.getProductById(this.$route.params.id);
            }
        },
        computed: {
            isCompleted(){
                return this.category.name;
            }
        },
        methods: {
            saveUpdateCategory(){
                document.getElementById("product-btn").disabled = true;
                if(this.category.id != ''){
                    let formData = new FormData();
                    formData.append('id', this.category.id);
                   /* if(typeof this.$refs.file.files[0]!== 'undefined'){
                       // formData.append('image', this.$refs.file.files[0]);
                    } else {
                       // formData.append('image', this.category.image);
                    }*/
                    formData.append('name', this.category.name);
                    axios.post('update/product-category',formData)
                    .then(resp => {
                        if(resp.data.code === 200){
                            this.$toaster.success(resp.data.message);
                            this.$router.push({ name:'categories'});
                        } else {
                            this.$toaster.error(resp.data.message);
                        }
                    }).catch(err => {
                        //console.log(err);
                    })
                } else{
                    let formData = new FormData();
                    /*if(typeof this.$refs.file.files[0]!== 'undefined'){
                      //  formData.append('image', this.$refs.file.files[0]);
                    }*/
                    formData.append('name', this.category.name);
                    axios.post('/store/product-category',formData)
                    .then(resp => {
                        if(resp.data.code === 200){
                            this.$toaster.success(resp.data.message);
                            this.$router.push({ name:'categories'});
                        } else {
                            this.$toaster.error(resp.data.message);
                        }
                    }).catch(err => {
                        //console.log(err);
                    })
                }
                document.getElementById("bill-desc-btn").disabled = false;
            },
            getProductById(id){
                axios.post('/edit/product-category',{
                    id: id
                }).then(resp => {
                    if(resp.data.code === 200){
                        this.category = {
                            id: resp.data.data.id,
                            name: resp.data.data.name,
                            image: resp.data.data.image,
                            status : resp.data.data.status,
                        }
                    }
                }).catch(err => {
                    //console.log(err);
                })
            },
        }
    }
</script>	