<template>
	<!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 v-if="!product.id" class="page-title">Add Product</h4>
                <h4 v-if="product.id" class="page-title">Update Product</h4>
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
                                        <form class="ecommerce-product-form" v-on:submit.prevent="saveUpdateProduct">
                                            <input type="hidden" v-model="product.id">
                                            
                                            <div class="form-group">
                                                <label>Name</label>
                                                <div>
                                                    <!-- regex:^[A-Za-z]+[A-Za-z0-9][A-Za-z0-9 -]*$' -->
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" placeholder="Enter Product Name" v-model="product.name" name="name" v-validate="required" data-vv-as="Product name">
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

                                             <!--    <div class="form-group">
                                                <label>Quantity</label>
                                                <div>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" placeholder="Enter Product Quantity" v-model="product.qty" name="quantity" v-validate="'required|regex:^[0-9]*$'" data-vv-as="Product Quantity">
                                                        <span class="input-group-addon bg-custom b-0">
                                                            <i class="mdi mdi-productpaper"></i>
                                                        </span>
                                                        <div class="tooltip2" v-show="errors.has('quantity')">
                                                            <div class="tooltip-inner">
                                                                <span v-show="errors.has('quantity')">{{ errors.first('quantity') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                             <!-- <div class="form-group">
                                                <label>Tag</label>
                                                <div>
                                                    <div class="input-group">
                                                      

                                                        <input-tag type="text" class="form-control" placeholder="Enter tag " v-model="product.tag" name="tag"  ></input-tag>

                                                        <span class="input-group-addon bg-custom b-0">
                                                            <i class="mdi mdi-productpaper"></i>
                                                        </span>
                                                        <div class="tooltip2" v-show="errors.has('tag')">
                                                            <div class="tooltip-inner">
                                                                <span v-show="errors.has('tag')">{{ errors.first('tag') }}</span>
                                                            </div>
                                                        </div>

                                                   
                                                    </div>
                                                </div>
                                            </div> -->
                                            
                                            <div class="form-group">
                                                <label>Category</label>
                                                <select class="form-control" id="category_id" name="category_id"  @change="clickOnCat($event)"  v-model="product.category_id">
                                                    <option value="">Select Category</option>
                                                    <option @click="clickOnCat(category.id)" :value="category.id" v-for="category in categories">{{category.name}}</option>
                                                </select>
                                            </div> 
                                           <!--  <div class="form-group">
                                                <label>Sub Category</label>
                                                <select class="form-control" id="sub_category_id" name="category_id" @click="CkonSubmenu(product.sub_category_id)" v-model="product.sub_category_id">
                                                    <option value="" selected="">Select Category</option>
                                                    <option :value="subcat.id" v-for="subcat in subCategories">{{subcat.name}}</option>
                                                </select>
                                            </div> -->
<!-- 
                                              <div class="form-group">
                                                <label>Submenu Variation</label>
                                                <select class="form-control" id="sub_category_id" name="category_id"  v-model="product.submenu_variation">
                                                    <option value="" selected="">Select Submenu Variation</option>
                                                    <option :value="sub.id" v-for="sub in submenuVariation">{{sub.name}}</option>
                                                </select>
                                            </div> -->
                                            <div class="form-group">
                                                <label>Country</label>
                                                <select class="form-control" id="country_id" name="country_id"  v-model="product.country_id">
                                                    <option value="">Select Country</option>
                                                    <option :value="country.code" v-for="country in countries">{{country.name}}</option>
                                                </select>
                                            </div>
                                           <!-- 
                                            <div class="form-group">
                                                <label>Manufacturer</label>
                                                <div>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" placeholder="Enter Manufacturer Name" v-model="product.manufacturer" name="manufacturer" v-validate="'required'" data-vv-as="Manufacturer">
                                                        <span class="input-group-addon bg-custom b-0">
                                                            <i class="mdi mdi-productpaper"></i>
                                                        </span>
                                                        <div class="tooltip2" v-show="errors.has('manufacturer')">
                                                            <div class="tooltip-inner">
                                                                <span v-show="errors.has('manufacturer')">{{ errors.first('manufacturer') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                           <!--  <div class="form-group">
                                                <label>Manufacturer Part Number (MPN)</label>
                                                <div>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" placeholder="Enter Manufacturer Part Number (MPN)" v-model="product.mpn" name="mpn" v-validate="'required'" data-vv-as="Manufacturer Part Number">
                                                        <span class="input-group-addon bg-custom b-0">
                                                            <i class="mdi mdi-productpaper"></i>
                                                        </span>
                                                        <div class="tooltip2" v-show="errors.has('mpn')">
                                                            <div class="tooltip-inner">
                                                                <span v-show="errors.has('mpn')">{{ errors.first('mpn') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                           <!--  <div class="form-group">
                                                <label>Brand</label>
                                                <div>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" placeholder="Enter Brand Name" v-model="product.brand_name" name="brand_name" v-validate="'required'" data-vv-as="Brand name">
                                                        <span class="input-group-addon bg-custom b-0">
                                                            <i class="mdi mdi-productpaper"></i>
                                                        </span>
                                                        <div class="tooltip2" v-show="errors.has('brand_name')">
                                                            <div class="tooltip-inner">
                                                                <span v-show="errors.has('brand_name')">{{ errors.first('brand_name') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                            <div class="form-group">
                                                <label>Variation </label>
                                                <select class="form-control" v-model="product.variation" name="variation">
                                                    <option value="" hidden="">Select Variation</option>
                                                    <!-- <option value="No">No Variation</option> -->
                                                    <option value="Package">Package</option>
                                                    <!-- <option value="Size">Size</option>
                                                    <option value="Color">Color</option> -->
                                                    <!-- <option value="Pattern">Pattern</option> -->
                                                </select>
                                                <div class="tooltip2" v-show="errors.has('variation')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('variation')">{{ errors.first('variation') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" v-if="product.variation!='No'">
                                                <label>{{product.variation}}</label>
                                                <div v-for="(var_value,index) in product.variation_values">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">{{(index+1)}}</span>
                                                        <input type="text" class="form-control" :placeholder="'Enter '+product.variation+' '+(index+1)" v-model="product.variation_values[index].value" :name="'variation_value'+(index+1)" v-validate="'required'" :data-vv-as="product.variation+' '+(index+1)">
                                                        <div class="tooltip2" v-show="errors.has('variation_value'+(index+1))">
                                                            <div class="tooltip-inner">
                                                                <span v-show="errors.has('variation_value'+(index+1))">{{ errors.first('variation_value'+(index+1)) }}</span>
                                                            </div>
                                                        </div>
                                                        <input type="number" class="form-control" :placeholder="'Enter MRP for '+product.variation+' '+(index+1)" v-model="product.variation_values[index].mrp_cost" :name="'variation_mrp_cost'+(index+1)" v-validate="'required'" :data-vv-as="'Price for '+product.variation+' '+(index+1)">
                                                        <div class="tooltip2" v-show="errors.has('variation_mrp_cost'+(index+1))">
                                                            <div class="tooltip-inner">
                                                                <span v-show="errors.has('variation_mrp_cost'+(index+1))">{{ errors.first('variation_mrp_cost'+(index+1)) }}</span>
                                                            </div>
                                                        </div>  
                                                         <!-- Product cost -->   
                                                         <input type="number" class="form-control" :placeholder="'Enter Price for '+product.variation+' '+(index+1)" v-model="product.variation_values[index].price" :name="'variation_price'+(index+1)" v-validate="'required'" :data-vv-as="'Price for '+product.variation+' '+(index+1)">
                                                        <div class="tooltip2" v-show="errors.has('variation_price'+(index+1))">
                                                            <div class="tooltip-inner">
                                                                <span v-show="errors.has('variation_price'+(index+1))">{{ errors.first('variation_price'+(index+1)) }}</span>
                                                            </div>
                                                        </div>
                                                        <span class="input-group-addon bg-custom b-0 pointer" @click="addInput" v-if="product.variation_values.length == (index+1)">
                                                            <i class="fa fa-plus"></i>
                                                        </span>
                                                        <span class="input-group-addon bg-custom b-0 pointer" @click="removeInput(index)" v-else>
                                                            <i class="fa fa-minus"></i>
                                                        </span>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                     
                                            <div class="form-group" v-if="product.variation == 'No'">
                                                <label>Price</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" placeholder="Enter Product Price" v-model="product.cost" v-validate="'required|decimal'" name="cost">
                                                    <span class="input-group-addon bg-custom b-0">
                                                        <i class="mdi mdi-productpaper"></i>
                                                    </span>
                                                    <div class="tooltip2" v-show="errors.has('cost')">
                                                        <div class="tooltip-inner">
                                                            <span v-show="errors.has('cost')">{{ errors.first('cost') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                          
                                            <div v-if="!product.id " class="form-group">
                                                <label>Upload Images</label>
                                                <input class="filestyle" data-buttonname="btn-default" id="filestyle-0" name="attachment" accept="image/*" type="file" ref="file" multiple>
                                            </div>
                                            <div class="form-group">
                                                <label>Description</label>
                                                <vue-editor v-model="product.description"></vue-editor>
                                            </div>
                                            
                                            <!-- <label>Description</label> -->
                                            <!-- <vue-editor v-model="product.description"></vue-editor> -->
                                            <!-- <div class="summernote"></div> -->
                                            <div class="text-center">
                                                <button v-if="!product.id" type="submit" name="submit" class="btn btn-primary waves-effect waves-light" :disabled="errors.any() || !isCompleted || disableBtn" id="product-btn">Add product</button>

                                                <button v-if="product.id" type="submit" class="btn btn-primary waves-effect waves-light" :disabled="errors.any() || disableBtn" id="product-btn">Update product</button>
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
        import Swal from 'sweetalert2';


    export default {
        data() {
            return {
                arrProducts:[],
                product:{
                    id:'',
                    tag:[],
                    name:'',
                    qty:'1000000',
                    cost:0,
                    mrp_cast:0,
                    image : '',
                    description : '',
                    category_id:'',
                    sub_category_id:'',
                    submenu_variation:'',
                    country_id:'',
                    brand_name:'',
                    manufacturer:'',
                    mpn:'',
                    variation:'Size',
                    variation_values:[{'id':'','value':'','price':'','mrp_cost':''}]
                },
                categories:[],
                countries:[],
                files:[],
                disableBtn:false,
                subCategories:[],
                submenuVariation:[]
            }
        },
        mounted() {
            if(this.$route.params.id){
                this.getProductById(this.$route.params.id);
            }
            this.getCategories();
            this.getCountries();
        },
        computed: {
            isCompleted(){
                return this.product.name  && this.product.description;
            }
        },
        methods: {
            addInput()
            {
                this.product.variation_values.push({'id':'','value':'','price':'','mrp_cost':''});
            },
            removeInput(index)
            {
                this.product.variation_values.splice(index,1);
            },
            saveUpdateProduct(){



                 Swal({
                    title: 'Are you sure ?',
                    text: "You update this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                      
             
                this.disableBtn = true;
                if(this.product.id != ''){
                    let formData = new FormData();
                    formData.append('id', this.product.id);
                    /*if(typeof this.$refs.file.files[0]!== 'undefined'){
                        formData.append('file', this.$refs.file.files);
                    } else {
                        formData.append('file', this.product.image);
                    }*/
                    formData.append('name', this.product.name);
                    formData.append('tag', this.product.tag);
                    formData.append('qty', this.product.qty);
                    formData.append('cost', this.product.cost);
                    formData.append('mrp_cast', this.product.mrp_cast);
                    formData.append('description', this.product.description);
                    formData.append('category_id', this.product.category_id);
                    formData.append('sub_category_id', this.product.sub_category_id);
                    formData.append('submenu_variation', this.product.submenu_variation);
                    formData.append('country_id', this.product.country_id);
                    formData.append('brand_name', this.product.brand_name);
                    formData.append('manufacturer', this.product.manufacturer);
                    formData.append('mpn', this.product.mpn);
                    formData.append('variation', this.product.variation);
                    formData.append('variation_values', JSON.stringify(this.product.variation_values));
                    axios.post('update/ecommerce-product',formData)
                    .then(resp => {
                        this.disableBtn = false;
                        if(resp.data.code === 200){
                            this.$toaster.success(resp.data.message);
                            this.$router.push({ name:'productreports'});
                        } else {
                            this.$toaster.error(resp.data.message);
                        }
                    }).catch(err => {
                        //console.log(err);
                    })
                } else{
                    let formData = new FormData();
                    if(typeof this.$refs.file.files[0]!== 'undefined')
                    {
                        this.files = this.$refs.file.files;
                        for( var i = 0; i < this.files.length; i++ ){
                          let file = this.files[i];

                          formData.append('files[' + i + ']', file);
                        }
                    }
                    formData.append('name', this.product.name);
                     formData.append('tag', this.product.tag);
                     formData.append('qty', this.product.qty);
                    formData.append('cost', this.product.cost);
                    formData.append('mrp_cast', this.product.mrp_cast);
                    formData.append('description', this.product.description);
                    formData.append('category_id', this.product.category_id);
                    formData.append('sub_category_id', this.product.sub_category_id);
                    formData.append('submenu_variation', this.product.submenu_variation);
                    formData.append('country_id', this.product.country_id);
                    formData.append('brand_name', this.product.brand_name);
                    formData.append('manufacturer', this.product.manufacturer);
                    formData.append('mpn', this.product.mpn);
                    formData.append('variation', this.product.variation);
                    formData.append('variation_values', JSON.stringify(this.product.variation_values));
                    //axios.post('/store/product',this.product)
                    axios.post('/store/ecommerce-product',formData)
                    .then(resp => {
                        this.disableBtn = false;
                        if(resp.data.code === 200){
                            this.$toaster.success(resp.data.message);
                            this.$router.push({ name:'productreports'});
                        } else {
                            this.$toaster.error(resp.data.message);
                        }
                    }).catch(err => {
                        //console.log(err);
                    })
                }
                this.disableBtn = false;

                       }
                });
            },
            addProduct(){
                let formData = new FormData();
                if(typeof this.$refs.file.files[0]!== 'undefined'){
                    formData.append('file', this.$refs.file.files[0]);
                }
                formData.append('name', this.product.name);
                formData.append('cost', this.product.cost);
                formData.append('description', this.product.description);
                //axios.post('/store/product',this.product)
                axios.post('/store/ecommerce-product',formData)
                .then(resp => {
                    if(resp.data.code === 200){
                        this.$toaster.success(resp.data.message);
                        this.$router.push({ name:'productreports'});
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                }).catch(err => {
                    //console.log(err);
                })
            },
            updateProduct(){
                //axios.post('/update/product',this.product)
                let formData = new FormData();
                formData.append('id', this.product.id);
                if(typeof this.$refs.file.files[0]!== 'undefined'){
                    formData.append('file', this.$refs.file.files[0]);
                } else {
                    formData.append('file', this.product.image);
                }
                formData.append('name', this.product.name);
                formData.append('cost', this.product.cost);
                formData.append('description', this.product.description);
                axios.post('update/ecommerce-product',formData)
                .then(resp => {
                    if(resp.data.code === 200){
                        this.$toaster.success(resp.data.message);
                        this.$router.push({ name:'productreports'});
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                }).catch(err => {
                    //console.log(err);
                })
            },
            getProductById(id){
                axios.post('/edit/ecommerce-product',{
                    id: id
                }).then(resp => {
                    if(resp.data.code === 200){
                        this.product = resp.data.data;
                       // /   this.product.tag
                        this.product.tag= resp.data.data.tag.split(',');

                        this.updateTimeSubCat(this.product.category_id);

                        // console.log(this.product.tag);
                    }
                }).catch(err => {
                    //console.log(err);
                })
            },
            getCategories(){
                axios.post('/get/product-category',{
                    status : 'Active'
                })
                .then(resp => {
                    if(resp.data.code === 200){
                        this.categories = resp.data.data;
                    }
                }).catch(err => {
                    //console.log(err);
                })
            },
            getCountries(){
                axios.post('/get_countries',{
                })
                .then(resp => {
                    if(resp.data.code === 200){
                        this.countries = resp.data.data;
                    }
                }).catch(err => {
                    //console.log(err);
                })
            },
           
            clickOnCat(event){

            this.subCategories=[];    
               // alert(event.target.value);
                     axios.post('/getSubCategories',{
                    //status : 'Active',
                    cat_id : event.target.value
                })
                .then(resp => {
                    if(resp.data.code === 200){
                        this.subCategories = resp.data.data;
                    }
                }).catch(err => {
                    //console.log(err);
                })

            },

             updateTimeSubCat(cat){

            this.subCategories=[];    
               // alert(event.target.value);
                     axios.post('/getSubCategories',{
                    //status : 'Active',
                    cat_id : cat
                })
                .then(resp => {
                    if(resp.data.code === 200){
                        this.subCategories = resp.data.data;
                    }
                }).catch(err => {
                    //console.log(err);
                })

            },   CkonSubmenu(id){
                //alert()
                axios.post('/getSubmenuVariation',{
                    s_cat_id: id
                }).then(resp => {
                    if(resp.data.code === 200){
                        this.submenuVariation = resp.data.data;
                       // /   this.product.tag
                      
                    }
                }).catch(err => {
                    //console.log(err);
                })
            },


        }
    }
</script>	


