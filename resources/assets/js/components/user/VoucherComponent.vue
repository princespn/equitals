<template>
	 <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="header-navbar-shadow"></div>
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
              <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Voucher Shopping</h2>
             <!--    <div class="breadcrumb-wrapper col-12">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</router-link>
                    </li>
                    <li class="breadcrumb-item active">Change Password
                    </li>
                  </ol>
                </div> -->
              </div>
            </div>
          </div>
          <!-- <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
            <div class="form-group breadcrum-right">
              <div class="dropdown">
                <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-settings"></i></button>
                <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="#">Chat</router-link><a class="dropdown-item" href="#">Email</router-link><a class="dropdown-item" href="#">Calendar</router-link></div>
              </div>
            </div>
          </div> -->
        </div>
        <div class="content-body">
          <section id="multiple-column-form">
           <div class="container-fluid">
            <div class="row">
               
            <div class="col-lg-12">
               <div class="card sssm">
                   <div class="card-body">
                        <div class="row justify-content-center">
                    <div class="col-lg-4">
                        <label>Country</label>
                       
                       <!--  <vue-tel-input
                                          @onInput="onInput"
                                          defaultCountry="IN"
                                          v-model="getcountry"
                                          placeholder="Select Country"
                                          :readonly="true"
                                        ></vue-tel-input> -->

                                        <!-- {{getcountry}} -->

                                         <select name="getcountry" v-on:change="getProducts" v-model="getcountry" id="country" class="form-control mb-1">
                                    <option value="" selected="selected">Select Country</option>
                                    <option :value="cou.code" v-for="cou in countryList" >{{cou.name}}</option>
                                   
                                  </select>
                                  <!-- {{countryList}} -->
                    </div>
                    <div class="col-lg-4">
                        <label>Select Categories</label>
                              <select name="cat" v-on:change="getProducts" v-model="cat_id" id="catid" class="form-control mb-1">
                                    <option value="" selected="selected">All Categories</option>
                                    <option :value="cat.id" v-for="cat in categories" >{{cat.name}}</option>
                                   
                                  </select>

                    </div>
                  <!--   <div class="col-lg-4">
                         <label>Search</label>
                        <input class="b-2PkghU" type="text" placeholder="Search for products or gift cards..." value="">
                    </div> -->
                </div>
                   </div>
               </div>
            </div>
               <div class="col-lg-12" >
                        <div class="dashboard-tab1 p-2 rounded-lg shadow-xs">
                            
                            <div class="row">
                               
                                 <div class="col-lg-3 mb-3 col-6" v-for="product in productsList">
                                  <div @click="viewDetails(product.id)">
                                    <div class="card border-0 w-100 p-0 rounded-xxl bg-white theme-light-bg shadow-md">
                                        <div class="card-body p-2">
                                            <div class="row">
                                                <div class="col-12">
                                                   <img :src="product.images[0]" class="img-fluid">
                                                <!--  <img src="public/user_files/images/products/flipkart.webp" class="img-fluid"> -->
                                                </div>
                                                <div class="col-12">                                               
                                                    <h4 class="fw-700 text-black-500 font-xssss ls-3 text-uppercase mb-0 mt-2 text-center"> {{product.name}}</h4>                             
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>                         
                
                            </div>
                        
                        </div>
                    </div>

                    <div class="col-lg-12 text-center"  v-if="productsList.length==0">
                      <h3> Select Country To Find Vouchers </h3>
                    </div>
        </div>
           </div>
        </section>
        </div>
      </div>
    </div>
</template>
<script>
import 'vue-tel-input/dist/vue-tel-input.css';
import VueTelInput from 'vue-tel-input';
	import Breadcrum from './BreadcrumComponent.vue';
	import Swal from 'sweetalert2';
   	export default {  
      	components: {
         	Breadcrum,VueTelInput
      	}, 
        data(){
            return{
                updatepassword: {
                    old_password: '',
                    new_password: '',
                    retype_password: '',
                   
                },
                 getcountry:'',
                    countryCode:'IN',
                    categories:'',
                    cat_id:'',
                    countryList:'',
				updatepassword:[],
				isDisableBtn:true,
                ico_arr:[],
                productsList:''
            }
        },
        computed:{
            isCompletePassword(){
                return this.updatepassword.old_password && this.updatepassword.new_password && this.updatepassword.retype_password;
            }
        },
        mounted(){
           // this.getProducts('IN');
         this.getCategories();
         this.getCountryList();
         this.checkCountryAvoid();
        },
        methods:{

          checkCountryAvoid(){ 
            axios.get('checkUserCountryAvoided', {}).then(response => {
              if (response.data.code == 200 && response.data.message == '123' ) {
                this.$router.push({name: 'dashboard'});
              }
            }).catch(error => {}); 
          },

          onInput({ number, isValid, country }) {
            //alert(country);
                    console.log(country)
                       
                this.getcountry=country.name;
                this.countryCode=country.iso2;
                 this.getProducts(country.iso2);
          },
          changeCat(){
          //alert();
              this.getProducts(this.getcountry);
          },

          countryFun(){
                   this.getProducts(this.getcountry);
          },
          getCountryList() {
                    axios.post('country-list')
                        .then(response => {
                            this.countryList = response.data.data;
                            //this.type = response.data.data[0]['type'];
                            console.log(this.countryList)
                        })
                        .catch(error => {});
                }, 
                getCategories() {
                    axios.post('get-product-categories')
                        .then(response => {
                            this.categories = response.data.data;
                            //this.type = response.data.data[0]['type'];
                        })
                        .catch(error => {});
                }, 
                  getProducts() {
                    axios.post('get-products', {country:this.getcountry,cat_id:this.cat_id})
                        .then(response => {
                           if(response.data.code == 200){
                            this.productsList = response.data.data.records;
                            this.type = response.data.data[0]['type'];
                            }else{

                                this.productsList ='';
                                 this.type = '';
                                 this.$toaster.error(response.data.message)
                            }
                        })
                        .catch(error => {});
                }, 
                 viewDetails(productId) {

            this.$router.push({
                name: 'voucher-detail',
                params: {
                    id: productId,
                    // variation_id : variationId 
                },
            });
        }
 
        }
	}
</script>